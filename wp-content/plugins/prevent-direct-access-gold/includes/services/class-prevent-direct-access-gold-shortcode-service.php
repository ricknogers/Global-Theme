<?php
if ( class_exists( 'PDA_Shortcode_Service' ) ) {
	return;
}

/**
 *
 * Class PDA_Shortcode_Service
 */
class PDA_Shortcode_Service {
	/**
	 * Shortcode attributes.
	 *
	 * @var array
	 */
	private $attributes;

	/**
	 * Class instance
	 *
	 * @var PDA_Shortcode_Service
	 */
	public static $instance;

	/**
	 * @var string
	 */
	const SHORTCODE_LIST_FILES_NAME = 'pda_list_files';

	/**
	 * PDA_Shortcode_Service constructor.
	 */
	public function __construct() {
		// Check if PDA AR or PDA Membership is register filter to handle shortcode.
		if ( ! has_filter( 'pda_shortcode_get_accessible_files' ) ) {
			return;
		}

		$this->attributes = [
			'orderby'             => 'name',
			'order'               => 'ASC',
			'max_files_per_query' => '10000', // Limit the number of protected files in query.
		];

		add_shortcode( self::SHORTCODE_LIST_FILES_NAME, array( $this, 'render_list_files_shortcode' ) );
	}

	/**
	 * Get service instance.
	 *
	 * @return PDA_Shortcode_Service
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			// Use static instead of self due to the inheritance later.
			// For example: ChildSC extends this class, when we call get_instance
			// it will return the object of child class. On the other hand, self function
			// will return the object of base class.
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Valid order attribute.
	 *
	 * @param array $attrs Attributes.
	 *
	 * @return bool
	 */
	private function valid_order( $attrs ) {
		// Values from WP_Query WordPress.
		$orders = [ 'ASC', 'DESC' ];

		return in_array( $attrs['order'], $orders, true );
	}

	/**
	 * Valid orderby attribute.
	 *
	 * @param array $attrs Attributes.
	 *
	 * @return bool
	 */
	private function valid_orderby( $attrs ) {
		// Values from WP_Query WordPress.
		$orderbys = [ 'none', 'ID', 'author', 'title', 'name', 'date', 'modified', 'rand' ];

		return in_array( $attrs['orderby'], $orderbys, true );
	}

	/**
	 * Valid max_files_per_query attribute.
	 *
	 * @param array $attrs Attributes.
	 *
	 * @return bool
	 */
	private function valid_max_files_per_query( $attrs ) {
		return Pda_v3_Gold_Helper::is_non_negative_integer( $attrs['max_files_per_query'] );
	}

	/**
	 * Valid attributes of shortcode
	 *
	 * @param array $attrs Attributes of shortcode.
	 *
	 * @return bool
	 */
	private function valid_attributes( $attrs ) {
		if ( ! $this->valid_max_files_per_query( $attrs ) ) {
			return false;
		}
		if ( ! $this->valid_order( $attrs ) ) {
			return false;
		}
		if ( ! $this->valid_orderby( $attrs ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Massage attributes
	 *
	 * @param array $attrs List attributes.
	 *
	 * @return array
	 */
	private function massage_attributes( $attrs ) {
		$attrs['order']   = strtoupper( trim( $attrs['order'] ) );
		$attrs['orderby'] = strtolower( trim( $attrs['orderby'] ) );

		// ID must capitalize via WP_Query doc.
		if ( 'id' === $attrs['orderby'] ) {
			$attrs['orderby'] = 'ID';
		}

		$attrs['max_files_per_query'] = (int) $attrs['max_files_per_query'];

		return $attrs;
	}

	/**
	 * Show wrong message.
	 *
	 * @return string
	 */
	public function show_wrong_message() {
		return sprintf( '<p style="color: red">[' . self::SHORTCODE_LIST_FILES_NAME . '] %1$s</p>', __( 'Invalid attributes or values', 'prevent-direct-access-gold' ) );
	}

	/**
	 * Render short code.
	 *
	 * @param array  $attrs   list of attributes.
	 * @param string $content the content inside short code.
	 *
	 * @return string
	 */
	public function render_list_files_shortcode( $attrs, $content = null ) {
		$attrs = shortcode_atts(
			apply_filters( 'pda_shortcode_attributes', $this->attributes ),
			$attrs
		);

		$attrs    = apply_filters( 'pda_shortcode_massage_attributes', $this->massage_attributes( $attrs ), $attrs );
		$is_valid = apply_filters( 'pda_shortcode_valid_attributes', $this->valid_attributes( $attrs ), $attrs );

		if ( ! $is_valid ) {
			return apply_filters( 'pda_shortcode_show_invalid_message', $this->show_wrong_message(), $attrs );
		}

		$protected_files = apply_filters(
			'pda_shortcode_get_protected_files',
			$this->get_protected_files( $attrs ),
			array(
				'attrs' => $attrs
			)
		);
		// This filter is shared to other plugin (PDA AR, PDA Membership, ...) to get accessible files for it.
		$accessible_files = apply_filters(
			'pda_shortcode_get_accessible_files',
			array(),
			array(
				'attrs'           => $attrs,
				'protected_files' => $protected_files
			)
		);

		$output = '<div>' . __( 'No files found', 'prevent-direct-access-gold' ) . '</div>';
		if ( ! empty( $accessible_files ) ) {
			$output = '<ol class="pda-accessible-files">';
			foreach ( $accessible_files as $accessible_file ) {
				$title  = '' === $accessible_file['title'] ? '(no title)' : $accessible_file['title'];
				$output .= '<li id="pda-file-' . $accessible_file['id'] . '">';
				$output .= '<a target="_blank" href="' . $accessible_file['permalink'] . '">' . $title . '</a>';
				$output .= '</li>';
			}
			$output .= '</ol>';
		}

		return apply_filters(
			'pda_shortcode_render_list_files_content',
			$output,
			array(
				'attrs'            => $attrs,
				'protected_files'  => $protected_files,
				'accessible_files' => $accessible_files,
			)
		);
	}

	/**
	 * Get protected files.
	 *
	 * @param array $attrs
	 *
	 * @return array
	 */
	private function get_protected_files( $attrs ) {
		// The meta_key '_pda_protection' with the meta_value 'true'.
		$args = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'order'          => $attrs['order'],
			'no_found_rows'  => true, // This will tell WordPress not to run SQL_CALC_FOUND_ROWS on the SQL query, drastically speeding up your query. SQL_CALC_FOUND_ROWS calculates the total number of rows in your query which is required to know the total amount of “pages” for pagination
			'meta_query'     => array(
				array(
					'key'   => '_pda_protection',
					'value' => '1'
				)
			),
			'posts_per_page' => $attrs['max_files_per_query'],
			'orderby'        => $attrs['orderby'],
		);

		$files_protection = [];
		$the_query        = new WP_Query( $args );

		if ( empty( $the_query->posts ) ) {
			return $files_protection;
		}

		$posts = $the_query->posts;

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$files_protection[] = array(
					'id'        => $post->ID,
					'permalink' => wp_get_attachment_url( $post->ID ),
					'title'     => $post->post_title,
				);
			}
		}

		return $files_protection;
	}

}
