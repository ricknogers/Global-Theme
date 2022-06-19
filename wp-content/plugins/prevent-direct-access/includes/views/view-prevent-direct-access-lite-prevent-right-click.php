<tr>
    <td>
        <label class="pda_switch" for="disable_right_click">
            <input type="checkbox" id="disable_right_click"
                   name="disable_right_click" <?php echo esc_attr( $disable_right_click ); ?>  />
            <span class="pda-slider round"></span>
        </label>
    </td>

    <td>
        <p>
            <label><?php echo esc_html__( 'Disable Copy and Right Click', 'prevent-direct-access' ) ?>
            </label>
            <?php echo _e( 'Disable text selection and right-click to <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/settings/?utm_source=user-website&utm_medium=settings-other-security&utm_campaign=pda-lite#prevent-copy-content">prevent content theft</a> on all your web pages.', 'prevent-direct-access' ) ?>
        </p>
    </td>
</tr>
