<div class="card">
    <div class="card-body archive-content">
        <div class="archive-header">
            <h4 class="card-title"><?php the_title(); ?></h4>
        </div>
        <p class="card-text"><?php the_excerpt(); ?></p>
        <p class="text-muted"><?php echo get_the_date('j M, Y');?></p>

        <a href="<?php the_permalink();?>" class="btn btn-primary">Read More</a>
    </div>
</div>