<div class="row">
	<div class="col page-header  py-2 shadow-sm position-relative ">
	    
	        <?php if(!is_page()):?>
	        	<?php if ( !$pagename && $id > 0 ) {
				  // If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
				  $post = $wp_query->get_queried_object();
				  $pagename = $post->post_name;
				} ?>
	        <?php else:?>
	        	            <h1><?php the_title();?></h1>

	        <?php endif;?>
	       
		   
		
 <h1><?php echo $pagenam;?></h1>
	</div>
</div><!--row-->
<div class="row">
	<div class="col breadcrumbs-container single-selection">
		<div class="card shadow p-1">
			<div class="card-body shadow-sm snf-breadcrumbs ">
				<div class="col second-tier-nav crumbs">
					<?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
				</div>

			</div><!--card-body shadow-sm snf-breadcrumbs-->
		</div><!--card shadow-->
	</div><!--breadcrumbs-container-->
</div><!--row-->