(function ($) {
  $(function () {
	$('a.pda-gold-crypto-btn').click(function (evt) {
		evt.preventDefault();
		var postID = $(this).data('id');
		var type = $(this).data('type');

		if (type === 'encrypt') {

		  $(this).text('Encrypting...');
		  encryptFile(postID, this);
		} else {

		  $(this).text('Decrypting...');
		  decryptFile(postID, this);
		}
	  }
	);

	function encryptFile(postID, btn) {
	  var data = {
		action: 'pda_gold_encrypt_file',
		security_check: pda_gold.nonce,
		post_id: postID,
	  }

	  $.ajax({
		url: pda_gold.ajax_url,
		type: 'POST',
		data: data,
		success: function (data) {
		  if (data && data.success) {
			// $status = $('a#pda-gold-status-' + postID);
			// $status.show();
			$(btn).data('type', 'decrypt');
			$(btn).text('Decrypt');

			alert(data.message);
		  }
		},
		error: function (jqXHR) {
		  if (jqXHR.responseText) {
			data = JSON.parse(jqXHR.responseText);
			alert(data.message);
		  } else {
			alert('Oops. Something went wrong. Please try again later.')
		  }

		  $(btn).text('Encrypt');
		}
	  });
	}

	function decryptFile(postID, btn) {
	  var data = {
		action: 'pda_gold_decrypt_file',
		security_check: pda_gold.nonce,
		post_id: postID,
	  }

	  $.ajax({
		url: pda_gold.ajax_url,
		type: 'POST',
		data: data,
		success: function (data) {
		  if (data && data.success) {
			$status = $('a#pda-gold-status-' + postID);
			$status.hide();
			$(btn).data('type', 'encrypt');
			$(btn).text('Encrypt');

			alert(data.message);
		  }
		},
		error: function (jqXHR) {
		  if (jqXHR.responseText) {
			data = JSON.parse(jqXHR.responseText);
			alert(data.message);
		  } else {
			alert('Oops. Something went wrong. Please try again later.')
		  }

		  $(btn).text('Decrypt');
		}
	  });
	}

  });
})(jQuery);
