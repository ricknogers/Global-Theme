<?php
/**
 * Template Name: Investor Center
 *
@SNF Product
 */

get_header(); ?>
<div class="container container-noChildPages investorTranslation">
    <?php if ( is_user_logged_in() ) { ?>
        <?php get_template_part('template-assets/investors/investor', 'information'); ?>
    <?php } else {; ?>
        <?php get_template_part('template-assets/investors/investor','login');?>
    <?php };?>
</div>
<?php get_footer();?>
