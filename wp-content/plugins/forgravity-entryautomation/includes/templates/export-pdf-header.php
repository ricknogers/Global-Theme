<?php

// Get script/styling extension.
$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php echo esc_html( $form['title'] ) ?></title>
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/print<?php echo $min; ?>.css' type='text/css' />
		<?php

			/**
			 * Determines if the Gravity Forms styles should be printed
			 *
			 * @since 1.7
			 *
			 * @param bool  false Set to true if style should be printed.
			 * @param array $form The Form object
			 */
			$styles = apply_filters( 'gform_print_styles', false, $form );

			// If styles were found, display them.
			if ( ! empty( $styles ) ) {
				wp_print_styles( $styles );
			}
		?>
	</head>
	<body>

		<div id="view-container">
			<form>