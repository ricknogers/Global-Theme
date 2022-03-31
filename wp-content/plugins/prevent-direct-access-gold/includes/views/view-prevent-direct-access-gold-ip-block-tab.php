<div class="main_container">
	<h3><?php _e( 'Restrict access to private download links', 'prevent-direct-access-gold' ) ?></h3>
	<form method="post" id="pda_gold_ip_block_form">
        <?php wp_nonce_field('pda_ajax_nonce_ip_block', 'nonce_ip_block') ?>
		<p class="ip-block-title"> <?php _e( 'Blacklist these IP addresses: stop the following IP addresses from accessing private download links', 'prevent-direct-access-gold' ) ?></p>
		<input id="pda_gold_ip_block" name="ip_block" value="<?php _e($ip_block); ?>"/><br>
		<p class="description"><?php _e( 'Use the asterisk (*) for wildcard matching. E.g: 7.7.7.* will match IP from 7.7.7.0 to 7.7.7.255', 'prevent-direct-access-gold' ) ?></p>
		<p>
            <?php if ( Pda_Gold_Functions::is_license_expired() )  { ?>
                <input type="submit" value="<?php _e( 'Save changes', 'prevent-direct-access-gold' ); ?>" class="button button-primary"	 name="btn_ip_lock" disabled />
            <?php } else { ?>
                <input type="submit" value="<?php _e( 'Save changes', 'prevent-direct-access-gold' ); ?>" class="button button-primary"	 name="btn_ip_lock" />
            <?php } ?>
            
        </p>
	</form>
</div>