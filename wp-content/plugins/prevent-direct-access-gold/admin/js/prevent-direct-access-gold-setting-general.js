(function ($) {
  'use strict'
  var ajax_url = prevent_direct_access_gold_setting_data.ajax_url;
  var home_url = prevent_direct_access_gold_setting_data.home_url;

  $(document).ready(function () {
    if ($('.pda-v3-gold-tooltip')) {
      if ($('.pda-v3-gold-tooltip').tooltip) {
        $('.pda-v3-gold-tooltip').tooltip({
          position: {
            my: "left bottom-10",
            at: "left top",
          }
        });
      }
    }
  });

  $(function () {
    handleForNoAccessPage();
    $('#pda_prefix_url').keyup(function (evt) {
      if (!_validatePrivateLinkPrefix()) {
        $('.pda-error-prefix-private-link').show();
        return;
      }
      $('.pda-error-prefix-private-link').hide();
      $('#pda_prefix').text(this.value.trim());
    });

    $(".pda_select2").select2({
      width: '100%',
    });

    $(".pda_select2_for_role_protection").select2({
      width: '80%',
    });

    $("#pda_whitelist_role_non_expired_select2").select2();

    if ($("#file_access_permission").val() == 'custom_roles') {
      $('#grant-access').attr('required', true);
      $('#grant-access').show();
      $('#pda_role_select2').prop('required', true);
    } else {
      $('#grant-access').attr('required', false);
      $('#grant-access').hide();
      $('#pda_role_select2').prop('required', false);
    }

    $("#file_access_permission").change(function () {
      if ($("#file_access_permission").val() == 'custom_roles') {
        $('#grant-access').attr('required', true);
        $('#grant-access').show();
        $('#pda_role_select2').prop('required', true);
      } else {
        $('#grant-access').attr('required', false);
        $('#grant-access').hide();
        $('#pda_role_select2').prop('required', false);
      }
    });

    const toggleGrantAccessRoles = function () {
      if ($("#pda_roles_auto_protect_new_file").prop("checked")) {
        $('#pda-grant-access-roles').show();
        $('#pda_auto_protect_new_file_select2').attr('required', 'required');
      } else {
        $('#pda-grant-access-roles').hide();
        $('#pda_auto_protect_new_file_select2').removeAttr('required');
      }
    };
    toggleGrantAccessRoles();
    $("#pda_roles_auto_protect_new_file").change(function () {
      toggleGrantAccessRoles();
    });

    if ($("#pda_auto_protect_new_file").prop("checked")) {
      $('#grant-access-protect-file').show();
    } else {
      $('#grant-access-protect-file').hide();
    }
    $("#pda_auto_protect_new_file").change(function () {
      if ($("#pda_auto_protect_new_file").prop("checked")) {
        $('#grant-access-protect-file').show();
      } else {
        $('#grant-access-protect-file').hide();
        $('#pda_auto_protect_new_file_select2').removeAttr('required');
        $('#pda_roles_auto_protect_new_file').attr('checked', false);
        $('#pda-grant-access-roles').hide();
      }
    });

    $("#pda_auto_replace_protected_file").change(function () {
      if ($(this).prop('checked')) {
        $("#pda-pages-posts-replace").show();
        $("#pda_replaced_pages_select2").prop('required', true);
      } else {
        $("#pda-pages-posts-replace").hide();
        $("#pda_replaced_pages_select2").prop('required', false);
      }
    });

    _handleSwitchButton('#pda_enable_wc', '#pda-web-crawler', '#pda-selected-wc');

    _handle_fully_activate();

    $('#pda_setting_form').keypress(function (event) {
      if (event.which == '13') {
        return false;
      }
    });

	$("#remote_log").change(function() {
	  if ($(this).prop('checked') == true) {
		$('#pda-force-htaccess').show();
	  } else {
		$('#pda-force-htaccess').hide();
	  }
	});

    $('#pda_setting_form').submit(function (evt) {
      evt.preventDefault();
      if (!_validateBeforeSubmit()) {
        return;
      }

      var replaced = $("#pda_prefix_url").val().trim().replace(/  +/g, '-');
      var result = replaced.split(' ').join('-');

      if ($("#remote_log").prop('checked') == true) {
        $('#helpers').show();
      } else {
        $('#helpers').hide();
      }

      var customLink = $('#pda-input-custom-link').val();
      if ($('#pda-no-access-page').val() === 'page-404' || $('#pda-no-access-page').val() === 'search-page-post') {
        if (!validateCustomLink($('#pda-input-custom-link').val())) {
          customLink = '';
        }
      }

      _updateSettingsGeneral({
        remote_log: $("#remote_log").prop('checked'),
        pda_prefix_url: $("#pda_prefix_url").val().trim().length === 0 ? "private" : result,
        pda_auto_protect_new_file: $("#pda_auto_protect_new_file").prop('checked'),
        pda_gold_enable_image_hot_linking: $("#pda_gold_enable_image_hot_linking").prop('checked'),
        pda_gold_enable_directory_listing: $("#pda_gold_enable_directory_listing").prop('checked'),
        pda_prevent_access_license: $("#pda_prevent_access_license").prop('checked'),
        pda_prevent_access_version: $("#pda_prevent_access_version").prop('checked'),
        whitelist_roles: $("#pda_role_select2").val(),
        whitelist_user_groups: $("#pda_uam_user_group_select2").val(),
        whitelist_roles_auto_protect: $("#pda_auto_protect_new_file").prop("checked") && $("#pda_roles_auto_protect_new_file").prop("checked") ? $("#pda_auto_protect_new_file_select2").val() : null,
        file_access_permission: $("#file_access_permission").val(),
        remove_license_and_all_data: $("#remove_license_and_all_data").prop('checked'),
        use_redirect_urls: $("#use_redirect_urls").prop('checked'),
        pda_auto_create_new_private_link: $("#pda_auto_create_new_private_link").prop('checked'),
        pda_auto_replace_protected_file: $("#pda_auto_replace_protected_file").prop('checked'),
        pda_replaced_pages_posts: $('#pda_replaced_pages_select2').val(),
        force_download: $('#pda_force_download').prop('checked'),
		force_pda_htaccess: $('#force_pda_htaccess').prop('checked'),
		pda_gold_enable_auto_activate_new_site: $('#pda_gold_enable_auto_activate_new_site').prop('checked'),
        pda_role_protection: $('#pda_role_protection').val(),
        pda_gold_no_access_page: $('#pda-no-access-page').val(),
        pda_nap_custom_link: customLink,
        pda_nap_existing_page_post: $('#pda-result-existing-page-post').val(),
        pda_prevent_access_enable_wc: $('#pda_enable_wc').prop('checked'),
        pda_prevent_access_selected_wc: $('#pda-selected-wc').val(),
      }, function (error) {
        if (error) {
          console.error(error);
        }
        if ($("#pda_prefix_url").val().trim().length === 0) {
          $("#pda_prefix_url").val("private");
        }
      });
    });

    $(".custom-aws-bucket-input").each(function (key) {
      $(this).change(function () {
        $('.btn-test').show('slow');
        $('#submit').hide('slow');
      });
    });

    $('#is_aws_default').change(function (evt) {
      evt.preventDefault();
      if (this.checked) {
        $('.custom-aws-bucket-input').prop('required', false);
        $('.custom-aws-bucket').hide('slow');
        $('.btn-test').hide('slow');
        $('#submit').show('slow');
      } else {
        $('.custom-aws-bucket-input').prop('required', true);
        $('.custom-aws-bucket').show('slow');
        $('.btn-test').show('slow');
        $('#submit').hide('slow');
      }
    });

    _handleMigration();

    $('#activate-all-sites').click(function () {
      let infoSiteActivated = $('#info-site-activated');
      if (infoSiteActivated.hasClass('pda-display-none')) {
        infoSiteActivated.removeClass('pda-display-none');
      }
      let activateAllSite = $("#activate-all-sites");
      activateAllSite.attr("type", "hidden");
      $('#span-activate').append('<div id="license-loading" class="lds-ring"><div></div><div></div><div></div><div></div></div>');

      function getActivated() {
        $.ajax({
          url: ajax_url,
          type: 'GET',
          data: {
            action: 'pda_gold_activated_statistics',
          },
          success: function (data) {
            if (data.hasOwnProperty('status')) {
              siteActivated.text(data.num);
              if (!data.status) {
                $('#license-loading').remove();
                activateAllSite.attr("type", "submit");
                clearInterval(myInterval);
                toastr.success('License activated successfully on existing sites', 'Prevent Direct Access Gold 3.0')
              }
            }
          },
          error: function (error) {
            console.log("Errors", error);
            $('#license-loading').remove();
            activateAllSite.attr("type", "submit");
            clearInterval(myInterval);
            toastr.error('Fail to activate PDA Gold license on existing sites. Please try again.', 'Prevent Direct Access Gold 3.0')
          },
          timeout: 1000
        });
      }

      let siteActivated = $('#site-activated');
      let myInterval = setInterval(getActivated, 1000);

      const _data = {
        action: 'pda_gold_activate_all_sites',
        security_check: $("#prevent-direct-access-gold_nonce").val(),
      };
      $.ajax({
        url: ajax_url,
        type: 'POST',
        data: _data,
        success: function (data) {
          if (data === 'invalid_nonce') {
            alert('No! No! No! Verify Nonce Fails!');
          }
        },
        error: function (error) {
          console.log("Errors", error);
        },
        timeout: 5000
      });
    });

    $('#btn-recheck-license').click(function () {
      var _data = {
        action: 'pda_gold_recheck_license',
        security_check: $("#nonce_pda_v3").val(),
      };
      var recheckLicenseButton = $(this);
      recheckLicenseButton.text('(Refreshing...)');
      $.ajax({
        url: ajax_url,
        type: 'POST',
        data: _data,
        success: function (data) {
          if (data.success) {
            toastr.success(data.message, 'Prevent Direct Access Gold 3.0');
            return;
          }
          if (data.message) {
            toastr.error(data.message, 'Prevent Direct Access Gold 3.0');
            return;
          }
          toastr.error('Opps! Please try again or contact the plugin owner.', 'Prevent Direct Access Gold 3.0');
        },
        error: function (error) {
          console.log('Error: ', error);
          toastr.error('Opps! Please try again or contact the plugin owner.', 'Prevent Direct Access Gold 3.0');
        },
        complete: function () {
          recheckLicenseButton.text('(Refresh license)');
          location.reload();
        }
      });
    });

  });

  function _updateSettingsGeneral(settings, cb) {
    var _data = {
      action: 'pda_gold_update_general_settings',
      settings: settings,
      security_check: $("#nonce_pda_v3").val(),
    };
    $('#summit').val('Submiting');
    $("#submit").prop("disabled", true);
    $.ajax({
      url: ajax_url,
      type: 'POST',
      data: _data,
      success: function (data) {
        $("#submit").prop("disabled", false);
        // Do something with the result from server
        if (data == 'invalid_nonce') {
          alert('No! No! No! Verify Nonce Fails!');
        } else if (data['is_error']) {
          $('#pda-error-nap-custom-link').show();
        } else {
          if (data['raw_url_error']) {
            var message = data['message'];
            $('.pda_raw_url_error').html(message);
            $('.pda_raw_url_error').show();
            var status = message.split(':')[0];
            $('.pda_button_error_raw_url').html(status);
            $('.pda_button_error_raw_url').show();
          } else {
            $('.pda_raw_url_error').hide();
            $('.pda_button_error_raw_url').hide();
          }
          toastr.success('Your settings have been updated successfully!', 'Prevent Direct Access Gold 3.0')
        }
        cb();
      },

      error: function (error) {
        $("#submit").prop("disabled", false);
        console.log("Errors", error);
        cb(error);
      },
      timeout: 5000
    });
  }

  function _handleMigration() {
    $("#migration-form").submit(function (evt) {
      evt.preventDefault();
      var _data = {
        action: 'pda_gold_migrate_data',
        security_check: $("#nonce_pda_v3").val(),
      };
      $("#migration-progress").show('slow');
      $("#migration-progress").removeClass('hide');
      $("#migration-progress-bar").addClass("running");
      $("#submit").val("Migrating...");
      $("#submit").attr("disabled", "disabled");
      $.ajax({
        xhr: function () {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", function (evt) {
            if (evt.lengthComputable) {
              var percentComplete = evt.loaded / evt.total;
              console.log("Percent", percentComplete);
              $('#migration-progress-bar').css({
                width: percentComplete * 100 + "%"
              });
              $('#migration-progress-bar').text(percentComplete * 100 + "% " + "Complete");
            }
          }, false);
          xhr.addEventListener("progress", function (evt) {
            if (evt.lengthComputable) {
              var percentComplete = evt.loaded / evt.total;
              $('#migration-progress-bar').css({
                width: percentComplete * 100 + "%"
              });
              $('#migration-progress-bar').text(percentComplete * 100 + "% " + "Complete");
            }
          }, false);
          return xhr;
        },
        type: 'POST',
        data: _data,
        url: ajax_url,
        success: function (data) {
          $("#submit").val("Data migrated successfully");
          setTimeout(function () {
            location.reload();
          }, 2000);
        }
      })
    });
  }

  function _handle_fully_activate() {
    $("#enable_pda_v3_form").submit(function (evt) {
      evt.preventDefault();
      _ajax_handle_fully_activate(true);
    });

    $("#enable_pda_v3_form_no_reload").submit(function (evt) {
      evt.preventDefault();
      _ajax_handle_fully_activate(false);
    });

    $("#enable_pda_v3_raw_url").submit(function (evt) {
      evt.preventDefault();
      _ajax_handle_enable_raw_url(true);
    })
  }

  function _ajax_handle_fully_activate(isReload) {
    var _data = {
      action: 'pda_gold_check_htaccess',
      security_check: $("#nonce_pda_v3").val(),
    };
    $("#enable_pda_v3").attr("disabled", "disabled");
    $.ajax({
      type: 'POST',
      data: _data,
      url: ajax_url,
      success: function (data) {
        var toastTitle = 'Prevent Direct Access Gold 3.0';
        var rule_names = ['hl', 'ip_white', 'ip_black', 'rm'];
        for (var i = 0; i < rule_names.length; i++) {
          if (data.rules_checking[rule_names[i]] !== undefined && !data.rules_checking[rule_names[i]]) {
            $("#enable_pda_v3").attr("disabled", false)
            toastr.error('Opps! Please try to update your .htaccess again with the above instruction', toastTitle);
            return;
          }
        }

        if (true === data.status) {
          if (isReload) {
            $("#enable_pda_v3").attr("disabled", "disabled");
          } else {
            $("#enable_pda_v3").attr("disabled", false);
          }
          toastr.success(data.message, toastTitle);
          if (isReload) {
            location.reload();
          }
        } else {
          $("#enable_pda_v3").attr("disabled", false)
          toastr.error(data.message, toastTitle)
        }
      }
    });
  }

  function _ajax_handle_enable_raw_url(isReload) {
    var _data = {
      action: 'pda_gold_enable_raw_url',
      security_check: $("#nonce_pda_v3").val(),
    };
    $("#enable_raw_url").attr("disabled", "disabled");
    $.ajax({
      type: 'POST',
      data: _data,
      url: ajax_url,
      success: function (data) {
        if (data) {
          if (!isReload) {
            $("#enable_raw_url").attr("disabled", false);
          }
          toastr.success('Great! Success to fully activated the plugin.', 'Prevent Direct Access Gold 3.0');
          if (isReload) {
            location.reload();
          }
        } else {
          $("#enable_raw_url").attr("disabled", false)
          toastr.error('Opps! Please try again or contact the plugin owner.', 'Prevent Direct Access Gold 3.0')
        }
      }
    })
  }

  function validateCustomLink(link) {
    const validate = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.([a-zA-Z]{1,63}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+){1,256})*$/;
    return validate.test(link);
  }

  function handleForNoAccessPage() {
    $('#pda-no-access-page').change(function () {
      if ($(this).val() === 'page-404') {
        $('.pda-wrap-no-access-content').hide();
        $('#pda-error-nap-existing-page-post').hide();
        $('#pda-error-nap-custom-link').hide();
      } else if ($(this).val() === 'custom-link') {
        $('.pda-wrap-custom-link').show();
        $('.pda-wrap-search-page-post').hide();
        $('#pda-error-nap-existing-page-post').hide();

        var link = $('#pda-input-custom-link').val();
        if (link) {
          _validateCustomLink(link);
        }

        $('#pda-input-custom-link').change(function () {
          var link = $('#pda-input-custom-link').val();
          _validateCustomLink(link);
        });

        $('#pda-input-custom-link').keyup(function () {
          if ($(this).val()) {
            $('#pda-clear-custom-link').show();
          } else {
            $('#pda-clear-custom-link').hide();
          }
        })
        $('#pda-input-custom-link').trigger('keyup');


      } else if ($(this).val() === 'search-page-post') {
        $('.pda-wrap-search-page-post').show();
        $('.pda-wrap-custom-link').hide();
        $('#pda-input-custom-link').prop('required', false);
        $('#pda-error-nap-custom-link').hide();
      }
    });
    $('#pda-no-access-page').trigger('change');

    $('#pda-clear-custom-link').click(function () {
      $('#pda-input-custom-link').val('');
      $(this).hide();
      $('#pda-error-nap-custom-link').show();
    });

    $('#pda-clear-search').click(function () {
      $('#pda-search-no-access-page').val('');
      $('#pda-result-existing-page-post').val('');
      $(this).hide();
    });

  }

  function _validateCustomLink(link) {
    if (!validateCustomLink(link)) {
      $('#pda-error-nap-custom-link').show();
    } else {
      $('#pda-error-nap-custom-link').hide();
    }
  }

  function _validatePrivateLinkPrefix() {
    const prefixUrl = $("#pda_prefix_url").val().trim();
    if (prefixUrl.length > 255) {
      return false;
    }
    const uriPattern = /(^[A-Za-z0-9-_]+$)/g;
    return uriPattern.test(prefixUrl);
  }

  function _validateBeforeSubmit() {
    var result = true;
    if (!_validatePrivateLinkPrefix()) {
      $('.pda-error-prefix-private-link').show();
      $('#pda_prefix_url').focus();
      document.getElementById('pda-private-download-link').scrollIntoView();
      result = false;
    }

    if ($('#pda-no-access-page').val() === 'custom-link') {
      var customLink = $('#pda-input-custom-link').val();
      if (!validateCustomLink(customLink)) {
        $('#pda-error-nap-custom-link').show();
        $('#ppda-input-custom-link').focus();
        document.getElementById('pda-file-protection').scrollIntoView();
        result = false;
      }
    }

    if ($('#pda-no-access-page').val() === 'search-page-post') {
      var titleAndLinkNAP = $("#pda-result-existing-page-post").val();
      if (titleAndLinkNAP === '') {
        $('#pda-error-nap-existing-page-post').show();
        $('#pda-search-no-access-page').focus();
        document.getElementById('pda-file-protection').scrollIntoView();
        result = false;
      }
    }
    return result;
  }

  function _handleSwitchButton(switchBtn, panel, insideElement) {
    $(switchBtn).change(function () {
      if ($(this).prop('checked')) {
        $(panel).show();
        $(insideElement).prop('required', true);
      } else {
        $(panel).hide();
        $(insideElement).prop('required', false);
      }
    });
  }
})(jQuery);
