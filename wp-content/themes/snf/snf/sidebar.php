<?php if(has_term('','markets')):?>
<?php $terms = get_the_terms(get_the_ID(), 'markets');?>
    <aside class="sidebar card ">
        <section class="sidebar-widget bordered-sidebar">
            <?php if( $terms ): ?>
                <?php foreach( $terms as $term ): ?>
	                <?php $icon = get_field('hero_image', $term->taxonomy . '_' . $term->term_id);?>
	                <?php $marketRedirectURL = get_field('page_url', 'category_'.$term->term_id); ?>
	                <?php $breadcrumbsColor = get_field('color_scheme', $term->taxonomy . '_' . $term->term_id);?>
	                <div class="card-body sidebar-market-contact "style="background-image:url('<?php echo $icon['url']; ?>');">
	                    <div class="sidebar-overlay" style="background: linear-gradient(167deg, rgba(0,45,115,1) 0%, <?php echo $breadcrumbsColor;?> 51%);"></div>
	                    <div class="markets-contact-content">
	                        <h3>Contact SNF <?php echo $term->name;?> Today!</h3>
	                        <a class="btn btn btn-outline-light" href="<?php echo $marketRedirectURL ;?>/contact">
	                            Let's Connect!
	                        </a>
	                    </div>
	                </div>
				<?php endforeach; ?>
			<?php else:?>
            <?php endif; ?>
        </section>
        <section class="sidebar-widget">
            <div class="heading "><h4 class="display-5">Sustainability</h4></div>
            <div class="card-body">
                <?php if( $terms ): ?>
                    <?php foreach( $terms as $term ): ?>
                        <?php if ( have_rows( 'sdg' ,$term->taxonomy . '_' . $term->term_id) ) : ?>
                            <div class=" photos">
                                <?php while ( have_rows( 'sdg' ,$term->taxonomy . '_' . $term->term_id) ) : the_row(); ?>
                                    <?php $sdg_Icon = get_sub_field('icon', $term->taxonomy . '_' . $term->term_id);?>
                                    <?php $sdgTitle = get_sub_field('title', $term->taxonomy . '_' . $term->term_id );?>
                                    <div class="item ">
                                        <a href="<?php echo get_sub_field('icon', $term->taxonomy . '_' . $term->term_id) ?>" data-lightbox="<?php echo $term->slug;?>">
                                            <figure class="figure">
                                                <img  src="<?php echo get_sub_field('icon', $term->taxonomy . '_' . $term->term_id) ?>" style="" alt="<?php echo $sdgTitle;?>" class="img-fluid mb-1 mr-1 img-fluidrounded " />
                                                <figcaption class="figure-caption"><?php echo $sdgTitle;?></figcaption>
                                            </figure>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else:?>
                <?php endif; ?>
            </div>
        </section>
        <section class="sidebar-widget">
            <div class="heading "><h4 class="display-5">Related Posts</h4></div>
            <div class="col-xs-12 card-body">
             
                <?php foreach( $terms as $term ): ?>
                    <?php $args = array('post_type' => 'global-communication','posts_per_page'=>4,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'markets',
                                'field' => 'slug',
                                'terms' => $term->slug,
                            ),
                        ),
                    );
                    $loop = new WP_Query($args);
                    if($loop->have_posts()) {
                        echo '<ul class="list-group">';
                        while($loop->have_posts()) : $loop->the_post();
                            echo '<li class="list-group-item"><a href="'.get_permalink().'" title="'.get_the_title().'" target="_blank">'.get_the_title().'</a></li>';
                        endwhile;
                        echo "</ul>";
                    }
                    ;?>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="sidebar-widget sidebar-search">
            <div class="heading "><h4 class="display-5">Search</h4></div>
            <div class="form-group col-xs-12">
                <?php $search_terms = htmlspecialchars( $_GET["s"] ); ?>
                <form role="form" action="<?php bloginfo('siteurl'); ?>/" id="searchform" method="get">
                    <div class="inner-addon right-addon">
                        <i class="glyphicon glyphicon-search"></i>
                        <input type="text" class="form-control"   name="s" id="s" name="s" placeholder="Search"<?php if ( $search_terms !== '' ) { echo ' value="' . $search_terms . '"'; } ?> />
                    </div>
                </form>
            </div>
        </section>
    </aside>
<?php else:?>
    <aside class="sidebar ">
        <section class="sidebar-widget bordered-sidebar">

        </section>
    </aside>
<?php endif;?>
