<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 1/28/18
 * Time: 13:09
 */
$current_user = wp_get_current_user();
if ( empty( get_user_meta( get_current_user_id(), 'pda_subscribed' ) ) ) {
	?>
	<div class="pda_sub_div">
		<form>
			<p><label for="pda_signup_newsletter"
			          style="font-style:italic; margin-bottom:5px;"><?php echo __( 'Be the first to get our latest updates and probably 1-year Gold license for free.', 'prevent-direct-access' ) ?></label>
			</p>
			<span id="pda_subcribe_div">
            <div>
                <input type="text" id="pda_signup_newsletter" name="pda_signup_newsletter" placeholder="you@example.com"
                       value="<?php echo esc_attr( $current_user->user_email ); ?>"/>
                <input type="button" class="button button-primary" id="pda_signup_newsletter_btn"
                       value="<?php echo __( 'Get Lucky', 'prevent-direct-access' ) ?>"/>
                <p id="pda_signup_newsletter_error" style="display: none; color: red" class="pda_subscribe_error"><span>Please enter your valid email!</span></p>
            </div>
        </span>
		</form>
	</div>
	<?php
} else {
	?>
	<div class="pda_sub_div">
		<p><label class="pda_signup_newsletter" for="pda_signup_newsletter"
		          style=""><?php echo __( 'Congrats! You\'ve subscribed to our newsletter and now stand a chance to win our 1-year Gold license for free.', 'prevent-direct-access' ) ?>
				</br>
				<?php echo __( 'Stay tuned for our updates :)', 'prevent-direct-access' ) ?>
			</label></p>
	</div>
	<?php
}

