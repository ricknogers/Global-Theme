<div class="container">
	<div class="row">
		<?php
    $investor_terms = get_terms('investors');
    $investors = array();
    foreach ($investor_terms as $investor_term) {
        $investors[] = $investor_term->slug;
    }
    $args = array(
        'orderby' => 'date',
        'post_type' =>  'document',
        'posts_per_page' => 3,
        'tax_query' => array(
            array(
                'taxonomy' => 'investors',
                'field' => 'slug',
                'terms' => $investors
            ),
            array(
                'taxonomy' => 'investors',
                'field' => 'slug',
                'terms' => 'upcoming-events',
                'operator'    => 'NOT IN'
            )
        ),
    );
    $query = new WP_Query( $args ); ?>
    <?php if ( $query->have_posts() ) : $count = 0;?>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="col-lg-4 col-md-4 col-sm-4 investorCard">
                <div class="card h-100">
                    <div class="card-img investor-gradient">
                        <?php  $term_array = array();
                        $term_list = wp_get_post_terms($post->ID, 'investors', array(
                                "fields" => "all",
                                'orderby' => 'parent',
                                'order' => 'ASC'
                            )
                        );
                        foreach($term_list as $term_single) {
                            $term_array[] = $term_single->name ; //do something here
                        }
                        ?>
                        <?php
                        $file = get_field('doc_fichier');
                        $url = wp_get_attachment_url( $file );
                        ?>

                        <div class="date">

                            <?php foreach($term_list as $term) :?>
                                <a href="<?php echo get_category_link( $term->term_id ) ?>">
                                    <h5 class="text-muted <?php echo $term->slug; ?>"><?php echo $term->name; ?></h5>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-body investor-info">
                        <h3 class="card-title"><a href="<?php echo esc_url( $file['url'] ); ?>" target="_blank"><?php the_title();?></a></h3>

                        <?php foreach($term_list as $term) :?>
                        <a href="<?php echo get_category_link( $term->term_id ) ?>">
                            <p><?php echo $term->description; ?> </p>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Published Date: <?php echo get_the_date() ?></small>
                    </div>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    <?php endif;?>
	</div>
</div>
