
<div class="row">
    <div class="col ox">
		<?php if(get_field('secondary_title')):?>
            <div class="section-identifier two-titles">
                <h1><?php the_title();?><span><?php the_field( 'secondary_title' ); ?></span></h1>
            </div>
		<?php else:?>
            <div class="section-identifier one-title">
                <h1><?php the_title();?></h1>
            </div>
		<?php endif; ?>
    </div><!-- pageTitleOverlay-->
</div>
