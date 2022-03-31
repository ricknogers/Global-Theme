<?php if ( empty( $user_meta ) || false === $user_meta ) { ?>
	<div class="main_container">
		<div class="pda_sub_div">
			<form class="pda_sub_form">
				<p>
					<label class="pda_signup_newsletter" for="pda_signup_newsletter">
						<?php echo __( 'Be the first to get our latest updates and probably 1-year Gold license for free.', 'prevent-direct-access-gold' ) ?>
					</label>
				</p>
				<span id="pda_subcribe_div">
					<div>
						<input type="text" id="pda_gold_signup_newsletter_input" name="pda_signup_newsletter"
						       placeholder="you@example.com" value="<?php echo $current_user->user_email ?>"/>
						<div id="pda_signup_newsletter_error" style="display: none"
						     class="pda_subscribe_error">
							<span>
								<?php _e( 'Please enter your valid email.', 'prevent-direct-access-gold' ) ?>
							</span>
						</div>
						<p>
							<input type="submit" class="button button-primary" id="pda_gold_signup_newsletter"
							       value="<?php echo __( 'Get Lucky', 'prevent-direct-access-gold' ) ?>"/>
						</p>
					</div>
				</span>
			</form>
			<p style="display: none;" class="newsletter_inform">
				<label class="pda_signup_newsletter" for="pda_signup_newsletter">
					<?php echo __( 'Congrats! You\'ve subscribed to our newsletter and now stand a chance to win our 1-year Gold license for free.', 'prevent-direct-access-gold' ) ?></br>
					<?php echo __( 'Stay tuned for our updates :)', 'prevent-direct-access-gold' ) ?>
				</label>
			</p>
		</div>
	</div>
<?php } else { ?>
	<div class="main_container">
		<div class="pda_sub_div">
			<p>
				<label class="pda_signup_newsletter" for="pda_signup_newsletter">
					<?php echo __( 'Congrats! You\'ve subscribed to our newsletter and now stand a chance to win our 1-year Gold license for free.', 'prevent-direct-access-gold' ) ?></br>
					<?php echo __( 'Stay tuned for our updates :)', 'prevent-direct-access-gold' ) ?>
				</label>
			</p>
		</div>
	</div>
<?php }
