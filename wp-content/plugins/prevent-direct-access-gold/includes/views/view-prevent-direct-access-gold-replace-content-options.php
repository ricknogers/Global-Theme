<tr>
	<?php if ( $setting->getSettings( PDA_v3_Constants::PDA_AUTO_REPLACE_PROTECTED_FILE ) ) { ?>
		<td>
			<label class="pda_switch" for="pda_auto_replace_protected_file">
				<input type="checkbox" id="pda_auto_replace_protected_file"
				       name="pda_auto_replace_protected_file" checked/>
				<span class="pda-slider round"></span>
			</label>
		</td>
	<?php } else { ?>
		<td>
			<label class="pda_switch" for="pda_auto_replace_protected_file">
				<input type="checkbox" id="pda_auto_replace_protected_file"
				       name="pda_auto_replace_protected_file"/>
				<span class="pda-slider round"></span></label>
			</label>
		</td>
	<?php } ?>
	<td>
		<p>
			<label><?php echo esc_html__( 'Search & Replace', 'prevent-direct-access-gold' ) ?></label>
			<?php echo esc_html__( 'Search and auto-replace new protected files whose URLs are already embedded in content', 'prevent-direct-access-gold' ) ?>
		</p>
	</td>

</tr>
<?php
$is_display  = $setting->getSettings( PDA_v3_Constants::PDA_AUTO_REPLACE_PROTECTED_FILE ) ? "" : "style='display:none'";
$is_required = $setting->getSettings( PDA_v3_Constants::PDA_AUTO_REPLACE_PROTECTED_FILE ) ? 'required' : '';
?>
<tr id="pda-pages-posts-replace" <?php echo $is_display ?>>
	<td></td>
	<td><p><?php echo esc_html__( 'Apply to these pages or posts only', 'prevent-direct-access-gold' ) ?></p>
		<?php $selected_posts = $setting->selected_roles( PDA_v3_Constants::PDA_REPLACED_PAGES_POSTS ); ?>
		<select <?php echo esc_attr( $is_required ); ?> multiple="multiple" id="pda_replaced_pages_select2"
		                                                class="pda_select2">
			<?php foreach ( $pages as $page ): ?>
				<?php $is_selected = array_search( $page->ID, $selected_posts ) !== false ? "selected" : "" ?>
				<option <?php echo esc_attr( $is_selected ) ?>
						value="<?php echo esc_html__( $page->ID ) ?>"><?php echo '' !== $page->post_title ? esc_html__( $page->post_title, 'prevent-direct-access-gold' ) : esc_html__( '(no title)', 'prevent-direct-access-gold' ); ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>
