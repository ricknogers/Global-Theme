<tr>
	<td>
		<label class="pda_switch" for="pda_prevent_access_license">
            <input type="checkbox" id="pda_prevent_access_license" disabled="disabled"/>
            <span class="pda-slider round"></span>
		</label>
	</td>
    <td>
        <p>
            <label><?php echo esc_html__( 'Block Access to Sensitive Files', 'prevent-direct-access' ) ?>
            <span>
              <?php echo esc_html__( PDA_Lite_Constants::WARNING_PLAN, 'prevent-direct-access' ) ?>
            </span>
            </label>
            <?php echo esc_html__( 'Block access to readme.html, license.txt, and wp-config-sample.php files', 'prevent-direct-access' ) ?>
        </p>
    </td>
</tr>