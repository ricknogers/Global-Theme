<tr>
    <?php if ( $setting->getSettings( PDA_v3_Constants::FORCE_DOWNLOAD ) ) { ?>
        <td>
            <label class="pda_switch" for="pda_force_download">
                <input type="checkbox" id="pda_force_download" name="pda_force_download" checked/>
                <span class="pda-slider round"></span>
            </label>
            <div class="pda_error" id="pda_l_error"></div>
        </td>
    <?php } else { ?>
        <td>
            <label class="pda_switch" for="pda_force_download">
                <input type="checkbox" id="pda_force_download" name="pda_force_download"/>
                <span class="pda-slider round"></span>
            </label>
            <div class="pda_error" id="pda_l_error"/>
        </td>
    <?php } ?>
    <td>
        <p>
            <label><?php echo esc_html__( 'Force Downloads', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Force downloads instead of redirecting to protected files when clicking Download Links', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
</tr>
