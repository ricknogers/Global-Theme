<?php
$selected_roles = $setting->selected_roles(PDA_v3_Constants::WHITElIST_ROLES_AUTO_PROTECT)
?>
<tr>
    <?php if ( $setting->getSettings( PDA_v3_Constants::PDA_AUTO_PROTECT_NEW_FILE ) ) { ?>
		<td>
			<label class="pda_switch" for="pda_auto_protect_new_file">
				<input type="checkbox" id="pda_auto_protect_new_file"
							 name="pda_auto_protect_new_file" checked/>
				<span class="pda-slider round"></span>
			</label>
		</td>
    <?php } else { ?>
		<td>
			<label class="pda_switch" for="pda_auto_protect_new_file">
				<input type="checkbox" id="pda_auto_protect_new_file"
							 name="pda_auto_protect_new_file"/>
				<span class="pda-slider round"></span></label>
			</label>
		</td>
    <?php } ?>
    <td>
        <p>
            <label><?php echo esc_html__( 'Auto-protect New File Uploads', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Automatically protect all new file uploads', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
	<tr id="grant-access-protect-file">
		<td></td>
		<td>
			<div class="display-flex">
				<label class="pda_switch" for="pda_roles_auto_protect_new_file">
					<input type="checkbox" id="pda_roles_auto_protect_new_file" name="pda_roles_auto_protect_new_file" <?php echo ! empty( $selected_roles ) ? 'checked' : ''; ?>/>
					<span class="pda-slider round"></span>
				</label>
				<span class="grant-access-user-roles">
					<div>
						<p><?php echo esc_html__( 'Protect new file uploads by these user roles only', 'prevent-direct-access-gold' ) ?></p>
					</div>
					<div id="pda-grant-access-roles">
						<select multiple="multiple" id="pda_auto_protect_new_file_select2" class="pda_select2">
							<?php foreach ($roles as $role_name => $role_info):
								$arrRole = array($role_name); ?>
								<option <?php echo array_intersect( $arrRole, $selected_roles ) ? 'selected="selected"' : '' ?> value="<?php echo $role_name ?>"><?php echo $role_name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</span>
			</div>
		</td>
		<td>

		</td>
	</tr>
</tr>
