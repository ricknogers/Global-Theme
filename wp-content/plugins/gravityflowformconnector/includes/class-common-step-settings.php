<?php
/**
 * Gravity Flow Form Connector Common Step Settings Functions
 *
 * @since       2.1
 * @copyright   Copyright (c) 2015-2020, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @package     GravityFlow
 */

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

/**
 * Class Gravity_Flow_Form_Connector_Common_Step_Settings
 *
 * @since 2.1
 */
class Gravity_Flow_Form_Connector_Common_Step_Settings {

	/**
	 * The current step
	 *
	 * @since 2.1
	 *
	 * @var Gravity_Flow_Step_New_Entry
	 */
	private $_step;

	/**
	 * Gravity_Flow_Form_Connector_Common_Step_Settings constructor.
	 *
	 * @since 2.1
	 */
	public function __construct( $step ) {
		$this->_step = $step;
	}

	/**
	 * Returns the common server fields.
	 *
	 * @since 2.1
	 *
	 * @return array[]
	 */
	public function get_server_fields() {
		$v1_dependency = $this->fields_dependency( array(
			'fields' => array(
				array( 'field' => 'server_type', 'values' => array( 'remote' ) ),
				array( 'field' => 'api_version', 'values' => array( '1' ) ),
			),
		) );

		return array(
			array(
				'name'          => 'server_type',
				'label'         => esc_html__( 'Site', 'gravityflowformconnector' ),
				'type'          => 'radio',
				'default_value' => 'local',
				'horizontal'    => true,
				'onchange'      => 'jQuery(this).closest("form").submit();',
				'choices'       => array(
					array( 'label' => esc_html__( 'This site', 'gravityflowformconnector' ), 'value' => 'local' ),
					array(
						'label' => esc_html__( 'A different site', 'gravityflowformconnector' ),
						'value' => 'remote',
					),
				),
			),
			array(
				'name'          => 'api_version',
				'label'         => esc_html__( 'REST API', 'gravityflowformconnector' ),
				'type'          => 'radio',
				'default_value' => empty( $_GET['fid'] ) ? '2' : '1',
				'horizontal'    => true,
				'onchange'      => 'jQuery(this).closest("form").submit();',
				'choices'       => array(
					array(
						'label' => esc_html__( 'Version 1', 'gravityflowformconnector' ),
						'value' => '1',
					),
					array(
						'label' => esc_html__( 'Version 2', 'gravityflowformconnector' ),
						'value' => '2',
					),
				),
				'dependency'    => array(
					'field'  => 'server_type',
					'values' => array( 'remote' ),
				),
			),
			array(
				'name'       => 'remote_site_url',
				'label'      => esc_html__( 'Site Url', 'gravityflowformconnector' ),
				'type'       => 'text',
				'dependency' => $v1_dependency,
				'onchange'   => 'jQuery(this).closest("form").submit();',
			),
			array(
				'name'       => 'remote_public_key',
				'label'      => esc_html__( 'Public Key', 'gravityflowformconnector' ),
				'type'       => 'text',
				'dependency' => $v1_dependency,
				'onchange'   => 'jQuery(this).closest("form").submit();',
			),
			array(
				'name'       => 'remote_private_key',
				'label'      => esc_html__( 'Private Key', 'gravityflowformconnector' ),
				'type'       => 'text',
				'dependency' => $v1_dependency,
				'onchange'   => 'jQuery(this).closest("form").submit();',
			),
			array(
				'name'       => 'connected_app',
				'label'      => esc_html__( 'Connected App', 'gravityflowformconnector' ),
				'type'       => 'select',
				'tooltip'    => esc_html__( 'Manage your Connected Apps in the Workflow->Settings->Connected Apps page. ', 'gravityflowformconnector' ),
				'onchange'   => 'jQuery(this).closest("form").submit();',
				'dependency' => $this->fields_dependency( array(
					'fields' => array(
						array( 'field' => 'server_type', 'values' => array( 'remote' ) ),
						array( 'field' => 'api_version', 'values' => array( '2' ) ),
					),
				) ),
				'choices'    => $this->get_connected_app_choices(),
			),
		);
	}

	/**
	 * Returns an array of choices for the connected_app setting.
	 *
	 * @since 2.1
	 *
	 * @return array[]
	 */
	private function get_connected_app_choices() {
		$connected_apps = gravityflow_connected_apps()->get_connected_apps();
		$choices        = array(
			array(
				'label' => esc_html__( 'Select a Connected App', 'gravityflowformconnector' ),
				'value' => '',
			),
		);

		foreach ( $connected_apps as $app ) {
			$choices[] = array(
				'label' => $app['app_name'],
				'value' => $app['app_id'],
			);
		}

		return $choices;
	}

	/**
	 * Returns the entry lookup field.
	 *
	 * @since 2.1
	 *
	 * @param array|string $dependency The field dependency.
	 *
	 * @return array
	 */
	public function get_lookup_method_field( $dependency ) {
		return array(
			'name'          => 'lookup_method',
			'label'         => esc_html__( 'Entry Lookup', 'gravityflowformconnector' ),
			'type'          => 'radio',
			'default_value' => 'select_entry_id_field',
			'horizontal'    => true,
			'onchange'      => 'jQuery(this).closest("form").submit();',
			'choices'       => array(
				array(
					'label' => esc_html__( 'Conditional Logic', 'gravityflowformconnector' ),
					'value' => 'filter',
				),
				array(
					'label' => esc_html__( 'Select a field containing the source entry ID.', 'gravityflowformconnector' ),
					'value' => 'select_entry_id_field',
				),
			),
			'dependency'    => $this->fields_dependency( $dependency ),
		);
	}

	/**
	 * Returns the Lookup Conditional Logic field.
	 *
	 * @since 2.1
	 *
	 * @param int|string   $form_id    The ID of the selected form.
	 * @param array|string $dependency The field dependency.
	 *
	 * @return array
	 */
	public function get_entry_filter_field( $form_id, $dependency = array() ) {
		$is_remote = $this->_step->get_setting( 'server_type' ) === 'remote';

		$dependency['fields'][] = array(
			'field'  => 'lookup_method',
			'values' => array( 'filter' ),
		);
		$dependency             = $this->fields_dependency( $dependency );

		if ( $is_remote ) {
			if ( ! $this->gravity_flow_supports_filter_settings() ) {
				return $this->get_unsupported_entry_filter_field( $dependency, esc_html__( 'This setting requires Gravity Flow 2.7.1 or greater.', 'gravityflowformconnector' ) );
			} elseif ( empty( $form_id ) || ! $this->_step->is_api_v2() ) {
				return $this->get_unsupported_entry_filter_field( $dependency, esc_html__( 'To use this setting with a remote site you must select REST API Version 2 and the remote site must be running Gravity Forms 2.4.22 or greater.', 'gravityflowformconnector' ) );
			}

			$filters = $this->get_remote_field_filters( $form_id );
			if ( empty( $filters ) ) {
				return $this->get_unsupported_entry_filter_field( $dependency, esc_html__( 'To use this setting the remote site must be running Gravity Forms 2.4.22 or greater.', 'gravityflowformconnector' ) );
			}
		}

		$field = array(
			'name'                 => 'entry_filter',
			'show_sorting_options' => true,
			'label'                => esc_html__( 'Lookup Conditional Logic', 'gravityflowformconnector' ),
			'type'                 => 'entry_filter',
			'filter_text'          => esc_html__( 'Look up the first entry matching {0} of the following criteria:', 'gravityflowformconnector' ),
			'dependency'           => $dependency,
		);

		if ( $is_remote ) {
			$field['filter_settings'] = $filters;
		} else {
			$field['form_id'] = $form_id;
		}

		return $field;
	}

	/**
	 * Returns the Lookup Conditional Logic field when not supported.
	 *
	 * @since 2.1
	 *
	 * @param array|string $dependency The field dependency.
	 * @param string       $message    The error message tp be displayed.
	 *
	 * @return array
	 */
	private function get_unsupported_entry_filter_field( $dependency, $message ) {
		$html = sprintf( '<div class="delete-alert alert_yellow"><i class="fa fa-exclamation-triangle gf_invalid"></i> %s</div>', $message );

		// Use a hidden input to retain existing value.
		$html .= gravity_flow()->settings_hidden( array(
			'name' => 'entry_filter',
			'type' => 'hidden',
		), false );

		return array(
			'name'       => 'entry_filter',
			'label'      => esc_html__( 'Lookup Conditional Logic', 'gravityflowformconnector' ),
			'type'       => 'html',
			'dependency' => $dependency,
			'html'       => $html,
		);
	}

	/**
	 * Determines if the installed Gravity Flow version supports using remote field filters.
	 *
	 * @since 2.1
	 *
	 * @return bool|int
	 */
	private function gravity_flow_supports_filter_settings() {
		return version_compare( GRAVITY_FLOW_VERSION, '2.7.2', '>=' );
	}

	/**
	 * Returns the field filter settings for a remote form.
	 *
	 * @since 2.1
	 *
	 * @param int|string $form_id The ID of the selected form.
	 *
	 * @return false|array
	 */
	private function get_remote_field_filters( $form_id ) {
		return $this->_step->remote_request_v2( "forms/{$form_id}/field-filters", 'GET', null, array( '_admin_labels' => 1 ) );
	}

	/**
	 * Returns a GF 2.5 settings compatible dependency array or evaluates the dependency to return one of the WP boolean callbacks for older versions.
	 *
	 * @since 2.1
	 *
	 * @param array $dependency The field dependency.
	 *
	 * @return array|string
	 */
	public function fields_dependency( $dependency ) {
		if ( ! gravity_flow()->is_gravityforms_supported( '2.5-beta-1' ) ) {
			foreach ( $dependency['fields'] as $field ) {
				if ( ! gravity_flow()->setting_dependency_met( $field ) ) {
					return '__return_false';
				}
			}

			return '__return_true';
		}

		return $dependency;
	}

}
