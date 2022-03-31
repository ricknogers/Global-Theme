(function (window, $) {
	$(document).ready(function () {
		$("body").on("click", "#pda_gold_signup_newsletter", function (evt) {
			evt.preventDefault();
			var email = $("#pda_gold_signup_newsletter_input").val().trim();
			var emailPattern = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
			$("#pda_gold_signup_newsletter").val("Saving...");
			if (email && emailPattern.test(email)) {
				// $.ajax({
				// 	url: 'https://api.getresponse.com/v3/contacts',
				// 	type: 'POST',
				// 	headers: {
				// 		"X-Auth-Token": "api-key ae824cfc3df1a2aa18e8a5419ec1c38b",
				// 		'Content-Type' : 'application/json'
				// 	},
				// 	dataType: 'json',
				// 	data: email,
				// 	success: function (data) {
				// 		$(".pda_sub_form").hide();
				// 		$(".newsletter_inform").show("slow");
				// 		console.log("Success", data);
				// 	},
				// 	error: function (error) {
				// 		console.log("Error", error);
				// 	}
				// });

				$.ajax({
					url: newsletter_data.newsletter_url,
					type: 'POST',
					data: {
						action: 'pda_gold_subscribe',
						security_check: newsletter_data.newsletter_nonce,
						email: email
					},
					success: function (data) {
						$(".pda_sub_form").hide();
						$(".newsletter_inform").show("slow");
						console.log("Success", data);
						$("#pda_gold_signup_newsletter").val("Get Lucky");
					},
					error: function (error) {
						$(".pda_sub_form").hide();
						$(".newsletter_inform").show("slow");
						$("#pda_gold_signup_newsletter").val("Get Lucky");
					}
				});
			} else {
				$("#pda_signup_newsletter_error").show("slow");
				$("#pda_signup_newsletter").focus();
				$("#pda_gold_signup_newsletter").val("Get Lucky");
			}
		});
	});
})(window, jQuery);
