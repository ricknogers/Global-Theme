<?php
/**
 * Delete Action for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation\Action;

use ForGravity\Entry_Automation\Action;
use ForGravity\Entry_Automation\Task;
use ForGravity\Entry_Automation\Entries;

use GFAPI;
use GFCommon;
use GFFormsModel;

/**
 * Delete Entries action.
 */
class Delete extends Action {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.2
	 * @access protected
	 * @var    object $_instance If available, contains an instance of this class.
	 */
	protected static $_instance = null;

	/**
	 * Defines the action name.
	 *
	 * @since  1.2
	 * @access protected
	 * @var    string $action Action name.
	 */
	protected $name = 'delete';





	// # ACTION SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Settings fields for configuring this Entry Automation action.
	 *
	 * @since 1.2.1
	 * @since 3.0   Move delete settings fields to the action class.
	 *
	 * @return array
	 */
	public function settings_fields() {

		// Get form.
		$form = fg_entryautomation()->get_current_form();

		// Prepare Delete Fields choice.
		$delete_fields_choice = [
			'label' => esc_html__( 'Delete Specific Fields', 'forgravity_entryautomation' ),
			'value' => 'fields',
		];
		if ( empty( $form['fields'] ) ) {
			$delete_fields_choice['disabled'] = true;
		}

		$fields = [
			'id'         => 'delete',
			'title'      => esc_html__( 'Delete Settings', 'forgravity_entryautomation' ),
			'dependency' => [
				'live'   => true,
				'fields' => [
					[
						'field'  => 'action',
						'values' => [ 'delete' ],
					],
				],
			],
			'sections'   => [
				[
					'id'     => '',
					'title'  => esc_html__( 'Delete Settings', 'forgravity_entryautomation' ),
					'fields' => [
						[
							'name'          => 'deleteType',
							'label'         => esc_html__( 'Deletion Type', 'forgravity_entryautomation' ),
							'type'          => 'radio',
							'default_value' => 'entry',
							'tooltip'       => sprintf(
								'<h6>%s</h6>%s',
								esc_html__( 'Deletion Type', 'forgravity_entryautomation' ),
								esc_html__( 'Choose to delete the entire entry from the database or specific fields. If the Entry Type is Draft Submissions, you can only delete the entire entry.', 'forgravity_entryautomation' )
							),
							'choices'       => [
								[
									'label' => esc_html__( 'Delete Entry', 'forgravity_entryautomation' ),
									'value' => 'entry',
								],
								$delete_fields_choice,
							],
						],
						[
							'name'       => 'moveToTrash',
							'label'      => esc_html__( 'Move To Trash', 'forgravity_entryautomation' ),
							'type'       => 'checkbox',
							'tooltip'    => sprintf(
								'<h6>%s</h6>%s',
								esc_html__( 'Move To Trash', 'forgravity_entryautomation' ),
								esc_html__( 'When enabled, entries will be moved to the trash section instead of being immediately deleted from the database.', 'forgravity_entryautomation' )
							),
							'choices'    => [
								[
									'name'  => 'moveToTrash',
									'label' => esc_html__( 'Move entries to trash instead of deleting them immediately', 'forgravity_entryautomation' ),
								],
							],
							'dependency' => [
								'live'   => true,
								'fields' => [
									[
										'field'  => 'deleteType',
										'values' => [ 'entry' ],
									],
									[
										'field'  => 'entryType',
										'values' => [ 'entry' ],
									],
								],
							],
						],
						[
							'name'       => 'deleteFields',
							'label'      => esc_html__( 'Fields to Delete', 'forgravity_entryautomation' ),
							'type'       => 'checkbox',
							'choices'    => self::get_delete_fields_choices(),
							'no_choices' => esc_html__( 'You must add at least one form field.', 'forgravity_entryautomation' ),
							'dependency' => [
								'live'   => true,
								'fields' => [
									[
										'field'  => 'deleteType',
										'values' => [ 'fields' ],
									],
								],
							],
						],
					],
				],
			],
		];

		if ( ! ( $fillablepdfs_settings = self::get_fillablepdfs_settings_fields( $form ) ) ) {
			return $fields;
		}

		$fields['sections'][0]['fields'][] = $fillablepdfs_settings;

		return $fields;

	}

	/**
	 * Get form fields and entry meta as checkbox choices.
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public static function get_delete_fields_choices() {

		// Initialize choices array.
		$choices = [];

		// Get form.
		$form = fg_entryautomation()->get_current_form();

		// If form could not be retrieved, return.
		if ( ! $form ) {
			return $choices;
		}

		// Add entry meta fields.
		$entry_meta = GFFormsModel::get_entry_meta( $form['id'] );
		$keys       = array_keys( $entry_meta );
		foreach ( $keys as $key ) {
			array_push( $form['fields'], [ 'id' => $key, 'label' => $entry_meta[ $key ]['label'] ] );
		}

		// Convert meta field objects.
		$form = GFFormsModel::convert_field_objects( $form );

		/**
		 * Loop through fields, add to choices.
		 *
		 * @var \GF_Field $field
		 */
		foreach ( $form['fields'] as $field ) {

			// Skip display only fields.
			if ( rgobj( $field, 'displayOnly' ) ) {
				continue;
			}

			// Set admin label context.
			$field->set_context_property( 'use_admin_label', true );

			$choices[] = [
				'name'  => sprintf( 'deleteFields[%s]', esc_attr( $field->id ) ),
				'label' => esc_html( GFCommon::get_label( $field ) ),
			];

		}

		return $choices;

	}

	/**
	 * Icon class for Entry Automation settings button.
	 *
	 * @since  1.2
	 *
	 * @return string
	 */
	public function get_icon() {

		if ( version_compare( \GFForms::$version, '2.5-beta-1', '<' ) ) {
			return 'fa-trash';
		}

		return parent::get_icon();

	}

	/**
	 * Action label, used in Entry Automation settings.
	 *
	 * @since  1.2
	 *
	 * @return string
	 */
	public function get_label() {

		return esc_html__( 'Delete Entries', 'forgravity_entryautomation' );

	}

	/**
	 * Action short label, used in Entry Automation Tasks table.
	 *
	 * @since  1.2
	 *
	 * @return string
	 */
	public function get_short_label() {

		return esc_html__( 'Delete', 'forgravity_entryautomation' );

	}





	// # RUNNING ACTION ------------------------------------------------------------------------------------------------

	/**
	 * Process task.
	 *
	 * @since  1.2
	 * @since  3.0 Deprecated the $task and $form parameters.
	 *
	 * @return bool
	 */
	public function run() {

		// Return false if the task property isn't set correctly.
		if ( ! $this->task instanceof Task ) {
			return false;
		}

		// Prepare the task and form.
		$task = $this->task;
		$form = $this->form;

		// Prepare search criteria.
		$search_criteria = $task->get_search_criteria();

		// Prepare paging criteria.
		$paging = Entries::$paging;

		// Loop until all entries have been processed.
		while ( $task->entries_processed < $task->found_entries ) {

			// Log the page number.
			fg_entryautomation()->log_debug( __METHOD__ . '(): Deleting group ' . ( round( $task->entries_processed / $paging['page_size'] ) + 1 ) . ' of ' . ( round( $task->found_entries / $paging['page_size'] ) ) );

			// Get entries.
			$args    = [
				'form_id'         => $form['id'],
				'search_criteria' => $search_criteria,
			];
			$entries = $this->entries->get( $args, rgar( $task->meta, 'entryType' ) );

			// If no more entries were found, break.
			if ( empty( $entries ) ) {
				fg_entryautomation()->log_debug( __METHOD__ . '(): No entries were found for this page.' );
				break;
			}

			// Loop through entries.
			foreach ( $entries as $entry ) {

				switch ( rgar( $task->meta, 'deleteType' ) ) {

					case 'fields':
						// Loop through fields, delete enabled fields.
						foreach ( $task->meta['deleteFields'] as $field_id => $enabled ) {

							// If field is not set for deletion, skip.
							if ( '1' != $enabled ) {
								continue;
							}

							// Get field.
							$field = GFAPI::get_field( $form, $field_id );

							// Delete uploaded files.
							if ( $field && in_array( $field->type, [ 'fileupload', 'post_image' ] ) ) {

								// Get field value.
								$field_value = rgar( $entry, $field_id );

								if ( $field->multipleFiles && ! empty( $field_value ) ) {
									$files = json_decode( $field_value, true );
								} else {
									$files = [ $field_value ];
								}

								// Loop through files, delete.
								if ( is_array( $files ) && ! empty( $files ) ) {
									foreach ( $files as $url ) {
										$file_path = GFFormsModel::get_physical_file_path( $url );
										$file_path = apply_filters( 'gform_file_path_pre_delete_file', $file_path, $url );
										if ( file_exists( $file_path ) ) {
											unlink( $file_path );
										}
									}
								}

							}

							// Delete field value.
							GFAPI::update_entry_field( $entry['id'], $field_id, '' );

						}

						// Delete Fillable PDFs files.
						if ( function_exists( 'fg_fillablepdfs' ) && ( $feed_ids = $this->get_feeds_ids( 'forgravity-fillablepdfs' ) ) ) {

							// Get PDFs for entry.
							$entry_pdfs = fg_fillablepdfs()->get_entry_pdfs( $entry );

							// Loop through found PDFs and add for enabled feeds.
							foreach ( $entry_pdfs as $entry_pdf ) {

								if ( ! in_array( (int) $entry_pdf['feed_id'], $feed_ids, true ) ) {
									continue;
								}

								fg_fillablepdfs()->delete_pdf( $entry_pdf );

							}

						}

						break;

					default:
						if ( rgar( $task->meta, 'entryType' ) !== 'draft_submission' && rgar( $task->meta, 'moveToTrash' ) ) {
							GFAPI::update_entry_property( $entry['id'], 'status', 'trash' );
						} else {
							Entries::delete( $entry );
						}

						break;

				}

				// Increase entries processed count.
				$task->entries_processed++;

			}

			if ( rgar( $task->meta, 'deleteType' ) === 'fields' ) {
				$paging['offset'] += $paging['page_size'];
			}

		}

		// Log that deletion has been completed.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Deletion completed.' );

		/**
		 * Executed after entries have been deleted.
		 *
		 * @param Task $task Entry Automation Task.
		 * @param array $form The Form object.
		 */
		gf_do_action( [ 'fg_entryautomation_after_deletion', $form['id'] ], $task, $form );

		return true;

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Returns the selected feed IDs.
	 *
	 * @since 4.0
	 *
	 * @param string $addon Add-On to return selected feeds for.
	 *
	 * @return int[]
	 */
	private function get_feeds_ids( $addon ) {

		// Get selected Feeds from Task meta.
		$feeds = rgars( $this->task->meta, 'deleteFeeds/' . $addon );

		// Filter out feeds that are disabled.
		$feeds = array_filter( $feeds, function( $enabled ) {
			return $enabled;
		} );

		// Return only the feed IDs.
		return array_map( 'intval', array_keys( $feeds ) );

	}

	/**
	 * Returns the Delete Feeds setting if Fillable PDFs is activated with available feeds.
	 *
	 * @since 4.0
	 *
	 * @param array $form The Form object.
	 *
	 * @return array
	 */
	private static function get_fillablepdfs_settings_fields( $form ) {

		if ( ! function_exists( 'fg_fillablepdfs' ) ) {
			return [];
		}

		$feeds = fg_fillablepdfs()->get_active_feeds( $form['id'] );

		if ( empty( $feeds ) ) {
			return [];
		}

		$feed_choices = [];
		foreach ( $feeds as $feed ) {
			$feed_choices[] = [
				'name'  => sprintf( 'deleteFeeds[%s][%d]', esc_attr( fg_fillablepdfs()->get_slug() ), $feed['id'] ),
				'label' => esc_html( rgars( $feed, 'meta/feedName' ) ),
			];
		}

		return [
			'name'       => sprintf( 'deleteFeeds[%s]', esc_attr( fg_fillablepdfs()->get_slug() ) ),
			'type'       => 'checkbox',
			'dependency' => [
				'live'   => true,
				'fields' => [
					[
						'field'  => 'deleteType',
						'values' => [ 'fields' ],
					],
				],
			],
			'label'      => esc_html__( 'Delete Generated Fillable PDFs', 'forgravity_entryautomation' ),
			'choices'    => $feed_choices,
		];

	}

}
