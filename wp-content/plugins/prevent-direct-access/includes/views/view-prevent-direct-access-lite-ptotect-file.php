<tr>
    <td>
        <label class="pda_switch" for="hide_protected_files_in_media">
            <input type="checkbox" id="hide_protected_files_in_media"
                   name="hide_protected_files_in_media" <?php echo esc_attr( $hide_protected_files_in_media ); ?>  />
            <span class="pda-slider round"></span>
        </label>
    </td>

    <td>
        <p>
            <label><?php echo esc_html__( 'Restrict Media Library Access', 'prevent-direct-access' ) ?>
            </label>
            <?php echo _e( 'Allow users to view <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/settings/?utm_source=user-website&utm_medium=settings-other-security&utm_campaign=pda-lite#restrict-media-access">their own file uploads</a> in Media Library only. Admin users can see all files by default.', 'prevent-direct-access' ) ?>
        </p>
    </td>
</tr>
