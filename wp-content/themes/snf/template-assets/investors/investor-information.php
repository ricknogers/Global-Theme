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
            // Get all the taxonomies for this post type
            $taxonomies = get_object_taxonomies( array( 'post_type' => $post_type ) );
            foreach( $taxonomies as $taxonomy ) :
                // Gets every "category" (term) in this taxonomy to get the respective posts
                $terms = get_terms( $taxonomy );
                $terms = get_terms( array(
                    $taxonomy,
                    //            'orderby' => 'meta_value_num',
                    //            'meta_key' => 'investor_order',
                    'hide_empty' => 'false',
                    "fields" => "all",
                ) );
                foreach( $terms as $term ) : ?>
                    <?php
                    $numOfCols = 2;
                    $rowCount = 0;
                    $bootstrapColWidth = 12 / $numOfCols;
                    $args = array(
                        'post_type' => $post_type,
                        'posts_per_page' => 4,  //show all posts
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'slug',
                                'terms' => $term->slug,

                            ),
                            array(
                                'taxonomy' => 'investors',
                                'field' => 'slug',
                                'terms' => 'upcoming-events',
                                'operator'    => 'NOT IN'
                            )
                        )
                    );
                    $posts = new WP_Query($args);
                    if( $posts->have_posts() ): ?>
                        <!-- Output Category Name in Blue Bar and Display # of Post included in Category -->
                        <div class="col-sm-12 investorCategoryTitle" id="<?php echo $term->slug; ?>">
                            <ul class="list-group catLable">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <button type="button" class="none" data-container="body" data-toggle="popover" data-placement="top" data-title="<?php  echo $term->name;?>" data-content="<?php echo $term->description;?>">
                                        <i class="fa fa-question-circle text-info" aria-hidden="true"></i>
                                    </button>
                                    <a href="<?php echo get_term_link($term);?>">
                                        <h2><?php echo $term->name; ?> </h2>
                                    </a>
                                    <div class="catCount">
                                        <span class="newsCategoryCount ">(<?php echo $posts->found_posts;?>) </span>
                                    </div>
                                </li>
                            </ul><!--catLable-->
                        </div><!--investorCategoryTitle-->
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <?php if($rowCount % $numOfCols == 0) { ?> <div class="row investor-snippet"> <?php } $rowCount++; ?>
                            <div class="col-sm-<?php echo $bootstrapColWidth;?> investorCategoryListings">
                                <ul class="list-unstyled">
                                    <?php
                                    $file = get_field('doc_fichier');
                                    $url = wp_get_attachment_url( $file );
                                    ?>
                                    <?php if($file) :?>
                                        <a href="<?php echo esc_html($url); ?>" target="_blank">
                                            <li class="media">
                                                <div class="img-card-top">
                                                    <div class="dateContainer">
                                                        <?php
                                                        // Load field value.
                                                        $unixtimestamp = strtotime( get_field('doc_date') );
                                                        ?>
                                                        <span class="month"><?php echo date_i18n( "m", $unixtimestamp ); ?></span>
                                                        <span class="year"><?php echo date_i18n( "Y", $unixtimestamp ); ?></span>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1"><?php  echo get_the_title(); ?></h5>
                                                    <p class="card-text"><?php echo wp_trim_words( get_the_content(), 25, '' );?></p>
                                                </div>
                                            </li>
                                        </a>
                                    <?php else:?>
                                        <a href="<?php the_permalink();?>">
                                            <li class="media">
                                                <div class="img-card-top">
                                                    <div class="dateContainer">
                                                        <?php
                                                        // Load field value.
                                                        $unixtimestamp = strtotime( get_field('doc_date') );
                                                        ?>
                                                        <span class="month"><?php echo date_i18n( "m", $unixtimestamp ); ?></span>
                                                        <span class="year"><?php echo date_i18n( "Y", $unixtimestamp ); ?></span>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1"><?php  echo get_the_title(); ?></h5>
                                                    <?php echo wp_trim_words( get_the_content(), 25, '' );?>
                                                </div>
                                            </li>
                                        </a>
                                    <?php endif;?>
                                    <hr />
                                </ul>
                            </div>
                            <?php if($rowCount % $numOfCols == 0) { ?> </div> <?php }  ?>
                        <?php endwhile; ?>
                    <?php endif;?>
                <?php endforeach;?>
            <?php endforeach;?>
        </div><!--featuredInvestors-->
    </div>

    <div class="col-md-12 col-sm-12 investor-support">
        <h2>Investor Support </h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card profile-card-2">
                    <div class="card-img-block">
                        <img class="img-fluid" src="<?php bloginfo('template_directory'); ?>/resources/images/investors/CraterLakeHeroImage.jpg" alt="Card image cap">
                    </div>
                    <div class="card-body pt-5">
                        <img src="https://randomuser.me/api/portraits/men/64.jpg" alt="profile-image" class="profile"/>
                        <h5 class="card-title">Loic FAUCHEUR</h5>
                        <p class="card-text">VP Finance at SNF</p>
                        <div class="icon-block">
                            <a href="#">
                                <i class="fa fa-linkedin"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-envelope-o"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <p class="mt-3 w-100 float-left text-center"><strong>investor@snf.com</strong></p>
            </div>
            <div class="col-md-4">
                <div class="card profile-card-2">
                    <div class="card-img-block">
                        <img class="img-fluid" src="<?php bloginfo('template_directory'); ?>/resources/images/investors/CraterLakeHeroImage.jpg" alt="Card image cap">
                    </div>
                    <div class="card-body pt-5">
                        <img src="https://randomuser.me/api/portraits/men/64.jpg" alt="profile-image" class="profile"/>
                        <h5 class="card-title">Mathieu CARRE</h5>
                        <p class="card-text">Investor Relations</p>
                        <div class="icon-block">
                            <a href="#">
                                <i class="fa fa-linkedin"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-envelope-o"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <p class="mt-3 w-100 float-left text-center"><strong>+33 4 77 36 86 00</strong></p>
            </div>
            <div class="col-md-4">
                <div class="card profile-card-2">
                    <div class="card-img-block">
                        <img class="img-fluid" src="<?php bloginfo('template_directory'); ?>/resources/images/investors/CraterLakeHeroImage.jpg" alt="Card image cap">
                    </div>
                    <div class="card-body pt-5">
                        <img src="https://randomuser.me/api/portraits/men/64.jpg" alt="profile-image" class="profile"/>
                        <h5 class="card-title">Investor Tech Support</h5>
                        <p class="card-text">Marketing</p>
                        <div class="icon-block">
                            <a href="#">
                                <i class="fa fa-linkedin"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-envelope-o"></i>
                            </a>
                            <a href="#">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <p class="mt-3 w-100 float-left text-center"><strong>marketing@snf.com</strong></p>
            </div>
        </div>
    </div>