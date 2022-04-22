<div class="row">
    <div class="col page-header theme-bg-dark py-5 text-center position- optimized-selection">
        <div class="page-header-shapes-right "></div>
        <div class="page-header-shapes-left"></div>
        <div class="row">
            <div class="col page-header-title" >
	          
	            	<h1><?php the_title();?></h1>
	            
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col breadcrumbs-container optimized-selection">
        <div class="card shadow p-1">
            <div class="card-body shadow-sm snf-breadcrumbs ">
                <div class="col  crumbs">
					<?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                </div>

            </div><!--card-body shadow-sm snf-breadcrumbs-->
        </div><!--card shadow-->
    </div><!--breadcrumbs-container-->
</div><!--row-->