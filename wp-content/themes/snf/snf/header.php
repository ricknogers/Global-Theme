<?php
/**
 * The Header for SNF Group
 *
 * Displays <head>, opening <body>, the <header> amd <nav>, and the opening tags
 * for tha main content.
 *
 * @package SNF Group
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="/facicon-2.svg">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<title> <?php wp_title( '' )   ?></title>
    <meta name="google-site-verification" content="kZJWqTUOOX2VlJRCdWXCu8HIO0LMwoTxZgC-PVCBbLE" />
    <?php wp_head(); ?>
    <?php if((has_term('', 'markets') || has_term('','country') )  && !((is_page(array('news','group-products'))) || is_archive() || is_single())):?>
        <?php $class="taxonomy d-flex flex-column min-vh-100  ";?>
    <?php else:?>
	    <?php $class=" page-option d-flex flex-column min-vh-100  ";?>
    <?php endif;?>
</head>
<body <?php body_class($class); ?>>
<?php get_template_part('template-parts/header-notification')?>
<header id="header" class="site-header " role="banner">
    <div class="globalTopBar container-fluid">
        <div class="row desktopTopBar">
            <div class="col companyTagLine">
                <h3><?php echo get_option('corporate_tag_line');?></h3>
            </div>
            <div class="col-md-8" id="snf-top-wrapper">
                <div class="  itemsRight">
                    <ul class="list-unstyled d-flex flex-wrap  ">
                        <li class="menu-item">
                            <a href="<?php echo home_url('/');?>investors">Investors  </a>
                        </li>
                        <li class="menu-item topbar-locations">
                            <?php get_template_part('country-list');?>
                        </li>
                        <li class="linkedinIcon socialDesktop menu-item">
                            <a href="<?php echo get_option('linkedin_url') ;?>" target="_blank"><i class="fa fa-linkedin-square social" aria-hidden="true"></i> </a>
                        </li>
                        <li class="search-header menu-item">
                            <?php get_template_part( 'template-assets/header/search' ); ?>
                        </li>
                        <?php if ( is_plugin_active('translatepress-multilingual/index.php') ):?>
                            <li class="socialDesk topmenu-item">
                                <?php get_template_part('translate-press');?>
                            </li>
                        <?php endif;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="taglineMobile">
        <div class="inner-tagline-top">
            <h3><?php echo get_option('corporate_tag_line');?></h3>
        </div><!--inner-tagline-top-->
    </div><!--taglineMobile-->
</header>

<div class="transition-navbar rounded">
	<section class="navigation-block ">
        <div class="" id="desktopNavigation">
            <div class=" snf-global-menu subsidiary-menu ">
                <div class=" logo-navigation-container">
                    <div class="nav-element-responsive logo-options">
                        <div class=" desktop-logo country-logo  navbar-brand-centered">
                            <a class="" href="<?php echo home_url( '/' ); ?>">
                                <?php include( locate_template( 'resources/images/logos/start-logo.php', false, false ) );?>
                            </a>
                        </div>
                        <div class=" scroll-logo   navbar-brand-centered">
                            <a class="" href="<?php echo home_url( '/' ); ?>">
                                <?php include( locate_template( 'resources/images/logos/scroll-logo.php', false, false ) );?>
                            </a>
                        </div>
                    </div>
                    <div class="nav-element-responsive global-nav-wrapper">
                        <nav class="navbar navbar-expand-md navbar-dark bg-light text-center" role="navigation">
                            <div class="toggleWrapper">
                                <button class="navbar-toggler x" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div><!--toggleWrapper-->
                            <?php snf_global_main_nav(0); ?>
                            <script>
                                jQuery(document).ready(function($){
                                    $(".nav-item-80175").click(function(){
                                        $("#locationsModal").modal('show');
                                    });
                                });
                            </script>
                        </nav>
                    </div><!--county-nav-wrapper-->
                </div>
            </div><!--snf-country-menu-->
        </div><!--mobileNavigation-->
        <div class="container-fluid" id="mobileNavigation">
            <div class=" snf-global-menu global-menu ">
	            <div class="navigationContainer">
                    <div class=" global-nav-wrapper">
                        <nav class="navbar navbar-expand-md navbar-dark bg-light text-center" role="navigation">
                            <div class="toggleWrapper">
                                <button class="navbar-toggler x" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div><!--toggleWrapper-->
                            <div class="navigation-mobile-wrapper">
                                <?php snf_global_main_nav(0); ?><!--/.navbar-collapse -->
                            </div>
                        </nav>
                    </div><!--county-nav-wrapper-->
                </div><!--navigationContainer-->
                <div class=" logo-navigation-container">
                    <div class="">
                        <div class=" default-logo country-logo  navbar-brand-centered">
                            <a class="" href="<?php echo home_url( '/' ); ?>">
                                <?php include( locate_template( 'resources/images/logos/start-logo.php', false, false ) );?>
                            </a>
                        </div><!--desktop-logo country-logo-->
                        <div class=" scroll-logo country-logo  navbar-brand-centered">
                            <a class="" href="<?php echo home_url( '/' ); ?>">
                                <?php include( locate_template( 'resources/images/logos/scroll-logo.php', false, false ) );?>
                            </a>
                        </div><!--scroll-logo country-logo-->
                    </div>
                </div><!--logo-navigation-container-->
                
            </div><!--snf-country-menu-->
        </div><!--container-->
    </section>
	<?php get_template_part( 'template-assets/header/taxonomy-nav'); ?>
</div><!--transition-navbar-->

<?php if(is_front_page()):?>
    <?php get_template_part('template-assets/header/front-page-header'); ?>
<?php else:?>
    <?php get_template_part('template-assets/header/inner-pages-header-image');?>
<?php endif;?>

<div id="content" class="site-content d-flex flex-column min-vh-100">