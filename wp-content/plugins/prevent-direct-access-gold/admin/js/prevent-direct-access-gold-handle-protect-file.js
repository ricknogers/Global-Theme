(function ($) {
  'use strict';
  const {nonce, api_url} = pda_gold_v3_data;
  const rootDomain = api_url.replace(/\/$/, '');
  const baseUrl = `${rootDomain}/pda/v3`;

  $(function () {
    $(".pda-protect-file").click(function (evt) {
      evt.preventDefault();
      const postId = evt.target.id.split('_')[1];
      pdaProtectFile(postId);
    });
  });

  function pdaProtectFile(postId) {
    const textProtect = changeBtnText(postId, true);
    if (textProtect.toLocaleLowerCase() === 'protect') {
      callProtectFile(postId, textProtect);
    } else {
      callUnProtectFile(postId, textProtect);
    }
  }

  function changeBtnText(postId, loading) {
    var $pdaProtectFile = $("#pda-protect-file_" + postId);
    $pdaProtectFile.css("pointer-events", "none");
    var textProtect = $pdaProtectFile.text();
    var message = loading ? textProtect + 'ing...' : textProtect;
    $pdaProtectFile.text(message);
    return textProtect;
  }


  function callProtectFile(postId, textProtect) {
    const protectFileUrl = `${baseUrl}/files/${postId}`;
    const $pdaProtectFile = $("#pda-protect-file_" + postId);

    $.ajax({
      url: protectFileUrl,
      type: 'POST',
      timeout: 10000,
      headers: {
        'X-WP-Nonce': nonce
      }
    }).done((data, status, response) => {
      if (response.status === 200) {
        toastr.success('Great! You\'ve successfully protected this file.', 'Prevent Direct Access Gold')
        $pdaProtectFile.text("Unprotect");
        $('#pda-v3-text_' + postId).remove();
        $('#pda-v3-wrap-status_' + postId).prepend('<span id="pda-v3-text_' + postId + '" class="protection-status " title="This file is protected"><i class="fa fa-check-circle" aria-hidden="true"></i> protected</span>');
      } else {
        toastr.error(data.message, 'Prevent Direct Access Gold');
        $pdaProtectFile.text('Protect');
      }
    }).error(error => {
      _handleError(error, 'protect');
      $pdaProtectFile.text('Protect');
    }).complete(function() {
      $pdaProtectFile.css("pointer-events", "auto");
    });
  }

  function callUnProtectFile(postId) {
    const unPortectFileUrl = `${baseUrl}/un-protect-files/${postId}`;
    const $pdaProtectFile = $("#pda-protect-file_" + postId);
    $.ajax({
      url: unPortectFileUrl,
      type: 'POST',
      timeout: 10000,
      headers: {
        'X-WP-Nonce': nonce
      }
    }).done((data, status, response) => {
      if (response.status === 200) {
        toastr.success('Great! You\'ve successfully unprotected this file.', 'Prevent Direct Access Gold')
        $pdaProtectFile.text("Protect");
        $('#pda-v3-text_' + postId).remove();
        $('#pda-v3-wrap-status_' + postId).prepend('<span id="pda-v3-text_' + postId + '" class="protection-status pda-unprotected" title="This file is unprotected"><i class="fa fa-times-circle" aria-hidden="true"></i> unprotected</span>');

		var encryptionStatus = $('#pda-v3-encryption_' + postId);
		if (encryptionStatus.length) {
		  encryptionStatus.hide();
		}
	  } else {
        toastr.error(data.message, 'Prevent Direct Access Gold');
        $pdaProtectFile.text(textProtect);
      }
    }).error(error => {
      _handleError(error, 'unprotect');
      $pdaProtectFile.text('Unprotect');
    }).complete(function() {
      $pdaProtectFile.css("pointer-events", "auto");
    })
    ;
  }

  function _handleError(error, action) {
    const message = error.responseJSON && error.responseJSON.message
      ? error.responseJSON.message
      : 'Failed to' + action + ' the file due to ' + error;
    toastr.error(message, 'Prevent Direct Access Gold');
  }


})(jQuery);
