<?php
/**
 * Merge Tags Class for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation\Task;

use ForGravity\Entry_Automation\Date;
use ForGravity\Entry_Automation\Task;
use GFAPI;
use GFCommon;

class_exists( '\GFForms' ) || die();

/**
 * Entry Automation Task Merge Tags class file.
 * Replace merge tags specific to a Task.
 *
 * @since     4.0
 * @package   ForGravity\Entry_Automation
 * @author    ForGravity
 * @copyright Copyright (c) 2021, ForGravity
 */
class Merge_Tags {

	/**
	 * Regex pattern for finding Date merge tags.
	 *
	 * @var string
	 */
	const DATE_PATTERN = '/{(date):?([^_]*?)}/';

	/**
	 * Regex pattern for finding Date Range merge tags.
	 *
	 * @var string
	 */
	const DATE_RANGE_PATTERN = '/{date_range_(start|end):?(.*?)(?:\s)?}/ism';

	/**
	 * The current Task object.
	 *
	 * @since 3.4
	 *
	 * @var Task
	 */
	private $task;

	/**
	 * The current Form object.
	 *
	 * @since 3.4
	 *
	 * @var array
	 */
	private $form;

	/**
	 * Initialize Merge Tags class.
	 *
	 * @since 3.4
	 *
	 * @param Task $task Task object to generate merge tags values for.
	 */
	public function __construct( $task ) {

		$this->task = $task;
		$this->form = GFAPI::get_form( $this->task->form_id );

	}

	/**
	 * Replace merge tags in provided text.
	 *
	 * @since 3.4
	 *
	 * @param string $text       Text to replace merge tags in.
	 * @param array  $entry      Entry to use for merge tag data.
	 * @param false  $url_encode Encode URLs.
	 * @param bool   $esc_html   Escape HTML.
	 * @param bool   $nl2br      Convert new lines to line breaks.
	 * @param string $format     Text format ("text" or "html").
	 *
	 * @return string
	 */
	public function replace_tags( $text, $entry = [], $url_encode = false, $esc_html = true, $nl2br = true, $format = 'html' ) {

		$simple_tags = $this->get_simple_tags();

		// Replace simple tags.
		$text = str_replace( array_keys( $simple_tags ), array_values( $simple_tags ), $text );

		// Search for Date Range merge tags.
		preg_match_all( self::DATE_RANGE_PATTERN, $text, $date_range_matches, PREG_SET_ORDER );
		if ( ! empty( $date_range_matches ) ) {
			foreach ( $date_range_matches as $match ) {
				$text = $this->replace_date_range_tag( $text, $match, $url_encode, $esc_html, $nl2br, $format );
			}
		}

		// Search for Date merge tags.
		preg_match_all( self::DATE_PATTERN, $text, $date_matches, PREG_SET_ORDER );
		if ( ! empty( $date_matches ) ) {
			foreach ( $date_matches as $match ) {
				$text = $this->replace_date_range_tag( $text, $match, $url_encode, $esc_html, $nl2br, $format );
			}
		}

		// Replace standard merge tags.
		$text = GFCommon::replace_variables( $text, $this->form, $entry, $url_encode, $esc_html, $nl2br, $format );

		return $text;

	}

	/**
	 * Replace Date Range merge tags in string.
	 *
	 * @since 3.4
	 *
	 * @param string $text       Text to replace merge tags in.
	 * @param array  $match      Regex match for specific merge tag instance.
	 * @param false  $url_encode Encode URLs.
	 * @param bool   $esc_html   Escape HTML.
	 * @param bool   $nl2br      Convert new lines to line breaks.
	 * @param string $format     Text format ("text" or "html").
	 *
	 * @return string
	 */
	private function replace_date_range_tag( $text, $match, $url_encode = false, $esc_html = true, $nl2br = true, $format = 'html' ) {

		$full_tag    = $match[0];
		$date_string = preg_match( self::DATE_RANGE_PATTERN, $text ) ? $this->get_date_range_date_string( $match[1] ) : ( new Date( Date::FORMAT_DATETIME ) )->format(); // phpcs:ignore
		$property    = preg_match( self::DATE_RANGE_PATTERN, $text ) ? $match[2] : 'format:' . $match[2];

		if ( ! empty( $date_string ) ) {
			// Expand all modifiers, skipping escaped colons.
			$exploded = explode( ':', str_replace( '\:', '|COLON|', $property ) );

			/*
			 * If there is a `:format` modifier in a merge tag, grab the formatting
			 *
			 * The `:format` modifier should always have the format follow it; it's the next item in the array
			 * In `foo:format:bar`, "bar" will be the returned format
			 */
			$format_key_index = array_search( 'format', $exploded, true );
			$date_format      = false;
			if ( false !== $format_key_index && isset( $exploded[ $format_key_index + 1 ] ) ) {
				// Return escaped colons placeholder.
				$date_format = str_replace( '|COLON|', ':', $exploded[ $format_key_index + 1 ] );
			}

			$is_human             = in_array( 'human', $exploded, true ); // {date_created:human}
			$is_diff              = in_array( 'diff', $exploded, true ); // {date_created:diff}
			$is_raw               = in_array( 'raw', $exploded, true ); // {date_created:raw}
			$is_timestamp         = in_array( 'timestamp', $exploded, true ); // {date_created:timestamp}
			$include_time         = in_array( 'time', $exploded, true );  // {date_created:time}
			$date_local_timestamp = ( new Date( 'timestamp', $date_string ) )->format(); // phpcs:ignore

			// If we're using time diff, we want to have a different default format.
			if ( empty( $date_format ) ) {
				// translators: %s: relative time from now, used for generic date comparisons. "1 day ago", or "20 seconds ago".
				$date_format = $is_diff ? esc_html__( '%s ago', 'gravityforms' ) : get_option( 'date_format' );
			}

			if ( $is_raw ) {
				$formatted_date = $date_string;
			} elseif ( $is_timestamp ) {
				$formatted_date = $date_local_timestamp;
			} elseif ( $is_diff ) {
				$formatted_date = sprintf( $date_format, human_time_diff( $date_local_timestamp ) );
			} elseif ( $include_time ) {
				$formatted_date = sprintf(
					esc_html__( '%1$s at %2$s', 'gravityforms' ),
					( new Date( $date_format, $date_local_timestamp ) )->format(), // phpcs:ignore
					( new Date( GFCommon::get_default_time_format(), $date_local_timestamp ) )->format() // phpcs:ignore
				);
			} else {
				$formatted_date = ( new Date( $date_format, $date_local_timestamp ) )->format(); // phpcs:ignore
			}
		} else {
			$formatted_date = '';
		}

		$formatted_date = GFCommon::format_variable_value( $formatted_date, $url_encode, $esc_html, $format, $nl2br );

		return str_replace( $full_tag, $formatted_date, $text );

	}

	/**
	 * Returns the date string for part of the Date Range.
	 *
	 * @since 3.4
	 *
	 * @param string $part Which part of the Date Range to return. ("start" or "end").
	 *
	 * @return string
	 */
	private function get_date_range_date_string( $part = 'start' ) {

		$search_criteria = $this->task->get_search_criteria();

		if ( $search_criteria['target'] === 'custom' && $part === 'start' && ! rgars( $this->task->meta, 'dateRange/' . $part ) ) {
			return '';
		}

		return rgar( $search_criteria, sprintf( '%s_date', $part ) );

	}

	/**
	 * Returns the simple Task merge tags and their values.
	 *
	 * @since 3.4
	 *
	 * @return array
	 */
	private function get_simple_tags() {

		return [
			'{timestamp}'         => ( new Date() )->format( 'U' ), // phpcs:ignore
			'{task_name}'         => rgar( $this->task->meta, 'feedName' ),
			'{task_id}'           => $this->task->id,
			'{found_entries}'     => $this->task->found_entries,
			'{entries_processed}' => $this->task->entries_processed,
		];

	}

}
