<aside class=" list-group archiveSideBar">

    <?php if (is_category()) { ?>
        <h3 class="sidebarTitle">
            <span><?php _e("Posts Categorized:", "snf_subsidiary"); ?></span> <?php single_cat_title(); ?>
        </h3>
    <?php } elseif (is_tag()) { ?>
        <h3 class="sidebarTitle">
            <span><?php _e("Posts Tagged:", "snf_subsidiary"); ?></span> <?php single_tag_title(); ?>
        </h3>
    <?php } elseif (is_author()) { ?>
        <h3 class="sidebarTitle">
            <span><?php _e("Posts By:", "snf_subsidiary"); ?></span> <?php get_the_author_meta('display_name'); ?>
        </h3>
    <?php } elseif (is_day()) { ?>
        <h3 class="sidebarTitle">
            <span><?php _e("Daily Archives:", "snf_subsidiary"); ?></span> <?php the_time('l, F j, Y'); ?>
        </h3>
    <?php } elseif (is_month()) { ?>
        <h3 class="sidebarTitle">
            <span><?php _e("Monthly Archives:", "snf_subsidiary"); ?></span> <?php the_time('F Y'); ?>
        </h3>
    <?php } elseif (is_year()) { ?>
        <h3 class="sidebarTitle">
            <span><?php _e("Yearly Archives:", "snf_subsidiary"); ?></span> <?php the_time('Y'); ?>
        </h3>
    <?php } elseif ('timeline' == get_post_type()) { ?>
        <h3 class="sidebarTitle">
            <?php post_type_archive_title(); ?>
        </h3>
        <ul class="list-group-flush"> <?php get_cpt_archives( 'timeline', true ); ?> </ul>

    <?php } elseif ('global-communication' == get_post_type()) { ?>
        <h3 class="sidebarTitle">
            <?php post_type_archive_title(); ?>
        </h3>
        <ul class="list-group-flush"> <?php get_cpt_archives( 'global-communication', true ); ?> </ul>
    <?php } elseif (is_tax( 'markets', '' ) ) { ?>
        <h3 class="sidebarTitle">
            <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            echo $term->name;?>

        </h3>
        <ul class="list-group-flush"> <?php newsletter_archive_loop( ); ?> </ul>
    <?php } elseif (is_tax( 'snf-communication-types', '' ) ) { ?>
        <h3 class="sidebarTitle">
            <?php
            // taxonomy term archives
            $post_type = get_post_type();
            $taxonomies = get_object_taxonomies($post_type);
            if(!empty($taxonomies)){
                foreach($taxonomies as $taxonomy){
                    $terms = get_terms($taxonomy);
                    if(!empty($terms)){
                        echo "<ul>";
                        foreach ( $terms as $term ) {
                            echo '<li><a href="'.get_term_link($term->slug, $taxonomy).'">'. $term->name . "</a></li>";
                        }
                        echo "</ul>";
                    }
                }
            }
            ;?>

        </h3>
        <ul class="list-group-flush"> <?php newsletter_archive_loop( ); ?> </ul>
    <?php } elseif ('marketing-material' == get_post_type()) { ?>
        <h3 class="sidebarTitle">
            <?php post_type_archive_title(); ?>
        </h3>
        <ul class="list-group-flush"> <?php get_cpt_archives( 'marketing-material', true ); ?> </ul>
    <?php } elseif (is_tax( 'country', '' ) ) { ?>
        <h3 class="sidebarTitle">
            <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            echo $term->name;?>
        </h3>
        <ul class="list-group-flush"> <?php newsletter_archive_loop( $term->slug); ?> </ul>
    <?php } elseif ( is_tax( 'investors', '' ) ) { ?>
        <h3 class="sidebarTitle">
            <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            echo $term->name;?>
        </h3>
        <ul class="list-group-flush"> <?php get_cpt_archives( 'investors', true ); ?> </ul>
    <?php }else{?>
        <h3 class="sidebarTitle">
            <?php if(is_tax()):?>
                <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                echo $term->name;?>
            <?php else:?>
                <?php post_type_archive_title(); ?>
            <?php endif;?>
        </h3>

    <?php } ?>

</aside><!--newsSideBar-->