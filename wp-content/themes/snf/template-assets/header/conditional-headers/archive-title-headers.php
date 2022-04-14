<div class="page-header">
    <?php if (is_category()) { ?>
        <h1 class="archive_title h2">
            <span><?php _e("Posts Categorized:", "snf_subsidiary"); ?></span> <?php single_cat_title(); ?>
        </h1>
    <?php } elseif (is_tag()) { ?>
        <h1 class="archive_title h2">
            <span><?php _e("Posts Tagged:", "snf_subsidiary"); ?></span> <?php single_tag_title(); ?>
        </h1>
    <?php } elseif (is_author()) { ?>
        <h1 class="archive_title h2">
            <span><?php _e("Posts By:", "snf_subsidiary"); ?></span> <?php get_the_author_meta('display_name'); ?>
        </h1>
    <?php } elseif (is_day()) { ?>
        <h1 class="archive_title h2">
            <span><?php _e("Daily Archives:", "snf_subsidiary"); ?></span> <?php the_time('l, F j, Y'); ?>
        </h1>
    <?php }elseif (is_day()) { ?>
        <h1 class="archive_title h2">
            <span><?php _e("Daily Archives:", "snf_subsidiary"); ?></span> <?php the_time('l, F j, Y'); ?>
        </h1>
    <?php } elseif (is_month()) { ?>
        <h1 class="archive_title h2">
            <span><?php _e("Monthly Archives:", "snf_subsidiary"); ?></span> <?php the_time('F Y'); ?>
        </h1>
    <?php } elseif (is_year()) { ?>
        <h1 class="archive_title h2">
            <span><?php _e("Yearly Archives:", "snf_subsidiary"); ?></span> <?php the_time('Y'); ?>
        </h1>
    <?php } elseif (is_post_type_archive('timeline')  ) { ?>
        <h1 class="archive_title h2">
            <?php echo post_type_archive_title(); ?>
        </h1>
    <?php } elseif (is_post_type_archive('global-communication')) { ?>
        <h1 class="archive_title h2">
            <?php echo post_type_archive_title(); ?> : Archives=
        </h1>

    <?php } elseif (is_tax( 'markets', '' ) ) { ?>
        <h1 class="archive_title h2">
            <?php
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            echo $term->name; ?>
        </h1>
    <?php } elseif (is_tax( 'snf-communication-types', '' ) ) { ?>
        <h1 class="archive_title h2">
            <?php
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            echo $term->name; ?> : Archives+
        </h1>
    <?php } elseif ('marketing-material' == get_post_type()) { ?>
        <h1 class="archive_title h2">
            <?php post_type_archive_title(); ?> : Archives_
        </h1>
    <?php } elseif (is_tax( 'country', '' ) ) { ?>
        <h1 class="archive_title h2">
            <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );?>
            SNF <?php echo $term->name;?>
        </h1>
    <?php } elseif ( is_tax( 'investors', '' ) ) { ?>
        <h1 class="archive_title h2">
            <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            echo $term->name;?> : Archives!
        </h1>
        <?php }?>
</div>