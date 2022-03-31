<tr>
    <td class="feature-input"><span class="feature-input"></span></td>
    <td>
        <p>
            <label><?php echo esc_html__( 'Change Download Link Prefix', 'prevent-direct-access-gold' ) ?></label>
            <p class="description pda-wrap-prefix-description"><?php echo esc_html__( 'Your Download Links will be: ', 'prevent-direct-access-gold' ) ?><?php echo get_site_url() . '/' ?><span id="pda_prefix"><?php echo esc_html__( $prefix_name, 'prevent-direct-access-gold' ) ?></span>/<?php _e( 'your-custom-filename', 'prevent-direct-access-gold' ) ?></p>
            <input type="text" id="pda_prefix_url" name="pda_prefix_url" value="<?php echo esc_attr( $prefix_name ) ?>" <?php echo $disabled_by_site ?>/>
            <p class="pda-error-prefix-private-link"><?php echo esc_html__( 'Please enter a valid prefix which should contain lowercase English letters (a-z), numbers (0-9), dash (-) and underscore (_) only.', 'prevent-direct-access-gold' ) ?></p>
        </p>
    </td>
</tr>
