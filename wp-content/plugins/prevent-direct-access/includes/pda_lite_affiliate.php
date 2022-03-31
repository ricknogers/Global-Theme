<?php
if ( ! class_exists('PDA_Lite_Affiliate' ) ){
    class PDA_Lite_Affiliate {
        function render_ui() {
            $url = PDA_LITE_BASE_URL . "public/assets/pda-gold-affiliate-banner(1200x480).png";
            ?>
            <div class="wrap">
                <h2><?php esc_html_e( 'Prevent Direct Access Gold: Invite & Earn', 'prevent-direct-access' ); ?></h2>
                <a class="pda-affiliate-program-page" target="_blank" href="http://bit.ly/joinpdaffiliate">
                    <img width="100%" src="<?php echo esc_attr($url) ?> ">
                </a>
            </div>
            <?php
        }
    }
}
