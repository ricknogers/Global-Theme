<?php
if ( ! class_exists('PDA_Affiliate' ) ){
    class PDA_Affiliate {
        function render_ui() {
            $widgets = new Prevent_Direct_Access_Gold_Setting_Widgets();
            $url = PDA_BASE_URL . "public/assets/pda-gold-affiliate-banner(1200x480).png";
            ?>
            <div class="wrap">
                <h2>Prevent Direct Access Gold: <?php esc_html_e( 'Invite & Earn', 'prevent-direct-access-gold' ); ?></h2>
                <a class="pda-affiliate-program-page" target="_blank" href="http://bit.ly/joinpdaffiliate">
                    <img width="100%" src="<?php echo esc_attr($url) ?> ">
                </a>
            </div>
            <div id="pda_right_column_metaboxes">
                <?php $widgets->render_subscribe_form(); ?>
                <?php $widgets->render_like_plugin_column(); ?>
            </div>
            <?php
        }
    }
}
