<?php

namespace ForGravity\Entry_Automation\Action\Export\Writer;

use ForGravity\Entry_Automation\Action\Export\Writer;
use ForGravity\Entry_Automation\Task;

use Exception;
use GFCommon;
use Mpdf\Mpdf;

/**
 * Export entries to PDF file.
 *
 * @since 3.0
 */
class PDF extends Writer {

	/**
	 * Get header for CSV export file.
	 *
	 * @since  3.0
	 */
	protected function build_header() {

		$form = $this->form;
		$task = $this->action->task;

		// Require needed classes.
		if ( ! class_exists( 'GFExport' ) ) {
			require_once GFCommon::get_base_path() . '/export.php';
		}
		if ( ! class_exists( 'GFEntryDetail' ) ) {
			require_once GFCommon::get_base_path() . '/entry_detail.php';
		}

		// Begin output buffering.
		ob_end_clean();
		if ( function_exists( 'ob_gzhandler' ) ) {
			ob_start( 'ob_gzhandler' );
		} else {
			ob_start();
		}

		/**
		 * Change the template file used for the PDF header.
		 *
		 * @since 2.0
		 *
		 * @param string $template_header Path to default PDF header template file.
		 * @param Task   $task            Entry Automation Task object.
		 * @param array  $form            The Form object.
		 */
		$template_header = apply_filters( 'fg_entryautomation_pdf_template_header', $this->get_component_file_path( 'header' ), $task, $form );

		// Display PDF header.
		if ( file_exists( $template_header ) ) {
			include $template_header;
		}

	}

	/**
	 * Build the formatted entry.
	 *
	 * @since 3.0
	 *
	 * @param string $built_entry       The CSV entry.
	 * @param array  $entry             The original entry data.
	 * @param int    $entries_processed The entries has been processed.
	 */
	protected function build_entry( &$built_entry, $entry, $entries_processed ) {

		$form   = $this->form;
		$task   = $this->action->task;
		$fields = $this->fields;

		/**
		 * Change the template file used for the PDF entry.
		 *
		 * @since 2.0
		 *
		 * @param string $template_entry Path to default PDF entry template file.
		 * @param Task   $task           Entry Automation Task object.
		 * @param array  $form           The Form object.
		 */
		$template_entry = apply_filters( 'fg_entryautomation_pdf_template_entry', $this->get_component_file_path( 'entry' ), $task, $form );

		// Display entry.
		if ( file_exists( $template_entry ) ) {
			include $template_entry;
		}

	}

	/**
	 * Build the footer for the export file.
	 *
	 * @since 3.0
	 */
	protected function build_footer() {

		$form = $this->form;
		$task = $this->action->task;

		/**
		 * Change the template file used for the PDF footer.
		 *
		 * @since 2.0
		 *
		 * @param string $template_footer Path to default PDF footer template file.
		 * @param Task   $task            Entry Automation Task object.
		 * @param array  $form            The Form object.
		 */
		$template_footer = apply_filters( 'fg_entryautomation_pdf_template_footer', $this->get_component_file_path( 'footer' ), $task, $form );

		// Display PDF footer.
		if ( file_exists( $template_footer ) ) {
			include $template_footer;
		}

		// Get PDF contents.
		$this->file_content = ob_get_clean();

	}

	/**
	 * Write entries to PDF file.
	 *
	 * @since 3.0
	 *
	 * @param string $file_path  Path to export file.
	 * @param string $write_type Export write type.
	 *
	 * @return bool|int
	 */
	protected function write_to_file( $file_path, $write_type = 'new' ) {

		// If appending or prepending to a new file, change write type.
		if ( ! file_exists( $file_path ) ) {
			$write_type = 'new';
		}

		// Include mPDF.
		if ( ! class_exists( '\Mpdf\Mpdf' ) ) {
			require_once fg_entryautomation()->get_includes_path() . '/vendor/autoload.php';
		}

		/**
		 * Modify the configuration used for mPDF.
		 *
		 * @since 3.3
		 *
		 * @param array $config mPDF configuration.
		 * @param Task  $task   Entry Automation Task object.
		 * @param array $form   The Form object.
		 */
		$config = apply_filters( 'fg_entryautomation_pdf_mpdf_config', [ 'mode' => 'c' ], $this->action->task, $this->form );

		switch ( $write_type ) {

			case 'append':

				try {

					// Initialize mPDF.
					$mpdf = new Mpdf( $config );

					// Import existing file.
					$page_count = $mpdf->setSourceFile( $file_path );

					// Loop through pages in source file, write them.
					for ( $i = 1; $i <= $page_count; $i++ ) {

						// Get template ID.
						$template_id = $mpdf->ImportPage( $i );
						$mpdf->UseTemplate( $template_id );

						// Add page break.
						if ( $i !== $page_count ) {
							$mpdf->AddPage();
						}

					}

					// Write HTML.
					$mpdf->AddPage();
					$mpdf->WriteHTML( $this->file_content, \Mpdf\HTMLParserMode::DEFAULT_MODE, false );

					// Save file.
					$mpdf->Output( $file_path );

				} catch ( Exception $e ) {

					fg_entryautomation()->log_debug( __METHOD__ . '(): PDF cannot be generated; ' . $e->getMessage() );

					return false;

				}

				return true;


			case 'prepend':

				try {

					// Initialize mPDF.
					$mpdf = new Mpdf( $config );

					// Write HTML.
					$mpdf->WriteHTML( $this->file_content, \Mpdf\HTMLParserMode::DEFAULT_MODE, false );
					$mpdf->AddPage();

					// Import existing file.
					$page_count = $mpdf->setSourceFile( $file_path );

					// Loop through pages in source file, write them.
					for ( $i = 1; $i <= $page_count; $i++ ) {

						// Get template ID.
						$template_id = $mpdf->ImportPage( $i );
						$mpdf->UseTemplate( $template_id );

						// Add page break.
						if ( $i !== $page_count ) {
							$mpdf->AddPage();
						}

					}

					// Save file.
					$mpdf->Output( $file_path );

				} catch ( Exception $e ) {

					fg_entryautomation()->log_debug( __METHOD__ . '(): PDF cannot be generated; ' . $e->getMessage() );

					return false;

				}

				return true;

			default:

				try {

					// Initialize mPDF.
					$mpdf = new Mpdf( $config );

					// Write HTML.
					$mpdf->WriteHTML( $this->file_content );

					// Save PDF to file path.
					$mpdf->Output( $file_path );

				} catch ( Exception $e ) {

					fg_entryautomation()->log_debug( __METHOD__ . '(): PDF cannot be generated; ' . $e->getMessage() );

					return false;

				}

				return true;

		}

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Returns the file path to a template component.
	 * Searches stylesheet directory, template directory and Entry Automation core.
	 *
	 * @since 3.3
	 *
	 * @param string $component Template component. ("header", "footer", or "entry")
	 *
	 * @return string|null
	 */
	private function get_component_file_path( $component ) {

		// Verify provided component is valid.
		if ( ! in_array( $component, [ 'header', 'footer', 'entry' ] ) ) {
			return null;
		}

		// Folders to search for the template file in.
		$root_paths = [
			trailingslashit( get_stylesheet_directory() ) . 'forgravity-entryautomation',
			trailingslashit( get_template_directory() ) . 'forgravity-entryautomation',
			fg_entryautomation()->get_includes_path(),
		];

		// Template file names to search for.
		$file_names = [
			sprintf( 'templates/export-pdf-%s-%d-%d.php', $component, intval( $this->form['id'] ), intval( $this->action->task->id ) ),
			sprintf( 'templates/export-pdf-%s-%d.php', $component, intval( $this->form['id'] ) ),
			sprintf( 'templates/export-pdf-%s.php', $component ),
		];

		foreach ( $file_names as $file_name ) {

			foreach ( $root_paths as $root_path ) {

				$file_path = sprintf( '%s/%s', untrailingslashit( $root_path ), $file_name );

				if ( file_exists( $file_path ) ) {
					return $file_path;
				}

			}

		}

		return null;

	}

}
