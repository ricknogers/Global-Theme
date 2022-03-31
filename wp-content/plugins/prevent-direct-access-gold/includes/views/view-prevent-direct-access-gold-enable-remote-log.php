<tr>
	<?php if ( $setting->getSettings(PDA_v3_Constants::REMOTE_LOG ) ) { ?>
		<td>
			<label class="pda_switch" for="remote_log">
				<input type="checkbox" id="remote_log" name="remote_log" checked/>
				<span class="pda-slider round"></span>
			</label>
			<div class="pda_error" id="pda_l_error"></div>
		</td>
	<?php } else { ?>
		<td>
			<label class="pda_switch" for="remote_log">
				<input type="checkbox" id="remote_log" name="remote_log"/>
				<span class="pda-slider round"></span>
			</label>
			<div class="pda_error" id="pda_l_error"/>
		</td>
	<?php } ?>
	<td>
		<p>
			<label><?php echo esc_html__( 'Enable Debug Logs', 'prevent-direct-access-gold' ) ?></label>
			<?php echo esc_html__( 'Log (fatal) errors of your entire website which speeds up the troubleshooting process when problems occur', 'prevent-direct-access-gold' ) ?>
		</p>
	</td>
</tr>
