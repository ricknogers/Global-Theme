 <div class=" pt-3 pb-3  " id="numeric-results">
                
     
	<?php $search=get_search_query(); global $wp_query;global $post;?>
	<?php if ( have_posts() && ($search != null || is_search()) || $post->post_content != '' ) :?>
	    <div class="page-header theme-bg-dark py-5 text-center position-relative search-results">
	        <div class="page-header-shapes-right "></div>
	        <div class="page-header-shapes-left"></div>
	        <div class="align-items-center justify-content-center d-flex">
	            <div class="col-8 page-header-title" >
	                <h1 class="display-4">Search Results for: <?php echo get_search_query();?></h1>
	            </div>
	            <div class="col  results-numbered-output" >
	                <?php
	                $first_post = $wp_query->post_count;
	                $last_post = $first_post + $wp_query->post_count;
	                $all_posts = $wp_query->found_posts;
	                ?>
	                <p class="small text-uppercase text-white">Showing <?php echo $first_post ?> of <strong><?php echo $all_posts; ?> results</strong></p>
	            </div>
	        </div>
	    </div>
	
	<?php endif; ?>
</div>