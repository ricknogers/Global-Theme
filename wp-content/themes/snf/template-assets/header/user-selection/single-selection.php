<div class="row">
	<div class="col page-header  py-2 shadow-sm position-relative">
		
		<?php if(is_page() || is_singular()):?>
			<h1><?php the_title();?></h1>
		<?php else:?>
			<?php get_template_part('template-assets/header/conditional-headers/archive-title-headers');?>
		<?php endif;?>
			
	</div>
</div><!--row-->
<div class="row">
	<div class="col breadcrumbs-container ">
		<div class="card shadow p-1">
			<div class="card-body shadow-sm snf-breadcrumbs ">
				<div class="col second-tier-nav crumbs">
					<?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
				</div>

			</div><!--card-body shadow-sm snf-breadcrumbs-->
		</div><!--card shadow-->
	</div><!--breadcrumbs-container-->
</div><!--row-->