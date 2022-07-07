<div class="row">
    <div class="col page-header theme-bg-dark py-md-5 text-center position- optimized-selection  ">
        <div class="page-header-shapes-right "></div>
        <div class="page-header-shapes-left"></div>
        <div class="row">
            <div class="col page-header-title" >
                <?php if(is_archive()):?>
                    <?php echo '<h1 class="archive-title">';?>
                    <?php echo single_cat_title();?>
                    <?php echo '</h1>';?>
                <?php else:?>

	            	<h1><?php the_title();?></h1>
	            <?php endif;?>
            </div>
        </div>
    </div>
</div>
