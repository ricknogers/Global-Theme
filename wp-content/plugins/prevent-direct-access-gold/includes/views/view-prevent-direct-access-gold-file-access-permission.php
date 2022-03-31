<tr>
    <td class="feature-input"><span class="feature-input"></span></td>
    <td>
        <p>
            <label><?php echo esc_html__( 'Set File Access Permission', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Select user roles who can access protected files through their file URLs', 'prevent-direct-access-gold' ) ?>
        </p>
        <select id="file_access_permission">
            <option value="admin_users" <?php if ( $file_access == "admin_users" ) { echo "selected";	} ?> ><?php echo esc_html__( 'Admin users', 'prevent-direct-access-gold' ) ?></option>
            <option value="author" <?php if ( $file_access == "author" ) { echo "selected"; } ?> ><?php echo esc_html__( 'The file\'s author', 'prevent-direct-access-gold' ) ?></option>
            <option value="logged_users" <?php if ( $file_access == "logged_users" ) { echo "selected"; } ?> ><?php echo esc_html__( 'Logged-in users', 'prevent-direct-access-gold' ) ?></option>
            <option value="blank" <?php if ( $file_access == "blank" ) { echo "selected";	} ?> ><?php echo esc_html__( 'No user roles', 'prevent-direct-access-gold' ) ?></option>
            <option value="anyone" <?php if ( $file_access == "anyone" ) { echo "selected"; } ?> ><?php echo esc_html__( 'Anyone', 'prevent-direct-access-gold' ) ?></option>
            <option value="custom_roles" <?php if ( $file_access == "custom_roles" ) { echo "selected"; } ?> ><?php echo esc_html__( 'Choose custom roles', 'prevent-direct-access-gold' ) ?></option>
        </select>
    </td>
</tr>
<tr id="grant-access">
    <td></td>
    <td><p><?php echo esc_html__( 'Grant access to these user roles only', 'prevent-direct-access-gold' ) ?></p>
        <select multiple="multiple" id="pda_role_select2" class="pda_select2">
            <?php foreach ( $roles as $role_name => $role_info ):
                $arrRole = array( $role_name ); ?>
                <option <?php echo array_intersect( $arrRole, $setting->selected_roles( PDA_v3_Constants::WHITElIST_ROLES ) ) ? 'selected="selected"' : '' ?> value="<?php echo esc_attr ( $role_name ) ?>"><?php echo esc_html__( $role_name ) ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>
