<div class="row">
    <div class="col page-header theme-bg-dark py-5 text-center position-relative default-selection">
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
</div>