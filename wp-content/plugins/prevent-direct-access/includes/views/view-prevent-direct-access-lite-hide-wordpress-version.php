<tr>
	<td>
		<label class="pda_switch" for="pda_prevent_access_version">
            <input type="checkbox" id="pda_prevent_access_version" disabled="disabled"/>
            <span class="pda-slider round"></span>
		</label>
	</td>
    <td>
        <p>
            <label><?php echo esc_html__( 'Hide WordPress Version', 'prevent-direct-access' ) ?>
            <span>
                <?php echo esc_html__( PDA_Lite_Constants::WARNING_PLAN, 'prevent-direct-access' ) ?>
            </span>
            </label>
            <?php echo esc_html__( 'Remove WordPress generator meta tag showing its version and sensitive information', 'prevent-direct-access' ) ?>
        </p>
    </td>
</tr>