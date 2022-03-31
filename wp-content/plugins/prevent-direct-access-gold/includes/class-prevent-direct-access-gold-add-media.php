<?php

if ( ! class_exists( 'PDA_Add_Media' ) ) {
	/**
	 * Render UI checkbox protect/unprotect file o popup add media
	 * Class PDA_Add_Media
	 */
	class PDA_Add_Media {

		/**
		 * PDA Services Class.
		 *
		 * @var stdClass PDA_Services PDA Services Class.
		 */
		private $services;

		/**
		 * PDA_Add_Media constructor.
		 */
		public function __construct() {
			$this->services = new PDA_Services();
		}

		/**
		 * Add checkbox to popup add media
		 *
		 * @param string $post post.
		 *
		 * @return string
		 */
		public function pda_add_media_render_ui( $post ) {
			$repo              = new PDA_v3_Gold_Repository();
			$is_protected      = $repo->is_protected_file( $post->ID );
			$checked           = $is_protected ? "checked='checked'" : '';
			$ip_block_disabled = - 1 === Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) ? '' : 'disabled';

			$data['post_id'] = $post->ID;
			if ( - 1 === Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) ) {
				$admin      = new Wp_Pda_Ip_Block_Admin( '', '' );
				$data_roles = $admin->get_user_roles_ip_block( $data );
			} else {
				$data_roles = $this->services->get_fap( $post->ID );
			}

			$type_select              = $data_roles['type'];
			$file_access_permission   = $this->pda_select_option_fap( $type_select, $ip_block_disabled, $is_protected, $post );
			$choose_custom_role       = $this->pda_select_option_choose_custom_role( $data_roles, $is_protected, $type_select, $post );
			$memberships_input_hidden = "<input value='' type='hidden' id='input_pda_fap_choose_custom_roles' name='attachments[{$post->ID}][pda_fap_choose_custom_roles]'/>";
			$get_url_by_id            = $this->pda_get_url_by_id( $post );
			$pda_js_handle_fap        = $this->pda_render_js_for_add_media_on_popup( $post, $type_select, $data_roles['roles'] );

			return "
				<div class='pda_wrap_protection_setting'>
					<input type='hidden' value='pda_protection_setting_hidden' name='attachments[{$post->ID}][pda_protection_setting_hidden]'>
					<input class='pda_protection_setting' type='checkbox' {$checked}
					       name='attachments[{$post->ID}][pda_protection_setting]'
					       id='attachments{$post->ID}pda_protection_setting'/>
					<label for='attachments{$post->ID}pda_protection_setting'>" . __( 'Protect this file', 'prevent-direct-access-gold' ) . "</label>
				</div>
				<div id='pda_wrap_select2_$post->ID' class='pda-wrap-select2'>
					<div class='pda-wrap-loading'>
						<div class='pda-loading'></div>
					</div>
					$file_access_permission
					$choose_custom_role
				</div>
				$memberships_input_hidden
				<script type='text/javascript'>
	                (function( $ ) {
	                    $get_url_by_id
	                    $pda_js_handle_fap
	                })( jQuery );
				</script>
			";
		}

		/**
		 * Render select input
		 *
		 * @param string $type_select       is role selected.
		 * @param string $ip_block_disabled status ip block plugin.
		 * @param bool   $is_protected      status file.
		 * @param string $post              post global.
		 *
		 * @return string
		 */
		public function pda_select_option_fap( $type_select, $ip_block_disabled, $is_protected, $post ) {
			$options      = array(
				'default',
				'admin-user',
				'author',
				'logger-in-user',
				'blank',
				'anyone',
				'custom-roles',
				'custom-roles-membership' // Virtual data
			);
			$option_value = '';
			foreach ( $options as $option ) {
				$is_selected = $option === $type_select ? true : false;
				$selected    = $is_selected ? 'selected' : '';
				switch ( $option ) {
					case 'default':
						$label        = __( 'Use default setting', 'prevent-direct-access-gold' );
						$option_value .= "<option $selected value='$option'>$label</option>";
						break;
					case 'admin-user':
						$label        = __( 'Admin users', 'prevent-direct-access-gold' );
						$option_value .= "<option $selected value='$option'>$label</option>";
						break;
					case 'author':
						$label        = __( 'The file\'s author', 'prevent-direct-access-gold' );
						$option_value .= "<option $selected value='$option'>$label</option>";
						break;
					case 'logger-in-user':
						$label        = __( 'Logged-in users', 'prevent-direct-access-gold' );
						$option_value .= "<option $selected value='$option'>$label</option>";
						break;
					case 'blank':
						$label        = __( 'No user roles', 'prevent-direct-access-gold' );
						$option_value .= "<option $selected value='$option'>$label</option>";
						break;
					case 'anyone':
						$label        = __( 'Anyone', 'prevent-direct-access-gold' );
						$option_value .= "<option $selected value='$option'>$label</option>";
						break;
					case 'custom-roles':
						$label        = __( 'Choose custom roles', 'prevent-direct-access-gold' );
						$option_value .= "<option $ip_block_disabled $selected value='$option'>$label</option>";
						break;
					case 'custom-roles-membership':
						$label        = __( 'Choose custom memberships', 'prevent-direct-access-gold' );
						$option_value .= "<option disabled $selected value='$option'>$label</option>";
						break;
				}
			}

			$display                = $is_protected ? '' : 'display: none';
			$file_access_permission = "
                <div class='pda_attachment_fap' style='$display'>
                    <p class='pda_wrap_fap'>" . __( 'File Access Permission', 'prevent-direct-access-gold' ) . "</p>
                    <select class='pda_file_access_permission' id='attachments[{$post->ID}][pda_file_access_permission]' name='attachments[{$post->ID}][pda_file_access_permission]'>
                    $option_value
                    </select>
                </div>";

			return $file_access_permission;
		}

		/**
		 * Render option custom role.
		 *
		 * @param string $data_roles   data.
		 * @param bool   $is_protected status file.
		 * @param string $type_select  is s role selected.
		 * @param object $post         data.
		 *
		 * @return string
		 */
		public function pda_select_option_choose_custom_role( $data_roles, $is_protected, $type_select, $post ) {
			$user_roles   = array_keys( get_editable_roles() );
			$roles_select = explode( ';', $data_roles['roles'] );
			$role_options = array_map( function ( $role ) use ( $roles_select ) {
				$selected = in_array( $role, $roles_select ) ? 'selected' : '';

				return "<option $selected value='$role'>$role</option>";
			}, $user_roles );

			$role_options           = implode( '', $role_options );
			$class_custom_role_hide = 'custom-roles' !== $type_select ? 'pda_fap_wrap_hide' : '';
			$choose_custom_role     = "
	            <div class='pda_fap_wrap_choose_custom_roles $class_custom_role_hide'>
	                <select id='pda_fap_choose_custom_roles' multiple name='attachments[{$post->ID}][pda_fap_select_custom_roles]'>
	                    $role_options
	                </select>
	            </div>
            ";

			return $choose_custom_role;
		}

		/**
		 * Get URL
		 *
		 * @param object $post data.
		 *
		 * @return string
		 */
		public function pda_get_url_by_id( $post ) {
			$rest_nonce    = wp_create_nonce( 'wp_rest' );
			$rest_url      = rtrim( get_rest_url(), '/' );
			$get_url_by_id = ( "
				function refreshUrl(cb, selectedId) {
					let settings = {
					  'async': true,
					  'url': '{$rest_url}/pda/v3/files/' + selectedId,
					  'method': 'GET',
					  'headers': {
					    'X-WP-Nonce': '{$rest_nonce}',
					    'cache-control': 'no-cache',
					  }
					}
					pda_loading_attachment();
					$.ajax(settings).done(function (response) {
					  const { post } = response;
					  const labelSetting = $('.setting[data-setting=url] input[readonly]');
					  if(labelSetting) {
					  	const currentUrl = labelSetting.val();
					  	// Get URL file name and remove query string.
					  	const currentFileName = currentUrl.substring(currentUrl.lastIndexOf('/') + 1 ).split('?')[0];
					    const postFileName = post.edit_url.substring(post.edit_url.lastIndexOf('/') + 1 ).split('?')[0];
					    if(currentFileName === postFileName) {
					        labelSetting.val(post.edit_url);
					    }
					  }
					  hide_loading_attachment();
					  cb(response);
					});
				}
				_.extend( wp.media.view.Attachment.prototype, {
					updateSave: function( status ) {
					// Do not need to update URL when changing the images
					if (!this.model.changed.status && this.model.changed.compat) {
						const selectedId = this.model.id;
						console.log('Updating', selectedId);
						hide_loading_attachment();
						handleUpdateUrl(function(res) {
			                const id = res.post.id;
			                if(res.is_protected) {
                               show_fap_element(id);
                               pda_show_red_border(id);
			                } else {
                               hide_fap_element(id);
                               hide_red_border(id);
			                }
			            }, selectedId);
					}
			            return this;
					}
				});

				function handleUpdateUrl(cb, selectedId) {
					refreshUrl(cb, selectedId);
				}
			" );

			return $get_url_by_id;
		}

		/**
		 * Render js for FAP on popup add media
		 *
		 * @param object $post data.
		 *
		 * @return string
		 */
		public function pda_render_js_for_add_media_on_popup( $post, $type_select, $role_selected ) {
			return "
				var checkBoxProtection = $('#attachments{$post->ID}pda_protection_setting');

				var pda_loading_attachment = function() {
					console.log('Loading');
					$('.pda-wrap-loading').css('height', '100%');
                    $('.pda-wrap-loading').css('width', '100%');
                    $('.pda-wrap-loading').show();
				}

				var hide_loading_attachment = function() {
					console.log('Hiding');
					$('.pda-wrap-loading').hide();
				}

				var show_fap_element = function(id) {
					$('#pda_wrap_select2_' + id).show();
					if ($('.pda_file_access_permission').val() === 'custom-roles' ) {
                        $('.pda_fap_wrap_choose_custom_roles').show();
                    } else {
                        $('input#input_pda_fap_choose_custom_roles').val('');
                        $('.pda_fap_wrap_choose_custom_roles').hide();
                    }
				}

				var hide_fap_element = function(id) {
					$('#pda_wrap_select2_' + id).hide();
				}

				var pda_show_red_border = function(id) {
					$('[data-id=' + id + ']').addClass('pda-protected-grid-view');
                    $('.selection-view').addClass('pda-protected-selection-view');
				}

				var hide_red_border = function(id) {
					$('[data-id=' + id + ']').removeClass('pda-protected-grid-view');
	                $('.selection-view').removeClass('pda-protected-selection-view');
                    $('.pda_fap_wrap_choose_custom_roles').hide();
				}

                if(checkBoxProtection.prop('checked')) {
                    $('[data-id=$post->ID]').addClass('pda-protected-grid-view');
                    $('.selection-view').addClass('pda-protected-selection-view');
                } else {
                    $('[data-id=$post->ID]').removeClass('pda-protected-grid-view');
                    $('.selection-view').removeClass('pda-protected-selection-view');
                    $('.pda_fap_wrap_choose_custom_roles').hide();
                }

                checkBoxProtection.change(function() {
                    pda_loading_attachment();
                    if(checkBoxProtection.prop('checked')) {
                        show_fap_element($post->ID);
                    } else {
                        hide_fap_element($post->ID);
                    }
                });

				$('select#pda_fap_choose_custom_roles').select2({
                    theme: 'default pda-custom-dropdown-select2'
                });

				$('select#pda_fap_choose_custom_roles').change(function() {
    	               $('input#input_pda_fap_choose_custom_roles').val($(this).val());

    	               if( $(this).val() !== null && $('#input_pda_fap_choose_custom_roles').val() !== '{$role_selected}' ) {
		                   pda_loading_attachment();
				       }
                });

                $('.pda_file_access_permission').change(function() {
                    if ($(this).val() === 'custom-roles' ) {
	                    $('select#pda_fap_choose_custom_roles').select2({
		                    theme: 'default pda-custom-dropdown-select2'
		                });
                        $('.pda_fap_wrap_choose_custom_roles').show();
                    } else {
                        if( $(this).val() !== '{$type_select}' ) {
		                    pda_loading_attachment();
	                    }
	                    $('select#pda_fap_choose_custom_roles').val('');
                        $('.pda_fap_wrap_choose_custom_roles').hide();
                    }
                });
			";
		}
	}
}


