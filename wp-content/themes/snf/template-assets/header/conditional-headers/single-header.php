<div class="row">
    <div class="col page-header theme-bg-dark py-5 text-center position-relative">
        <div class="page-header-shapes-right "></div>
        <div class="page-header-shapes-left"></div>
        <div class="row">
            <div class="col page-header-title" >
	            <?php if(is_page()):?>
                    <h1><?php the_title();?></h1>
	            <?php else:?>
		            <?php get_template_part('template-assets/header/conditional-headers/archive-title-headers');?>
	            <?php endif;?>
            </div>
        </div>
    </div>
</div><!--row-->
<div class="row">
    <div class="col breadcrumbs-container ">
        <div class="card shadow p-1">
            <div class="card-body shadow-sm snf-breadcrumbs ">
                <div class="col second-tier-nav crumbs">
                    <?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                </div>
                <?php if(is_tree('102')):?>
                    <div class="col second-tier-nav subsidiary-children-pages">
                        <ul class="list-inline">
                            <?php $args = [
                                'child_of' => $post->ID,
                                'title_li' => '',
                            ];
                            wp_list_pages( $args ); ?>
                        </ul>
                    </div>
                <?php endif;?>
            </div><!--card-body shadow-sm snf-breadcrumbs-->
        </div><!--card shadow-->
    </div><!--breadcrumbs-container-->
</div><!--row-->