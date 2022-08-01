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
	<?php  if ( in_array( $_ENV['PANTHEON_ENVIRONMENT'], array( 'live' ) ) ):?>
		<script   src=https://app.termly.io/embed.min.js data-auto-block="on" data-website-uuid="b8dae01b-f7ee-446f-abc2-8547bb006b4d"></script>
		<!-- Clarity tracking code for https://snf.com/ -->
		<script type="text/javascript">
		    (function(c,l,a,r,i,t,y){
		        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
		        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
		        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		    })(window, document, "clarity", "script", "6l7qj68oee");
		</script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-152401851-1"></script>
	    <script>
	        window.dataLayer = window.dataLayer || [];
	        function gtag(){dataLayer.push(arguments);}
	        gtag('js', new Date());
	
	        gtag('config', 'UA-152401851-1');
	    </script>
	
	<?php endif;?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
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
<?php get_template_part('template-assets/notifications/header-notification')?>
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
    </div><!--globalTopBar-->
    <nav class="navbar navbar-expand-lg navbar-light  site-navbar" role="navigation">
        <div class="container">
            <a class=" logo-wrapper"  href="<?php echo home_url( '/' ); ?>">
                <img src="<?php bloginfo('template_directory'); ?>/resources/images/logos/SNF-Water-Science-Dark-blue-SVG.svg" alt="SNF Logo" class="img-fluid mx-auto d-block ">
            </a>
            <button class=" navbar-toggler x " type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
			<?php
			require_once get_template_directory() . '/resources/inc/class-wp-bootstrap-navwalker.php';
			wp_nav_menu( array(
				'theme_location'  => 'snf_global_main_nav',
				'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
				'container'       => 'div',
				'container_class' => 'collapse navbar-collapse ',
				'container_id'    => 'main-menu',
				'menu_class'      => 'navbar-nav ',
				'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
				'walker'          => new WP_Bootstrap_Mega_Navwalker(),
			) ); ?><!--/.navbar-collapse -->
            <button type="button" class="buttonsearchmobile" id="buttonsearchmobile">
                <i class="fa fa-search openclosesearch"></i><i class="fa fa-search-minus openclosesearch" style="display:none" aria-hidden="true"></i>
            </button>
        </div>
    </nav>
</header>
<script>
    jQuery(document).ready(function($){
        $(".nav-item-84143").click(function(){
            $("#locationsModal").modal('show');
        });
        $('#buttonsearchmobile').click(function(){
            $('#mobilesearch').slideToggle( "fast",function(){
                $( '#content' ).toggleClass( "moremargin" );
            } );
        $('#searchbox').focus()
            $('.openclosesearch').toggle();
        });
    });
</script>
<?php if(has_term('','markets') || has_term('','country')):?>
    <?php get_template_part( 'template-assets/header/taxonomy-nav'); ?>
<?php endif;?>

<?php if(is_front_page()):?>
    <?php get_template_part('template-assets/header/front-page-header'); ?>
<?php else:?>
    <?php get_template_part('template-assets/header/inner-pages-header-image');?>
<?php endif;?>

<div id="content" class="site-content d-flex flex-column min-vh-100 mt-sm-4">