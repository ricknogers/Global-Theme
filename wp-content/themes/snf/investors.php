<?php
/**
 * Template Name: Investor Center
 *
@SNF Product
 */

get_header(); ?>
    <?php if ( is_user_logged_in() ) { ?>
        <?php get_template_part('template-assets/investors/investor', 'information'); ?>
    <?php } else {; ?>
        <?php get_template_part('template-assets/investors/investor','login');?>
    <?php };?>

<?php get_footer();?>
