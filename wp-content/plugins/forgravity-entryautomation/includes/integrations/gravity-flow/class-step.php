<?php

namespace ForGravity\Entry_Automation\Integrations\Gravity_Flow;

use Gravity_Flow_Step_Feed_Add_On;
use Gravity_Flow_Steps;

// If Gravity Forms is not loaded, exit.
class_exists( 'GFForms' ) || die();

/**
 * Entry Automation Step for Gravity Flow
 *
 * @since     2.0
 * @package   EntryAutomation
 * @author    ForGravity
 * @copyright Copyright (c) 2019, ForGravity
 */
class Step extends Gravity_Flow_Step_Feed_Add_On {

	/**
	 * The add-on slug.
	 *
	 * @var string
	 */
	protected $_slug = 'entryautomation';

	/**
	 * The name of the class used by the add-on.
	 *
	 * @var string
	 */
	protected $_class_name = '\ForGravity\Entry_Automation\Entry_Automation';

	/**
	 * A unique key for this step type.
	 *
	 * @var string
	 */
	public $_step_type = 'entryautomation';

	/**
	 * Returns the label for the step.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_label() {

		return __( 'Entry Automation', 'forgravity_entryautomation' );

	}

	/**
	 * Returns the icon for the step.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_icon_url() {

		return fg_entryautomation()->get_base_url() . '/images/gravityflow-step.svg';

	}

	/**
	 * Returns the feeds for the add-on.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_feeds() {

		// Get feeds.
		$feeds = parent::get_feeds();

		// If no feeds were found, return.
		if ( empty( $feeds ) ) {
			return $feeds;
		}

		// Loop through feeds, remove non-submission feeds.
		foreach ( $feeds as $i => $feed ) {

			// Remove non-submission feeds.
			if ( 'submission' !== rgars( $feed, 'meta/type' ) ) {
				unset( $feeds[ $i ] );
			}

		}

		return $feeds;

	}

	/**
	 * Add the ID of the current feed to the processed feeds array for the current Add-On.
	 * Prevent adding feed if entry was deleted.
	 *
	 * @since 2.0
	 *
	 * @param array $add_on_feeds The IDs of the processed feeds.
	 * @param int   $feed_id      The ID of the processed feed.
	 *
	 * @return array
	 */
	public function maybe_set_processed_feed( $add_on_feeds, $feed_id ) {

		// Get feed.
		$feed = fg_entryautomation()->get_feed( $feed_id );

		// If this is a delete entry feed, do not mark as processed.
		if ( 'delete' === rgars( $feed, 'meta/action' ) && 'entry' === rgars( $feed, 'meta/deleteType' ) ) {
			return $add_on_feeds;
		}

		if ( ! in_array( $feed_id, $add_on_feeds ) ) {
			$add_on_feeds[] = $feed_id;
		}

		return $add_on_feeds;

	}

}
