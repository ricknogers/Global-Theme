<tr>
    <td>
        <label class="pda_switch" for="pda_auto_create_new_private_link">
            <input type="checkbox" id="pda_auto_create_new_private_link"
                         name="pda_auto_create_new_private_link" disabled="disabled"/>
            <span class="pda-slider round"></span></label>
        </label>
    </td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Generate Private Link Once Protected', 'prevent-direct-access' ) ?>
			</label>
			<?php echo esc_html__( 'Automatically create a new private link once the file is protected', 'prevent-direct-access' ) ?><span>
                <?php echo esc_html__( PDA_Lite_Constants::WARNING_PLAN, 'prevent-direct-access' ) ?>
            </span>
		</p>
	</td>
</tr>
