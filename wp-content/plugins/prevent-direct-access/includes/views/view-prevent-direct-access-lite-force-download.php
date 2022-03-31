<tr>
    <td>
        <label class="pda_switch" for="pda_force_download">
            <input type="checkbox" id="pda_force_download" name="pda_force_download" disabled="disabled"/>
            <span class="pda-slider round"></span>
        </label>
        <div class="pda_error" id="pda_l_error"/>
    </td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Force Downloads', 'prevent-direct-access' ) ?>
			</label>
			<?php echo esc_html__( 'Force downloads instead of redirecting to protected files when clicking Private Links', 'prevent-direct-access' ) ?>
			<span>
                <?php echo esc_html__( PDA_Lite_Constants::WARNING_PLAN, 'prevent-direct-access' ) ?>
            </span>
		</p>
	</td>
</tr>
