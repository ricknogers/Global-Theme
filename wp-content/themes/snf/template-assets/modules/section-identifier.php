
<?php if(get_sub_field('secondary_title') && get_sub_field('primary_title')):?>
    <div class="section-identifier two-titles ">
        <h2><?php the_sub_field( 'primary_title' ); ?><span><?php the_sub_field( 'secondary_title' ); ?></span></h2>
    </div>
<?php else:?>
    <div class="section-identifier one-title ">
        <h2><?php the_sub_field( 'primary_title' ); ?></h2>
    </div>
<?php endif;?>