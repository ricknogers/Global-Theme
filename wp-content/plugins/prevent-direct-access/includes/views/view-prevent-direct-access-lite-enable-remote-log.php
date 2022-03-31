<tr>
    <td>
        <label class="pda_switch" for="view_by_logged_user">
            <input type="checkbox" id="view_by_logged_user"
                   name="view_by_logged_user" disabled="disabled"/>
            <span class="pda-slider round"></span>
        </label>
    </td>

    <td>
        <p>
            <label><?php echo esc_html__( 'Enable Debug Logs?', 'prevent-direct-access' ) ?>
            <span>
                <?php echo esc_html__( PDA_Lite_Constants::WARNING_PLAN, 'prevent-direct-access' ) ?>
            </span>
            </label>
            <?php echo esc_html__( 'Log (fatal) errors of your entire website which speeds up the troubleshooting process when problems occur', 'prevent-direct-access' ) ?>
        </p>
    </td>
</tr>