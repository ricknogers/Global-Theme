<?php
/**
 * The Entries class.
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation;

use DateTimeZone;
use GFAPI;
use GFExport;
use GFFormsModel;
use WP_Error;

/**
 * The Entries class.
 *
 * @since     5.0
 * @package   ForGravity\Entry_Automation
 * @author    ForGravity
 * @copyright Copyright (c) 2021, ForGravity
 */
class Entries {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  5.0
	 *
	 * @var    Entries $_instance If available, contains an instance of this class.
	 */
	protected static $_instance = null;

	/**
	 * The paging criteria.
	 *
	 * @since 5.0
	 *
	 * @var int[]
	 */
	public static $paging = [
		'offset'    => 0,
		'page_size' => 50,
	];

	/**
	 * The form ID.
	 *
	 * @since 5.0
	 *
	 * @var int
	 */
	private $form_id;

	/**
	 * The search criteria.
	 *
	 * @since 5.0
	 *
	 * @var array
	 */
	private $search_criteria;

	/**
	 * The sorting criteria.
	 *
	 * @since 5.0
	 *
	 * @var array
	 */
	private $sorting;

	/**
	 * The entries total count.
	 *
	 * @since 5.0
	 *
	 * @var int
	 */
	private $total_count;

	/**
	 * Get instance of this class.
	 *
	 * @since  5.0
	 *
	 * @return Entries
	 */
	public static function get_instance() {

		if ( null === static::$_instance ) {
			static::$_instance = new static();
		}

		return static::$_instance;

	}

	/**
	 * Magic __get function to dispatch a call to retrieve a private property.
	 *
	 * @since 5.0
	 *
	 * @param string $property The property name.
	 */
	public function __get( $property ) {

		if ( method_exists( $this, 'get_' . $property ) ) {

			return call_user_func( [ $this, 'get_' . $property ] );

		} else {

			/* translators: The class property name. */
			return new WP_Error( 'invalid_property', sprintf( esc_html__( "Can't get property %s", 'forgravity_entryautomation' ), $property ) );

		}

	}

	/**
	 * Get the total entry count.
	 *
	 * @since 5.0
	 *
	 * @param array  $args {
	 *     The arguments.
	 *
	 *     @type int     $form_id         The form ID.
	 *     @type array   $search_criteria The search criteria.
	 * }
	 * @param string $type The data type, can be 'entry' or 'draft_submission'.
	 *
	 * @return int
	 */
	public function get_total_count( $args, $type = 'entry' ) {

		// Setup properties.
		$this->form_id         = rgar( $args, 'form_id' );
		$this->search_criteria = rgar( $args, 'search_criteria' );

		switch ( $type ) {

			case 'draft_submission':
				$this->get_draft_submissions();

				break;

			default:
				add_filter( 'gform_gf_query_sql', [ $this, 'query_sql' ] );

				$this->total_count = GFAPI::count_entries( $this->form_id, $this->search_criteria );

				remove_filter( 'gform_gf_query_sql', [ $this, 'query_sql' ] );

		}

		return $this->total_count;

	}

	/**
	 * Get the entries.
	 *
	 * @since 5.0
	 *
	 * @param array  $args {
	 *     The arguments.
	 *
	 *     @type int     $form_id         The form ID.
	 *     @type array   $search_criteria The search criteria.
	 *     @type array   $sorting         The sorting criteria.
	 *     @type array   $paging          The paging criteria.
	 * }
	 * @param string $type The data type, can be 'entry' or 'draft_submission'.
	 *
	 * @return array|WP_Error
	 */
	public function get( $args, $type = 'entry' ) {

		$default_args = [
			'sorting'         => null,
			'paging'          => self::$paging,
			'search_criteria' => [],
		];

		$args = wp_parse_args( $args, $default_args );

		// Setup properties.
		$this->form_id         = rgar( $args, 'form_id' );
		$this->search_criteria = rgar( $args, 'search_criteria' );
		$this->sorting         = rgar( $args, 'sorting' );

		switch ( $type ) {

			case 'draft_submission':
				return $this->get_draft_submissions( false );

			default:
				add_filter( 'gform_gf_query_sql', [ $this, 'query_sql' ] );

				$entries = GFAPI::get_entries( $args['form_id'], $args['search_criteria'], $args['sorting'], $args['paging'], $this->total_count );

				remove_filter( 'gform_gf_query_sql', [ $this, 'query_sql' ] );

				return $entries;

		}

	}

	/**
	 * Get the draft submissions.
	 *
	 * We don't use GFFormModel::get_draft_submissions() because it doesn't support get data by form and also does not contain email data.
	 *
	 * @since 5.0
	 *
	 * @param boolean $count_entries By default, it sets to true to get total entries count; set to false if it's used to fetch paged and sorted entries.
	 *
	 * @return array
	 */
	private function get_draft_submissions( $count_entries = true ) {

		global $wpdb;

		GFFormsModel::purge_expired_draft_submissions();

		$table = version_compare( GFFormsModel::get_database_version(), '2.3-dev-1', '<' ) ? GFFormsModel::get_incomplete_submissions_table_name() : GFFormsModel::get_draft_submissions_table_name();

		// Get a single entry of draft submissions.
		if ( rgar( $this->search_criteria, 'uuid' ) ) {
			$row               = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE uuid = %s", $this->search_criteria['uuid'] ), ARRAY_A );
			$form              = GFAPI::get_form( $this->form_id );
			$row['submission'] = apply_filters( 'gform_incomplete_submission_post_get', $row['submission'], $row['uuid'], $form );

			return [ $row ];
		}

		// Use date_created column as the default sorting column.
		$order = 'DESC';
		if ( ! $count_entries ) {
			$sort_key       = rgar( $this->sorting, 'key' );
			$sort_direction = rgar( $this->sorting, 'direction' );

			if ( $sort_key === 'date_created' ) {
				$order = $sort_direction;
			}
		}
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE form_id = %d ORDER BY date_created $order", $this->form_id ), ARRAY_A );
		$form = GFAPI::get_form( $this->form_id );

		foreach ( $rows as &$row ) {
			$row['submission'] = apply_filters( 'gform_incomplete_submission_post_get', $row['submission'], $row['uuid'], $form );
		}

		$rows = $this->filter_draft_submissions( $rows );

		if ( ! $count_entries ) {
			$rows = $this->sort_draft_submissions( $rows );
		}

		$this->total_count = count( $rows );

		return $rows;

	}

	/**
	 * Filter the draft submissions with the search criteria.
	 *
	 * @since 5.0
	 *
	 * @param array $rows The draft submissions.
	 *
	 * @return array
	 */
	private function filter_draft_submissions( $rows ) {

		$rows = $this->filter_draft_submissions_date_range( $rows );
		$rows = $this->filter_draft_submissions_conditional_logic( $rows );

		return $rows;

	}

	/**
	 * Filter the draft submissions with the date range criteria.
	 *
	 * @since 5.0
	 *
	 * @param array $rows The draft submissions.
	 *
	 * @return array
	 */
	private function filter_draft_submissions_date_range( $rows ) {

		if ( rgar( $this->search_criteria, 'target' ) === 'all' ) {
			return $rows;
		}

		$date_field = rgar( $this->search_criteria, 'date_field' );

		if ( $date_field === 'date_created' ) {
			$rows = array_filter(
				$rows,
				function ( $row ) {
					// date_created column in draft_submissions table is in mysql (UTC) time, convert it to  the current time.
					$offset       = get_option( 'gmt_offset' );
					$date_created = $row['date_created'];
					if ( $offset ) {
						$date_created = ( new Date( Date::FORMAT_DATETIME, strtotime( $date_created ), new DateTimeZone( $offset ) ) )->format(); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
					}

					return $date_created > rgar( $this->search_criteria, 'start_date' ) && $date_created < rgar( $this->search_criteria, 'end_date' );
				}
			);
		} elseif ( is_numeric( $date_field ) ) {
			$rows = array_filter(
				$rows,
				function ( $row ) use ( $date_field ) {
					$partial_entry = self::decode_draft_submission( $row );

					return $partial_entry[ $date_field ] > rgar( $this->search_criteria, 'start_date' ) && $partial_entry[ $date_field ] < rgar( $this->search_criteria, 'end_date' );
				}
			);
		}

		return $rows;

	}

	/**
	 * Filter the draft submissions with the feed conditional logic criteria.
	 *
	 * @since 5.0
	 *
	 * @param array $rows The draft submissions.
	 *
	 * @return array
	 */
	private function filter_draft_submissions_conditional_logic( $rows ) {

		$field_filters = rgar( $this->search_criteria, 'field_filters' );
		if ( empty( $field_filters ) ) {
			return $rows;
		}

		$form = GFAPI::get_form( $this->form_id );

		$search_mode = isset( $field_filters['mode'] ) ? strtolower( $field_filters['mode'] ) : 'all';
		unset( $field_filters['mode'] );

		$entry_meta      = array_merge( fg_entryautomation()->get_feed_condition_entry_meta(), fg_entryautomation()->get_feed_condition_entry_properties() );
		$entry_meta_keys = array_keys( $entry_meta );

		$rows = array_filter( $rows, function( $row ) use ( $form, $field_filters, $search_mode, $entry_meta_keys ) {
			$partial_entry = self::decode_draft_submission( $row );
			$matches       = 0;

			foreach ( $field_filters as $field_filter ) {

				if ( ! rgar( $field_filter, 'key' ) ) {
					continue;
				}

				$result = $this->is_value_match( $form, $partial_entry, $entry_meta_keys, $field_filter );

				if ( $search_mode === 'any' && $result ) {
					return $result;
				}

				if ( $result ) {
					$matches++;
				}

			}

			if ( $search_mode === 'all' && $matches === count( $field_filters ) ) {
				return true;
			}

			return false;

		} );

		return $rows;

	}

	/**
	 * Sort the draft submissions for export tasks.
	 *
	 * @since 5.0
	 *
	 * @param array $rows The draft submissions.
	 *
	 * @return array
	 */
	private function sort_draft_submissions( $rows ) {

		$sort_key       = rgar( $this->sorting, 'key' );
		$sort_direction = rgar( $this->sorting, 'direction' );

		uasort( $rows, function ( $a, $b ) use ( $sort_key, $sort_direction ) {
			$a = rgar( self::decode_draft_submission( $a ), $sort_key );
			$b = rgar( self::decode_draft_submission( $b ), $sort_key );

			if ( $a == $b ) {
				return 0;
			}

			if ( $sort_direction === 'ASC' ) {
				return ( $a < $b ) ? - 1 : 1;
			} else {
				return ( $a > $b ) ? - 1 : 1;
			}
		} );

		return $rows;

	}

	/**
	 * Customize the SQL clauses.
	 *
	 * @since 3.0
	 *
	 * @param array $clauses An array with all the SQL clauses: select, from, join, where, order, paginate.
	 *
	 * @return array
	 */
	public function query_sql( $clauses ) {

		$clauses = $this->filter_entry_status( $clauses );
		$clauses = $this->filter_entry_date_range( $clauses );

		return $clauses;

	}

	/**
	 * The logic that filter the results by entry date range.
	 *
	 * @since 3.0
	 *
	 * @param array $clauses An array with all the SQL clauses: select, from, join, where, order, paginate.
	 *
	 * @return array
	 */
	private function filter_entry_date_range( $clauses ) {

		$target = rgar( $this->search_criteria, 'target' );

		if ( $target !== 'custom' ) {
			return $clauses;
		}

		$date_field = rgar( $this->search_criteria, 'date_field' );

		if ( $date_field === 'date_updated' ) {

			$clauses['where'] = str_replace( 'date_created', $date_field, $clauses['where'] );

		} elseif ( is_numeric( $date_field ) ) {

			global $wpdb;

			$clauses['join'] .= " INNER JOIN {$wpdb->prefix}gf_entry_meta as fgem ON t1.id = fgem.entry_id AND fgem.meta_key = '{$date_field}'";
			$clauses['where'] = str_replace( '`t1`.`date_created`', 'fgem.meta_value', $clauses['where'] );

		}

		return $clauses;

	}

	/**
	 * The logic that custom the entry status query.
	 *
	 * @since 3.0
	 *
	 * @param array $clauses An array with all the SQL clauses: select, from, join, where, order, paginate.
	 *
	 * @return array
	 */
	private function filter_entry_status( $clauses ) {

		$entry_statuses = rgar( $this->search_criteria, 'status' );

		if ( ! is_array( $entry_statuses ) ) {
			return $clauses;
		}

		// Whitelist the status.
		$allowed = wp_list_pluck( fg_entryautomation()->get_entry_statuses(), 'value' );

		$is_read = $is_starred = null;

		// Unset read, unread and starred.
		if ( in_array( 'read', $entry_statuses, true ) && in_array( 'unread', $entry_statuses, true ) ) {
			$entry_statuses = array_diff( $entry_statuses, [ 'read' ] );
			$entry_statuses = array_diff( $entry_statuses, [ 'unread' ] );
		} else {
			if ( in_array( 'read', $entry_statuses ) ) {
				$entry_statuses = array_diff( $entry_statuses, [ 'read' ] );
				$is_read        = 1;
			}
			if ( in_array( 'unread', $entry_statuses ) ) {
				$entry_statuses = array_diff( $entry_statuses, [ 'unread' ] );
				$is_read        = 0;
			}
		}
		if ( in_array( 'starred', $entry_statuses ) ) {
			$entry_statuses = array_diff( $entry_statuses, [ 'starred' ] );
			$is_starred     = 1;
		}

		$statuses = [];
		foreach ( $entry_statuses as $status ) {
			if ( ! in_array( $status, $allowed, true ) ) {
				continue;
			}

			$statuses[] = "`t1`.`status` = '$status'";
		}

		// When we set the status to an array, the SQL statement will turn into something like:
		// WHERE (`t1`.`form_id` IN (1264) AND (`t1`.`status` AND `t1`.`date_created` >= '1970-01-01 00:00:00' AND `t1`.`date_created` <= '2020-12-25 14:41:03')).
		if ( $is_read === 0 ) {
			$statuses[] = '`t1`.`is_read` = 0';
		}

		if ( $is_read === 1 ) {
			$statuses[] = '`t1`.`is_read` = 1';
		}

		if ( $is_starred === 1 ) {
			$statuses[] = '`t1`.`is_starred` = 1';
		}

		if ( ! empty( $statuses ) ) {
			$clauses['where'] = str_replace( '`t1`.`status`', '(' . implode( ' OR ', $statuses ) . ')', $clauses['where'] );
		} else {
			$clauses['where'] = str_replace( '`t1`.`status` AND ', '', $clauses['where'] );
		}

		return $clauses;

	}

	/**
	 * Helper method to check if the field value matches the conditional logic.
	 *
	 * This method is an adaption of Entry_Automation::evaluate_conditional_logic().
	 *
	 * @since 5.0
	 *
	 * @param array $form The form object.
	 * @param array $partial_entry The partial entry data.
	 * @param array $entry_meta_keys The entry meta keys.
	 * @param array $field_filter The conditional logic.
	 *
	 * @return bool
	 */
	private function is_value_match( $form, $partial_entry, $entry_meta_keys, $field_filter ) {

		$key      = rgar( $field_filter, 'key' );
		$value    = rgar( $field_filter, 'value' );
		$operator = rgar( $field_filter, 'operator' );

		if ( in_array( $key, $entry_meta_keys ) ) {
			return GFFormsModel::is_value_match( rgar( $partial_entry, $key ), $value, $operator, null, $field_filter, $form );
		} else {
			$source_field = GFFormsModel::get_field( $form, $key );
			$field_value  = empty( $partial_entry ) ? GFFormsModel::get_field_value( $source_field, [] ) : GFFormsModel::get_lead_field_value( $partial_entry, $source_field );

			return GFFormsModel::is_value_match( $field_value, $value, $operator, $source_field, $field_filter, $form );

		}

	}

	/**
	 * Delete entries by entry ID or UUID.
	 *
	 * @since 5.0
	 *
	 * @param array $entry The entry or draft submissions object.
	 */
	public static function delete( $entry ) {

		$entry_id = rgar( $entry, 'id' ) ? rgar( $entry, 'id' ) : rgar( $entry, 'uuid' );

		if ( is_numeric( $entry_id ) ) {
			GFAPI::delete_entry( $entry_id );

			return;
		}

		GFFormsModel::delete_draft_submission( $entry_id );

	}

	/**
	 * Decode a draft submission into the standard entry object.
	 *
	 * @since 5.0
	 *
	 * @param array $row The draft submission.
	 *
	 * @return array
	 */
	public static function decode_draft_submission( $row ) {

		return json_decode( $row['submission'], true )['partial_entry'];

	}

	/**
	 * Helper to get the list field row count.
	 *
	 * The method for draft submission is derived from GFExport::get_field_row_count().
	 *
	 * @since 5.0
	 *
	 * @param array  $form               The form.
	 * @param array  $exported_field_ids The exported field IDs.
	 * @param int    $found_entries      The found entries count.
	 * @param string $type               The entry type.
	 *
	 * @return array
	 */
	public static function get_field_row_count( $form, $exported_field_ids, $found_entries, $type = 'entry' ) {

		if ( $type !== 'draft_submission' ) {
			return GFExport::get_field_row_count( $form, $exported_field_ids, $found_entries );
		}

		$list_fields = GFAPI::get_fields_by_type( $form, array( 'list' ), true );

		// Only getting fields that have been exported.
		$field_ids = [];
		foreach ( $list_fields as $field ) {
			if ( in_array( $field->id, $exported_field_ids ) && $field->enableColumns ) {
				$field_ids[] = $field->id;
			}
		}

		if ( empty( $field_ids ) ) {
			return array();
		}

		$page_size = 200;
		$offset    = 0;

		$row_counts = array();

		$go_to_next_page = true;

		while ( $go_to_next_page ) {

			$args    = [
				'form_id' => $form['id'],
			];
			$results = self::get_instance()->get( $args, 'draft_submission' );

			foreach ( $results as $result ) {
				$entry = self::decode_draft_submission( $result );

				foreach ( $field_ids as $field_id ) {
					$list              = unserialize( $entry[ $field_id ] );
					$current_row_count = isset( $row_counts[ $field_id ] ) ? intval( $row_counts[ $field_id ] ) : 0;

					if ( is_array( $list ) && count( $list ) > $current_row_count ) {
						$row_counts[ $field_id ] = count( $list );
					}
				}
			}

			$offset += $page_size;

			$go_to_next_page = count( $results ) == $page_size;
		}

		return $row_counts;

	}

}
