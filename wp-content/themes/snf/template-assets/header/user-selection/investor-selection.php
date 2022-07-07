<?php if(  is_user_logged_in()):?>
	<div class="row">
		<div class="col page-header theme-bg-dark py-md-4 text-center position-relative">
			<div class="page-header-shapes-right "></div>
			<div class="page-header-shapes-left"></div>
			<div class="row">
				<div class="col page-header-title" >
					<h1><?php the_title();?></h1>
				</div>
			</div>
		</div>
	</div>
	<?php get_template_part('/template-assets/header/user-selection/breadcrumbs');?>
	<?php
	$args = array(
		'post_type' =>  'document',
		'posts_per_page' => 1,
		'order' => 'ACS',
		'orderby'=> 'DATE',
		'tax_query' => array(
			array(
				'taxonomy' => 'investors',
				'field' => 'slug',
				'terms' => 'upcoming-events',
			),
		),
	);
	$query = new WP_Query( $args );?>
	<?php if(!empty($query)):?>
		<?php if ( $query->have_posts() ) : ?>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<div class="row">
					<div class="col-sm-12 investor-update">
						<blockquote class="blockquote text-center">
							<section class="investor-overlay">
								<?php the_content();?>
							</section>
						</blockquote>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata();?>
		<?php endif;?>
	<?php endif;?>
<?php endif;?>
