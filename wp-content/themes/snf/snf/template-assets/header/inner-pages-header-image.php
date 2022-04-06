<?php if ( is_page_template('market-sites/market-home.php') || is_page_template('market-sites/market-inner-template.php')) :?>

    <?php get_template_part('template-assets/header/conditional-headers/markets-header-layout');?>

<?php else:?>
    <div class="container">
        <?php if(is_page('investors')):?>

            <?php get_template_part('template-assets/header/conditional-headers/investor-header');?>

        <?php endif;?>
        <?php if(has_post_thumbnail() && !( is_search() || is_archive()  || is_page('investors'))):?>

            <?php if(  is_page_template('subsidiary-landing.php')):?>

                <?php get_template_part('template-assets/header/conditional-headers/subsidiary-header');?>

            <?php else:?>

                <?php get_template_part('template-assets/header/conditional-headers/default-header');?>

            <?php endif;?>
        <?php else:?>
            <?php if(is_404()):?>

                <?php get_template_part('template-assets/header/conditional-headers/404-header');?>

            <?php endif;?>

            <?php if(is_search()):?>

                <?php get_template_part('template-assets/header/conditional-headers/search-header');?>

            <?php endif;?>

            <?php if(is_archive() || is_category() ):?>

                <?php get_template_part('template-assets/header/conditional-headers/archive-headers');?>

            <?php endif;?>

            <?php if(is_singular( array( 'global-communication', 'products', 'marketing-material' ) )):?>

                <?php get_template_part('template-assets/header/conditional-headers/single-header');?>

            <?php endif;?>

         

        <?php endif;?>
    </div><!--container-fluid-->
<?php endif;?>