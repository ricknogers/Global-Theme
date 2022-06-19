<?php
/**
 * Upload Writer Class for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation\Action\Export;

use ForGravity\Entry_Automation\Action\Export;
use ForGravity\Entry_Automation\Entries;
use ForGravity\Entry_Automation\Task;
use GFAPI;
use GFCommon;
use GFSignature;
use ZipArchive;

class_exists( '\GFForms' ) || die();

/**
 * Write uploaded files and export file to a ZIP archive.
 *
 * @since 4.0
 */
class Upload_Writer {

	/**
	 * The Export Action.
	 *
	 * @since 4.0
	 * @var   Export $action
	 */
	private $action;

	/**
	 * The Export Action's Task.
	 *
	 * @since 4.0
	 * @var   Task
	 */
	private $task;

	/**
	 * The ZipArchive instance.
	 *
	 * @since 4.0
	 * @var   ZipArchive
	 */
	private $zip_archive;

	/**
	 * The file name for the ZIP archive.
	 *
	 * @since 4.0
	 * @var  string
	 */
	private $file_name;

	/**
	 * The Entries instance.
	 *
	 * @since 5.0
	 *
	 * @var Entries
	 */
	protected $entries;

	/**
	 * Initializes a new File Upload writer.
	 *
	 * @since 4.0
	 *
	 * @param Export $action The Export action.
	 */
	public function __construct( $action ) {

		$this->action  = $action;
		$this->task    = $action->task;
		$this->entries = $action->entries;

	}

	/**
	 * Writes the found uploaded files to a ZIP archive.
	 * Returns false if file could not be opened or written.
	 *
	 * @since 4.0
	 *
	 * @return false|string
	 */
	public function write() {

		$file_path = $this->get_file_path();

		// Initialize ZipArchive.
		$this->zip_archive = new ZipArchive();

		if ( $this->zip_archive->open( $file_path, ZipArchive::CREATE ) !== true ) {
			fg_entryautomation()->log_error( __METHOD__ . '(): Unable to open ZIP file for task #' . $this->task->id );
			return false;
		}

		// Add export file to ZipArchive.
		$this->zip_archive->addFile( $this->action->file_path, sprintf( '%s/%s', $this->get_root_folder_name(), basename( $this->action->file_path ) ) );

		// Loop through entries.
		$this->add_entries();

		// Save Zip Archive.
		$saved = $this->zip_archive->close();

		if ( ! $saved ) {
			fg_entryautomation()->log_error( __METHOD__ . '(): Unable to write ZIP file for task #' . $this->task->id );
			return false;
		}

		// Delete the original export file.
		unlink( $this->action->file_path );

		return $file_path;

	}

	/**
	 * Loops through all entries matching search criteria and adds their files to the ZIP archive.
	 *
	 * @since 4.0
	 */
	private function add_entries() {

		// Get entry type.
		$entry_type = rgar( $this->task->meta, 'entryType' );

		// Prepare search criteria.
		$search_criteria = $this->task->get_search_criteria();

		// Prepare paging criteria.
		$paging = Entries::$paging;

		// Get sorting.
		$sorting = Export::get_sorting( $this->task, $this->action->form );

		// Get total entry count.
		$args          = [
			'form_id'         => $this->action->form['id'],
			'search_criteria' => $search_criteria,
		];
		$found_entries = $this->entries->get_total_count( $args, $entry_type );

		// Loop until all entries have been processed.
		$entries_processed = 0;
		while ( $entries_processed < $found_entries ) {

			// Get entries with sorting and paging criteria.
			$args['sorting'] = $sorting;
			$args['paging']  = $paging;
			$entries         = $this->entries->get( $args, $entry_type );

			// If no more entries were found, break.
			if ( empty( $entries ) ) {
				break;
			}

			// Loop through entries.
			foreach ( $entries as $entry ) {

				$this->add_entry_files( $entry );

				// Increase entries processed count.
				$entries_processed++;

			}

			// Increase offset.
			$paging['offset'] += $paging['page_size'];

		}

	}

	/**
	 * Adds files for a specific Entry to the ZIP archive.
	 *
	 * @since 4.0
	 *
	 * @param array $entry Entry object.
	 */
	private function add_entry_files( $entry ) {

		// Get ZIP archive folder path for entry.
		$entry_folder = sprintf(
			'%s/%s',
			$this->get_root_folder_name(),
			$this->get_entry_folder_name( $entry )
		);

		$entry_files = [];

		// Loop through each selected field and add found files.
		foreach ( $this->get_file_upload_field_ids() as $field_id ) {

			$files = $this->get_files_for_field( $entry, $field_id );

			if ( empty( $files ) ) {
				continue;
			}

			foreach ( $files as $file ) {
				if ( $this->zip_archive->addFile( $file, sprintf( '%s/%s', $entry_folder, basename( $file ) ) ) ) {
					$entry_files[] = basename( $file );
				}
			}

		}

		// Add Fillable PDFs files.
		if ( function_exists( 'fg_fillablepdfs' ) && ( $feed_ids = $this->get_feeds_ids( 'forgravity-fillablepdfs' ) ) ) {

			// Get PDFs for entry.
			$entry_pdfs = fg_fillablepdfs()->get_entry_pdfs( $entry );

			// Loop through found PDFs and add for enabled feeds.
			foreach ( $entry_pdfs as $entry_pdf ) {

				if ( ! in_array( (int) $entry_pdf['feed_id'], $feed_ids, true ) ) {
					continue;
				}

				$pdf_file_path = fg_fillablepdfs()->get_physical_file_path( $entry_pdf );

				if ( is_readable( $pdf_file_path ) && $this->zip_archive->addFile( $pdf_file_path, sprintf( '%s/%s', $entry_folder, $entry_pdf['file_name'] ) ) ) {
					$entry_files[] = $entry_pdf['file_name'];
				}

			}

		}

		if ( ! empty( $entry_files ) ) {
			fg_entryautomation()->log_debug( __METHOD__ . '(): Files added for entry #' . $entry['id'] . ': ' . print_r( $entry_files, true ) );
		}

	}

	/**
	 * Returns the root ZIP folder name.
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	private function get_root_folder_name() {

		return rtrim( $this->get_file_name(), '.zip' );

	}

	/**
	 * Returns the file name for the ZIP file.
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	private function get_file_name() {

		if ( $this->file_name ) {
			return $this->file_name;
		}

		// If no File Uploads specific file name was provided, use Export file name.
		if ( ! rgars( $this->task->meta, 'exportFiles/name' ) ) {

			$file_name = basename( $this->action->file_path );

		} else {

			// Replace merge tags in file name.
			$entry     = $this->task->entry_id ? GFAPI::get_entry( $this->task->entry_id ) : [];
			$file_name = $this->task->merge_tags->replace_tags( $this->task->meta['exportFiles']['name'], $entry, false, false, false, 'text' );

		}

		// Get file name extension.
		$ext = pathinfo( $file_name, PATHINFO_EXTENSION );

		// Remove extension from file name.
		if ( rgblank( $ext ) ) {
			$file_name = rtrim( $file_name, '.' );
		} else {
			$file_name = rtrim( $file_name, '.' . $ext );
		}

		// Get full file path.
		$export_folder    = trailingslashit( $this->get_export_folder() );
		$target_file_path = $export_folder . $file_name . '.zip';

		// Set base file name.
		$this->file_name = $file_name . '.zip';

		// If overwriting the file and a file already exists, delete existing file.
		if ( 'overwrite' === rgar( $this->task->meta, 'exportWriteType' ) && file_exists( $target_file_path ) ) {

			// Delete file.
			wp_delete_file( $target_file_path );

		} elseif ( ( 'new' === rgar( $this->task->meta, 'exportWriteType' ) || ! rgar( $this->task->meta, 'exportWriteType' ) ) && file_exists( $target_file_path ) ) {

			// Define starting duplicate file name counter.
			$counter          = 1;
			$target_file_path = $export_folder . $file_name . '-' . $counter . '.' . $ext;

			// If file name exists, iterate until it does not.
			while ( file_exists( $target_file_path ) ) {
				$target_file_path = $export_folder . $file_name . '-' . $counter . '.' . $ext;
				$counter++;
			}

			$this->file_name = sprintf( '%s-%d.zip', $file_name, $counter );

		}

		return $this->file_name;

	}

	/**
	 * Returns the full path to the ZIP file.
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	private function get_file_path() {

		return trailingslashit( $this->get_export_folder() ) . $this->get_file_name();

	}

	/**
	 * Returns the folder name for a specific entry.
	 *
	 * @since 4.0
	 *
	 * @param array $entry Entry object.
	 *
	 * @return string
	 */
	private function get_entry_folder_name( $entry ) {

		$folder_name = $this->task->meta['exportFiles']['folder'];

		return $this->task->merge_tags->replace_tags( $folder_name, $entry, false, false, false, 'text' );

	}

	/**
	 * Returns the root Export folder for the current Task.
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	private function get_export_folder() {

		return Export::get_export_folder( $this->task, $this->action->form );

	}

	/**
	 * Returns files uploaded to a specific field in an entry.
	 *
	 * @since 4.0
	 *
	 * @param array $entry    Entry object.
	 * @param int   $field_id Field to return files for.
	 *
	 * @return array
	 */
	private function get_files_for_field( $entry, $field_id ) {

		$field = GFAPI::get_field( $this->action->form, $field_id );

		// If provided field ID does not correspond to a File Upload or Signature field, return.
		if ( ! $field || ( ! is_a( $field, 'GF_Field_FileUpload' ) && ! is_a( $field, 'GF_Field_Signature' ) ) ) {
			return [];
		}

		$field_value = rgar( $entry, $field_id, false );

		// If no files were uploaded for this field, return.
		if ( ! $field_value ) {
			return [];
		}

		if ( $field->type === 'signature' ) {

			$files = [ trailingslashit( GFSignature::get_signatures_folder() ) . $field_value ];

		} else {

			// Collect uploaded files as an array based on if multiple files are allowed.
			if ( $field->multipleFiles ) {
				$files = json_decode( $field_value, true );
			} else {
				$files = [ $field_value ];
			}

			// Convert the file URLs to their physical location.
			$files = array_map( [ 'GFFormsModel', 'get_physical_file_path' ], $files );

		}

		// Filter out files that cannot be read.
		return array_filter( $files, 'is_readable' );

	}

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
		$feeds = rgars( $this->task->meta, 'exportFiles/feeds/' . $addon );

		// Filter out feeds that are disabled.
		$feeds = array_filter( $feeds, function( $enabled ) { return $enabled; } );

		// Return only the feed IDs.
		return array_map( 'intval', array_keys( $feeds ) );

	}

	/**
	 * Returns the selected File Upload field IDs.
	 *
	 * @since 4.0
	 *
	 * @return int[]
	 */
	private function get_file_upload_field_ids() {

		// Get selected File Upload fields Task meta.
		$fields = $this->task->meta['exportFiles']['fields'];

		// Filter out fields that are disabled.
		$fields = array_filter( $fields, function( $enabled ) { return $enabled; } );

		// Return only the field IDs.
		return array_keys( $fields );

	}





	// # ACTION SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Returns the Task Settings fields for configuring Upload Files.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public static function get_settings_fields() {

		$form                   = fg_entryautomation()->get_current_form();
		$export_fields_settings = self::get_export_fields_settings_field( $form );
		$fillablepdfs_settings  = self::get_fillablepdfs_settings_fields( $form );

		if ( empty( $export_fields_settings ) && empty( $fillablepdfs_settings ) ) {
			return [ 'fields' => [] ];
		}

		$field_dependency = [
			'live'   => true,
			'fields' => [
				[
					'field'  => 'exportWriteType',
					'values' => [ 'new', 'overwrite' ],
				],
				[
					'field' => 'exportFiles[enabled]',
				],
			],
		];

		$section = [
			'id'         => 'export-files',
			'title'      => esc_html__( 'Export Files', 'forgravity_entryautomation' ),
			'fields'     => [
				[
					'name'       => 'exportFiles[warning]',
					'type'       => 'html',
					'dependency' => [
						'live'   => true,
						'fields' => [
							[
								'field'  => 'exportWriteType',
								'values' => [ 'add' ],
							],
						],
					],
					'html'       => sprintf(
						'<div class="alert warning" role="alert">%s</div>',
						esc_html__( 'Files cannot be included with Tasks that add entries to an existing file.', 'forgravity_entryautomation' )
					),
				],
				[
					'name'       => 'exportFiles[enabled]',
					'type'       => 'toggle',
					'label'      => esc_html__( 'Include Files With Export File', 'forgravity_entryautomation' ),
					'dependency' => [
						'live'   => true,
						'fields' => [
							[
								'field'  => 'exportWriteType',
								'values' => [ 'new', 'overwrite' ],
							],
						],
					],
				],
				[
					'name'          => 'exportFiles[folder]',
					'type'          => 'text',
					'required'      => true,
					'dependency'    => $field_dependency,
					'class'         => 'merge-tag-support mt-position-right mt-hide_all_fields',
					'label'         => esc_html__( 'Entry Folder Name', 'forgravity_entryautomation' ),
					'description'   => esc_html__( "Define the name used to contain each entry's files.", 'forgravity_entryautomation' ),
					'default_value' => '{entry_id}',
				],
				[
					'name'        => 'exportFiles[name]',
					'type'        => 'text',
					'dependency'  => $field_dependency,
					'label'       => esc_html__( 'Archive File Name', 'forgravity_entryautomation' ),
					'description' => esc_html__( 'If left empty, the Export File Name will be used.', 'forgravity_entryautomation' ),
					'class'       => 'merge-tag-support mt-position-right mt-hide_all_fields',
				],
			],
		];

		if ( $export_fields_settings ) {
			$export_fields_settings['required'] = empty( $fillablepdfs_settings );
			$section                            = fg_entryautomation()->add_field_after( 'exportFiles[enabled]', $export_fields_settings, [ $section ] )[0];
		}

		if ( $fillablepdfs_settings ) {
			$section = fg_entryautomation()->add_field_before( 'exportFiles[folder]', $fillablepdfs_settings, [ $section ] )[0];
		}

		if ( class_exists( 'ZipArchive' ) ) {
			return $section;
		}

		unset( $section['fields'][1], $section['fields'][2], $section['fields'][3] );

		$section['fields'][0]['disabled']    = true;
		$section['fields'][0]['description'] = esc_html__( 'The Zip PHP extension is required to access this feature.', 'forgravity_entryautomation' );

		return $section;

	}

	/**
	 * Returns the Select Fields setting for the Export Files section.
	 *
	 * @since 4.0
	 *
	 * @param array $form The Form object.
	 *
	 * @return array
	 */
	private static function get_export_fields_settings_field( $form ) {

		$file_upload_choices = [];
		$file_upload_fields  = GFAPI::get_fields_by_type( $form, [ 'fileupload', 'signature', 'image_hopper' ] );

		if ( ! $file_upload_fields ) {
			return [];
		}

		foreach ( $file_upload_fields as $file_upload_field ) {
			$file_upload_choices[] = [
				'name'  => sprintf( 'exportFiles[fields][%d]', esc_attr( $file_upload_field->id ) ),
				'label' => GFCommon::get_label( $file_upload_field ),
			];
		}

		return [
			'name'        => 'exportFiles[fields]',
			'type'        => 'checkbox',
			'dependency'  => [
				'live'   => true,
				'fields' => [
					[
						'field'  => 'exportWriteType',
						'values' => [ 'new', 'overwrite' ],
					],
					[
						'field' => 'exportFiles[enabled]',
					],
				],
			],
			'label'       => esc_html__( 'Select Fields', 'forgravity_entryautomation' ),
			'description' => esc_html__( 'Select which File Upload or Signature fields will be included with the export file.', 'forgravity_entryautomation' ),
			'choices'     => $file_upload_choices,
		];

	}

	/**
	 * Returns the Export Files setting if Fillable PDFs is activated with available feeds.
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
				'name'  => sprintf( 'exportFiles[feeds][%s][%d]', esc_attr( fg_fillablepdfs()->get_slug() ), $feed['id'] ),
				'label' => esc_html( rgars( $feed, 'meta/feedName' ) ),
			];
		}

		return [
			'name'        => sprintf( 'exportFiles[feeds][%s]', esc_attr( fg_fillablepdfs()->get_slug() ) ),
			'type'        => 'checkbox',
			'dependency' => [
				'live'   => true,
				'fields' => [
					[
						'field' => 'exportFiles[enabled]',
					],
					[
						'field'  => 'exportWriteType',
						'values' => [ 'new', 'overwrite' ],
					],
				],
			],
			'label'       => esc_html__( 'Select Fillable PDFs Feeds', 'forgravity_entryautomation' ),
			'choices'     => $feed_choices,
		];

	}

}
