<tr>
	<td>
        <label class="pda_switch" for="use_redirect_urls">
            <input type="checkbox" id="use_redirect_urls" name="use_redirect_urls" disabled="disabled"/>
            <span class="pda-slider round"></span>
        </label>
		<div class="pda_error" id="pda_l_error"></div>
	</td>
    <td>
        <p>
            <label><?php echo esc_html__( 'Keep Raw URLs', 'prevent-direct-access' ) ?>
            <span>
                <?php echo esc_html__( PDA_Lite_Constants::WARNING_PLAN, 'prevent-direct-access' ) ?>
            </span>
            </label>
            <?php echo esc_html__( 'Keep Raw URLs for both Private and Original file URLs. Enable this option ONLY when you are using Wordpress.com or NGINX hostings that don\'t allow rewrite rules modification.', 'prevent-direct-access' ) ?>
        </p>
    </td>
</tr>