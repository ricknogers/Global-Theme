<?php if ( have_rows( 'split_layout_repeater' ) ) : ?>
    <?php while ( have_rows( 'split_layout_repeater' ) ) : the_row(); ?>
        <?php if ($counter % 2 === 0) :?>
	        <div class="row split-spacing py-2 mb-2 highlight-section align-items-center">
	            <div class="content-column col-md-6 col-sm-12 col-xs-12">
	                <div class="inner-column left">
	                    <div class="sec-title"><?php $title = get_sub_field('title');?>
	                        <?php  $term_array = array();
	                        $term_list = wp_get_post_terms($post->ID, 'markets', array(
	                                "fields" => "all",
	                                'orderby' => 'parent',
	                                'order' => 'ASC'
	                            )
	                        );
	                        foreach($term_list as $term_single) {
	                            $term_array[] = $term_single->name ; //do something here
	                        }
	                        ?>
		                    <?php if($term_list):?>
			                    <?php foreach($term_list as $term) :?>
                                    <div class="title"><?php echo $term->name;?></div>
			                    <?php endforeach; ?>
		                    <?php endif;?>
                            <h2 class="title"><?php echo $title; ?></h2>
	                    </div>
	                    <div class="text">
								<?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
                                    <p><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>
                                <?php else:?>
                                	<?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
	                                	<p><?php the_sub_field('content');?></p>
	                                <?php endif;?>
                                <?php endif;?>
								<?php $link = get_sub_field( 'link' ); ?>
                            <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url( $link) ; ?>" class="theme-btn btn-style-three">Read More</a>
                            <?php else:?>
                            <?php endif; ?>
                        </div>
	                </div>
	            </div>
	            <!--Image Column-->
	            <div class="image-column col-md-6 col-sm-12 col-xs-12">
	                <div class="inner-column ">
	                    <div class="image w-100 shadow">
	                        <?php if ( get_sub_field( 'image' ) ) : ?>
	                            <img class="projcard-img img-fluid" src="<?php the_sub_field( 'image' ); ?>" />
	                        <?php endif ?>
	                    </div>
	                </div>
	            </div>
	        </div>
        <?php else:?>
            <div class="row split-spacing pt-3 pb-3 highlight-section align-items-center" id="">
                <div class="image-column col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-column " >
                        <div class="image w-100 shadow">
                            <?php if ( get_sub_field( 'image' ) ) : ?>
                                <img class="projcard-img img-fluid" src="<?php the_sub_field( 'image' ); ?>" />
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="content-column col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-column right">
                        <div class="sec-title"><?php $title = get_sub_field('title');?>
                            <?php  $term_array = array();
                            $term_list = wp_get_post_terms($post->ID, 'markets', array(
                                    "fields" => "all",
                                    'orderby' => 'parent',
                                    'order' => 'ASC'
                                )
                            );
                            foreach($term_list as $term_single) {
                                $term_array[] = $term_single->name ; //do something here
                            }
                            ?>
                            <?php if($term_list):?>
                                <?php foreach($term_list as $term) :?>
                                    <div class="title"><?php echo $term->name;?></div>
                                <?php endforeach; ?>
                            <?php endif;?>
                            <h2 class="title"><?php echo $title; ?></h2>
                        </div>
                        <div class="text">
	                        <?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
                                <p><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>
	                        <?php else:?>
		                        <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
                                    <p><?php the_sub_field('content');?></p>
		                        <?php endif;?>
	                        <?php endif;?>
                            <?php $link = get_sub_field( 'link' ); ?>
                            <?php if ( $link ) : ?>
                                <a href="<?php echo esc_url( $link) ; ?>" class="theme-btn btn-style-three">Read More</a>
                            <?php else:?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php $counter++; ?>
    <?php endwhile; ?>
<?php else : ?>
    <?php // No rows found ?>
<?php endif; ?>