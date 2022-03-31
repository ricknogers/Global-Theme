<tr>
	<td>
		<label class="pda_switch" for="pda_auto_replace_protected_file">
			<input type="checkbox" id="pda_auto_replace_protected_file"
			       name="pda_auto_replace_protected_file" disabled="disabled"/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Search & Replace', 'prevent-direct-access' ) ?>
			</label>
			<?php echo esc_html__( 'Search and auto-replace new protected files whose URLs are already embedded in content', 'prevent-direct-access' ) ?>
			<span>
                <?php echo esc_html__( PDA_Lite_Constants::WARNING_PLAN, 'prevent-direct-access' ) ?>
            </span>
		</p>
	</td>

</tr>
