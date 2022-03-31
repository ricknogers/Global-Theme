<div class="pdav3_mp4">
    <input type="hidden" id="pda_v3_post_id" value="<?php echo $post->ID ?>"/>
    <input type="hidden" name="pda-v3-protection_toggle" value="off"/>
    <input type="checkbox" id="pda_v3_protection_toggle"
           name="pda_v3_protection_toggle" <?php checked( ( $is_protected ) ); ?> />
    <label class="pda-v3-protection-toggle" for="pda_v3_protection_toggle">
            <span aria-role="hidden" class="pdav3-on button button-primary"
                  data-pdav3-content="<?php esc_attr_e( 'Protect this file', 'prevent-direct-access-gold' ); ?>"></span>
        <span aria-role="hidden" class="pdav3-off"
              data-pdav3-content="<?php esc_attr_e( 'Unprotect this file', 'prevent-direct-access-gold' ); ?>"></span>

        <span class="visuallyhidden"><?php esc_html_e( 'Protect this attachment\'s files with PDA.', 'prevent-direct-access-gold' ); ?></span>
    </label>
    <div class="pda_v3_wrap_file_access_permission">
        <div><?php _e('File Access Permission', 'prevent-direct-access-gold') ?></div>
        <select class='pda_v3_file_access_permission' id="pda_file_access_permission_value">
            <option <?php echo $type_select === 'default' ? 'selected' : '' ?> value="default"><?php esc_attr_e( 'Use default setting', 'prevent-direct-access-gold' ); ?>
            </option>
            <option <?php echo $type_select === 'admin-user' ? 'selected' : '' ?> value="admin-user"><?php esc_attr_e( 'Admin users', 'prevent-direct-access-gold' ); ?>
            </option>
            <option <?php echo $type_select === 'author' ? 'selected' : '' ?> value="author"><?php esc_attr_e( 'The file\'s author', 'prevent-direct-access-gold' ); ?>
            </option>
            <option <?php echo $type_select === 'logger-in-user' ? 'selected' : ''; ?> value="logger-in-user"><?php esc_attr_e( 'Logged-in users', 'prevent-direct-access-gold' ); ?>
            </option>
            <option <?php echo $type_select === 'blank' ? 'selected' : ''; ?> value="blank"><?php esc_attr_e( 'No user roles', 'prevent-direct-access-gold' ); ?>
            </option>
            <option <?php echo $type_select === 'anyone' ? 'selected' : ''; ?> value="anyone"><?php esc_attr_e( 'Anyone', 'prevent-direct-access-gold' ); ?>
            </option>
            <option <?php echo $type_select === 'custom-roles' ? 'selected' : '' ?> value="custom-roles" disabled>
                <?php esc_attr_e( 'Choose custom roles', 'prevent-direct-access-gold' ); ?>
            </option>
	        <option <?php echo $type_select === 'memberships' ? 'selected' : '' ?> value="memberships" disabled>
		        <?php esc_attr_e( 'Choose custom memberships', 'prevent-direct-access-gold' ); ?>
	        </option>
        </select>
        <span id="pda_loader"></span>
    </div>
</div>
