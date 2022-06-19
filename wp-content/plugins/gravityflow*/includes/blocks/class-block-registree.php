<?php

namespace Gravity_Flow\Gravity_Flow\Blocks;

/**
 * Block Registree Abstract
 *
 * @since 2.8
 */
abstract class Block_Registree {

	protected $type = '';

	/**
	 * Registers the block with Gutenberg.
	 *
	 * @since 2.8
	 */
	public function register() {
		// Only load if Gutenberg is available.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type( $this->type, array(
			'render_callback' => array( $this, 'render' ),
		) );
	}

	/**
	 * Register the Fields.
	 *
	 * @since 2.8
	 *
	 * @return void
	 */
	abstract public function register_fields();

	/**
	 * Render the block.
	 *
	 * @since 2.8
	 *
	 * @param $attributes
	 * @param $content
	 *
	 * @return string
	 */
	abstract public function render( $attributes, $content );

}