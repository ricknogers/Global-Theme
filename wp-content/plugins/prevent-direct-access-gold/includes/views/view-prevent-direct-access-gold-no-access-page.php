<?php
$pda_function           = new Pda_Gold_Functions();
$no_access_page         = $pda_function->pda_get_setting_type_is_string( PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE ) === '' ? 'page-404' : $pda_function->pda_get_setting_type_is_string( PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE );
$nap_custom_link        = $pda_function->pda_get_setting_type_is_string( PDA_v3_Constants::PDA_NAP_CUSTOM_LINK );
$nap_existing_page_post = $pda_function->pda_get_setting_type_is_string( PDA_v3_Constants::PDA_NAP_EXISTING_PAGE_POST );
$title                  = '' !== $nap_existing_page_post ? explode( ';', $nap_existing_page_post )[1] : '';
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Customize "No Access" Page', 'prevent-direct-access-gold' ) ?></label>
			Select what to display when users have no access to your protected files
			<div class="pda-wrap-select-no-access-page">
				<select id="pda-no-access-page">
					<option value="page-404" <?php echo 'page-404' === $no_access_page ? 'selected' : ''; ?>>404 error
						page
					</option>
					<option value="custom-link" <?php echo 'custom-link' === $no_access_page ? 'selected' : ''; ?>>Custom
						link
					</option>
					<option value="search-page-post" <?php echo 'search-page-post' === $no_access_page ? 'selected' : ''; ?>>
						Existing page or post
					</option>
				</select>
			</div>
			<div class="pda-wrap-search-page-post pda-wrap-no-access-content <?php echo ( 'page-404' === $no_access_page || 'custom-link' === $no_access_page ) ? 'pda-hide-no-access-page' : ''; ?>">
				<div class="pda-wrap-input-search">
					<?php wp_nonce_field( 'internal-linking', '_ajax_linking_nonce', false ); ?>
					<input value="<?php echo esc_attr__( $title ); ?>" type="search" id="pda-search-no-access-page"
					       placeholder="Type at least 3 characters to search" class="pda-input-search" autocomplete="off"
					       aria-invalid="false"/>
					<div id="pda-clear-search" class="pda-button-clear">x</div>
					<div id="pda-search-loading" class="pda-button-clear pda-button-search-loading"></div>
				</div>
				<p id="pda-error-nap-existing-page-post">Please enter a valid page.</p>
				<div class="pda-wrap-search-research">
					<ul id="pda-search-result"></ul>
					<input id="pda-result-existing-page-post" type="hidden"
					       value="<?php echo esc_attr__( $nap_existing_page_post ); ?>"/>
				</div>
			</div>
			<div class="pda-wrap-custom-link pda-wrap-no-access-content <?php echo ( 'page-404' === $no_access_page || 'search-page-post' === $no_access_page ) ? 'pda-hide-no-access-page' : ''; ?>">
				<div class="pda-wrap-input-search">
					<input class="pda-input-search" <?php echo 'custom-link' === $no_access_page ? 'required' : ''; ?>
					       id="pda-input-custom-link"
					       autocomplete="off" type="text"
					       placeholder="Enter a custom link"
					       value="<?php echo esc_attr__( $nap_custom_link ); ?>">
					<div id="pda-clear-custom-link" class="pda-button-clear">x</div>
				</div>
				<p id="pda-error-nap-custom-link">Please enter a valid link.</p>
			</div>
		</p>
	</td>
</tr>
