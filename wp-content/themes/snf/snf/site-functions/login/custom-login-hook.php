<?php
/**
 * Custom Login Screen
 */

// WP-ADMIN Logo Change
function my_login_logo() {
    ?>
    <style type="text/css">
        body.login {
            background: rgb(0,45,115);
            background: linear-gradient(167deg, rgba(0,45,115,1) 0%, rgba(0,45,115,0.9920343137254902) 36%, rgba(255,255,255,1) 100%);
        }
        .login #backtoblog a, .login #nav a{
            color:#fff !important;
        }
        .login form{
            background-color: #fff;
            opacity: .9;
        }
        body.login div#login h1 a {
            background-image: url('<?php bloginfo('template_directory'); ?>/resources/images/logos/SNF-White.png');
            height:96px;
            width:330px;
            background-size: cover;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
    <?php
} add_action( 'login_enqueue_scripts', 'my_login_logo' );