<div class="container">
    <div class="row" id="">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-sm-12 most-recent-inv-docs">
                    <h2>Recent Releases </h2>
                </div>
                <?php get_template_part('template-assets/investors/investor-highlight');?>
            </div>
            <div class=" featuredInvestors" >
                <?php
                /** Loop through Categories and Display Posts within */
                $post_type = 'document';
				

                $investor_terms = get_terms( array(
                   'taxonomy' => 'investors',
                   'orderby' => 'meta_value_num',
                   'meta_key' => 'position_number',
                   'hide_empty' => true,
                ) );
                
                $investors = array();
                foreach ($investor_terms as $investor_term) {
	                $investors[] = $investor_term->slug;
                }
                foreach ($investor_terms as $investor_term):
                
                    $args = array(
                        'orderby' => 'date',
                        'post_type' =>  'document',
                        'posts_per_page' => 3,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'investors',
                                'field' => 'slug',
                                'terms' => $investor_term->slug,
                                
                            ),
                            array(
                                'taxonomy' => 'investors',
                                'field' => 'slug',
                                'terms' => 'upcoming-events',
                                'operator'    => 'NOT IN'
                            )
                        ),
                    );

                        $posts = new WP_Query($args);
                        if( $posts->have_posts() ): ?>
                            <!-- Output Category Name in Blue Bar and Display # of Post included in Category -->
                            <div class="col-sm-12 investorCategoryTitle" id="<?php echo $investor_term->slug; ?>">
                                <ul class="list-group catLable">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <button type="button" class="none" data-container="body" data-toggle="popover" data-placement="top" data-title="<?php  echo $investor_term->name;?>" data-content="<?php echo $investor_term->description;?>">
                                            <i class="fa fa-question-circle text-info" aria-hidden="true"></i>
                                        </button>
                                        <a href="<?php echo get_term_link($investor_term->slug, $investor_term->taxonomy);?>" >
                                            <h2><?php echo $investor_term->name; ?> </h2>
                                        </a>
                                        <div class="catCount">
                                            <span class="newsCategoryCount ">(<?php echo $posts->found_posts;?>) </span>
                                        </div>
                                    </li>
                                </ul><!--catLable-->
                            </div><!--investorCategoryTitle-->
                            <div class=" investorCategoryListings">
                                <ul class="list-group list-group-flush">
                                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                        <?php  $file = get_field('doc_fichier');  $url = wp_get_attachment_url( $file );?>
                                        <?php if($file) :?>
                                            <li class="list-group-item  my-3">
                                                <a href="<?php echo esc_url( $file['url'] ); ?>" target="_blank">
                                                    <div class="media-body">
                                                        <h5 class="mt-0 mb-1"><?php  echo get_the_title(); ?> <i class="bi bi-chevron-double-right"></i></h5>
                                                        <small class="text-muted">Published Date: <?php echo get_the_date() ?></small>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php else:?>
                                            <li class="list-group-item  my-3 border-right-0 border-left-0 border-top-0 border-bottom">
                                                <a href="<?php the_permalink();?>">
                                                    <div class="img-card-top">
                                                        <div class="dateContainer">
                                                            <?php $unixtimestamp = strtotime( get_field('doc_date') ); ?>
                                                            <span class="month"><?php echo date_i18n( "m", $unixtimestamp ); ?></span>
                                                            <span class="year"><?php echo date_i18n( "Y", $unixtimestamp ); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="mt-0 mb-1"><?php  echo get_the_title(); ?></h5>
                                                        <small class="text-muted">Published Date: <?php echo get_the_date() ?></small>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endif;?>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        <?php endif;?>
                    <?php endforeach;?>
            </div><!--featuredInvestors-->
        </div>
    </div>
</div>