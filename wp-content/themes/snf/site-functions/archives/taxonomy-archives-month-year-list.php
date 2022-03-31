<?php

function newsletter_archive_loop() {

    //fetch the terms for the newsletter-groups taxonomy
    $terms = get_terms( 'country', array (
        'hide_empty' => 'true',
    ));

    // run a query for each term
    foreach( $terms as $term ) {

        $dates = array();

        // Define the query
        $args = array(
            'post_type'         => 'global-communication',
            'newsletter-groups' => $term->slug ,
            'posts_per_page'    => -1,
        );

        // run the query
        $query = new WP_Query( $args );

        if( $query->have_posts() ) {

            echo '<div class="letters">';

            // output the term name in a heading tag
            echo'<h4 class="term-heading">' . $term->name . '</h4>';

            while ( $query->have_posts() ) { $query->the_post();

                // get current month
                $current_month = get_the_date('F');

                // get attachements from custom field
                $attachment_id = get_field('file');
                $url             = wp_get_attachment_url( $attachment_id );
                $title       = get_the_title( $attachment_id );

                // get the filesize
                $filesize = filesize( get_attached_file( $attachment_id ) );
                $filesize = size_format($filesize, 2);

                if( !in_array(get_the_date( 'F Y' ), $dates ) ){
                    $dates[] = get_the_date( 'F Y' );
                    echo '<h4 class="date">';
                    echo get_the_date( 'F Y' );
                    echo '</h4>';
                }

                ?>

                <li class="letters-file">
                    <a href="<?php echo $url; ?>" title="<?php echo $title; ?>" target="_blank" rel="noopener noreferrer"><?php the_title(); ?></a><span class="letters-file-sz">&nbsp;(<?php echo $filesize; ?>)</span>
                </li>

            <?php } // endwhile have posts

            echo '</div>'; // close letters div

            // use reset postdata to restore orginal query
            wp_reset_postdata();

        } // end if query have posts

    } // end for each

} // end function