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
            <label><?php echo esc_html__( 'Display the current author’s files only', 'prevent-direct-access' ) ?>
            </label>
            <?php echo esc_html__( 'Show files of the current logged-in user only in the Media Library.', 'prevent-direct-access' ) ?>
        </p>
    </td>
</tr>
