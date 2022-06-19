<?php

/**
 * Gravity Flow Update Field Values Step
 *
 *
 * @package     GravityFlow
 * @subpackage  Classes/Step
 * @copyright   Copyright (c) 2015-2018, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 * @since       1.4.3
 */

if ( class_exists( 'Gravity_Flow_Step' ) ) {

	class Gravity_Flow_Step_Update_Field_Values extends Gravity_Flow_Step_Update_Entry {
		public $_step_type = 'update_field_values';

		public function get_label() {
			return esc_html__( 'Update Fields', 'gravityflowformconnector' );
		}

		/**
		 * Returns the array of settings for this step.
		 *
		 * @return array
		 */
		public function get_settings() {

			$form_choices = array(
				array(
					'label' => esc_html__( 'Select a Form', 'gravityflowformconnector' ),
					'value' => '',
				),
			);

			$forms    = $this->get_forms();
			$form_ids = array();

			foreach ( $forms as $form ) {
				$form_choices[] = array(
					'label' => $form->title,
					'value' => $form->id,
				);
				$form_ids[]     = $form->id;
			}

			$action_choices = $this->action_choices();

			$common_settings = new Gravity_Flow_Form_Connector_Common_Step_Settings( $this );
			$form_id         = $this->get_setting( 'source_form_id' );

			$dependency = array(
				'fields' => array(
					array( 'field' => 'action', 'values' => array( 'update' ) ),
					array( 'field' => 'source_form_id', 'values' => $form_ids ),
				),
			);

			$settings = array(
				'title'  => esc_html__( 'Update Field Values', 'gravityflow' ),
				'fields' => array_merge( $common_settings->get_server_fields(), array(
						array(
							'name'     => 'source_form_id',
							'label'    => esc_html__( 'Source Form', 'gravityflowformconnector' ),
							'type'     => 'select',
							'onchange' => "jQuery('#action').val('update');jQuery(this).closest('form').submit();",
							'choices'  => $form_choices,
						),
						array(
							'name'          => 'action',
							'label'         => esc_html__( 'Action', 'gravityflowformconnector' ),
							'type'          => count( $action_choices ) == 1 ? 'hidden' : 'select',
							'default_value' => 'update',
							'horizontal'    => true,
							'onchange'      => "jQuery(this).closest('form').submit();",
							'choices'       => $action_choices,
						),
						$common_settings->get_lookup_method_field( $dependency ),
						$common_settings->get_entry_filter_field( $form_id ),
					)
				),
			);

			$lookup_setting = $this->get_setting( 'lookup_method' );

			if ( empty( $lookup_setting ) || $lookup_setting == 'select_entry_id_field' ) {
				$entry_id_field = array(
					'name'       => 'source_entry_id',
					'label'      => esc_html__( 'Entry ID Field', 'gravityflowformconnector' ),
					'type'       => 'field_select',
					'tooltip'    => __( 'Select the field which will contain the entry ID of the entry that values will be copied from.', 'gravityflowformconnector' ),
					'required'   => true,
					'dependency' => $common_settings->fields_dependency( $dependency ),
				);

				if ( function_exists( 'gravity_flow_parent_child' ) ) {
					$parent_form_choices = array();
					$entry_meta          = gravity_flow_parent_child()->get_entry_meta( array(), rgget( 'id' ) );

					foreach ( $entry_meta as $meta_key => $meta ) {
						$parent_form_choices[] = array(
							'value' => $meta_key,
							'label' => $meta['label'],
						);
					}

					if ( ! empty( $parent_form_choices ) ) {
						$entry_id_field['args']['append_choices'] = $parent_form_choices;
					}
				}

				if ( $this->get_setting( 'source_form_id' ) == $this->get_form_id() ) {
					$self_entry_id_choice = array( array( 'label' => esc_html__( 'Entry ID (Self)', 'gravityflowformconnector' ), 'value' => 'id' ) );
					if ( ! isset( $entry_id_field['args']['append_choices'] ) ) {
						$entry_id_field['args']['append_choices'] = array();
					}
					$entry_id_field['args']['append_choices'] = array_merge( $entry_id_field['args']['append_choices'], $self_entry_id_choice );
				}

				$settings['fields'][] = $entry_id_field;
			}

			$mapping_field = array(
				'name'                => 'mappings',
				'label'               => esc_html__( 'Field Mapping', 'gravityflowformconnector' ),
				'type'                => 'generic_map',
				'key_field'      => array(
					'choices'      => $this->field_mappings(),
					'custom_value' => false,
					'title'        => esc_html__( 'Field', 'gravityflowformconnector' ),
				),
				'value_field'    => array(
					'choices'      => $this->value_mappings(),
					'custom_value' => true,
					'title'        => esc_html__( 'Value', 'gravityflowformconnector' ),
				),
				'tooltip'             => '<h6>' . esc_html__( 'Mapping', 'gravityflowformconnector' ) . '</h6>' . esc_html__( 'Map the fields of the selected form to this form. Values from the selected entry will be saved in the entry in this form.', 'gravityflowformconnector' ),
				'dependency'          => $common_settings->fields_dependency( $dependency ),
			);

			$settings['fields'][] = $mapping_field;

			return $settings;
		}

		/**
		 * Returns the array of choices for the action setting.
		 *
		 * @return array
		 */
		public function action_choices() {
			$choices = array(
				array(
					'label' => esc_html__( 'Update Field Values', 'gravityflow' ),
					'value' => 'update',
				),
			);

			return $choices;
		}

		/**
		 * Prepare value map.
		 *
		 * @return array
		 */
		public function value_mappings() {

			$source_form_id = $this->get_setting( 'source_form_id' );

			if ( empty( $source_form_id ) ) {
				return false;
			}

			$source_form = $this->get_target_form( $source_form_id );

			if ( empty( $source_form ) ) {
				return false;
			}

			$fields = $this->get_field_map_choices( $source_form );
			
			return array_values( $fields );
		}

		/**
		 * Prepare field map.
		 *
		 * @return array
		 */
		public function field_mappings() {

			$form = $this->get_form();

			$fields = $this->get_field_map_choices( $form );
			$fields = array_filter( $fields, function( $field ) {
				return $field['value'] != 'id';
			});

			$fields = array_values( $fields );
			foreach( $fields as &$field ) {
				$field['name'] = (string) $field['value'];
			}
                  
			return $fields;
		}

		/**
		 * @param $form
		 * @param $entry
		 *
		 * @return array $new_entry
		 */
		public function do_mapping( $form, $entry ) {
			$new_entry = array();

			if ( ! is_array( $this->mappings ) ) {

				return $new_entry;
			}

			$target_form = $this->get_form();

			foreach ( $this->mappings as $mapping ) {
				if ( rgblank( $mapping['key'] ) ) {
					continue;
				}

				$new_entry = $this->add_mapping_to_entry( $mapping, $entry, $new_entry, $form, $target_form );
			}

			return apply_filters( 'gravityflowformconnector_' . $this->get_type(), $new_entry, $entry, $form, $target_form, $this );
		}

		public function process_local_action() {

			$entry = $this->get_entry();

			$source_form_id = $this->source_form_id;

			$form = $this->get_form();

			$source_form = GFAPI::get_form( $source_form_id );

			if ( empty( $source_form ) ) {
				return true;
			}

			$source_entry_id = rgar( $entry, $this->source_entry_id );

			$source_entry = $this->get_local_entry( $source_entry_id, $source_form_id, $entry, $form );

			if ( is_wp_error( $source_entry ) || $source_entry == false ) {
				return true;
			}

			$new_entry = $this->do_mapping( $this->filter_form( $source_form, $source_entry ), $source_entry );

			foreach ( $new_entry as $key => $value ) {
				$entry[ (string) $key ] = $value;
			}
			GFAPI::update_entry( $entry );

			return true;
		}

		/**
		 * Updates a remote entry.
		 *
		 *
		 * @return bool Has the step finished?
		 */
		public function process_remote_action() {
			$entry = $this->get_entry();

			$form = $this->get_form();

			$source_form_id = $this->source_form_id;

			$source_form = $this->get_target_form( $source_form_id );

			if ( empty( $source_form ) ) {
				return true;
			}

			$source_entry_id = rgar( $entry, $this->source_entry_id );

			$source_entry = $this->get_remote_entry( $source_entry_id, $source_form_id, $entry, $form );

			if ( is_wp_error( $source_entry ) || $source_entry == false ) {
				return true;
			}

			$new_entry = $this->do_mapping( $source_form, $source_entry );

			foreach ( $new_entry as $key => $value ) {
				$entry[ (string) $key ] = $value;
			}
			GFAPI::update_entry( $entry );

			return true;
		}
	}
}

Gravity_Flow_Steps::register( new Gravity_Flow_Step_Update_Field_Values() );
