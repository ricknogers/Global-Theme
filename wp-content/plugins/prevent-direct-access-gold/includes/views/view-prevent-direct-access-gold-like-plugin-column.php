<div class="main_container">
	<h3><?php _e('Like our Gold version?', 'prevent-direct-access-gold'); ?></h3>
	<div class="inside">
		<p><?php _e('If you like <b>Prevent Direct Access Gold version</b>, please give us <span class="pda-star dashicons dashicons-star-filled"></span> to motivate the team to work harder, add more powerful features and support you even better :) </br> A huge thanks in advance!', 'prevent-direct-access-gold'); ?></p>
		<p>
			<a href="https://wordpress.org/support/plugin/prevent-direct-access/reviews/?filter=5"
				 target="_blank" class="button-primary"><?php _e("Let's do it", 'prevent-direct-access-gold'); ?></a>
		</p>
		<?php if (!function_exists('plugins_api')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
		} ?>
	</div>
</div>
