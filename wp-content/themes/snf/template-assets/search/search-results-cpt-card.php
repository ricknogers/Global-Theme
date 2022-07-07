<?php $type = get_post_type(); ?>
<?php $post_type = get_post_type_object($post->post_type);?>
<div class="card mb-3 mt-3 shadow p-1  bg-transparent">
    <div class="card-body archive-content ">
        <div class="archive-header">
            <h4 class="card-title display-4"><?php the_title(); ?></h4>
        </div>

	    <?php if ( have_rows( 'card_repeater' ) ) : ?>
		    <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
			    <?php if(snf_custom_excerpt(get_sub_field('content'))):?>
				    <?php echo snf_custom_excerpt(get_sub_field('content'));?>

			    <?php endif;?>

		    <?php endwhile; wp_reset_postdata(); ?>
	    <?php endif;?>
        <?php if(custom_field_excerpt()):?>
            <p class="card-text text-black-50 lead"><?php echo custom_field_excerpt();; ?></p>
        <?php else:?>
            <p class="card-text text-black-50 lead"><?php the_excerpt();; ?></p>
        <?php endif;?>
        <?php if($type == 'global-communication'):?>
        <p class="text-muted"><?php echo get_the_date('j M, Y');?></p>
        <?php endif;?>
    </div>
    <div class="card-footer bg-transparent border-top border-dark text-muted">
	    <?php if ( get_field( 'file' ) ) : ?>
            <a class="card-link mr-2" href="<?php the_field( 'file' ); ?>"><i class="bi bi-filetype-pdf"></i>Download File</a>
	    <?php endif; ?>
	    <?php $url_redirect = get_field( 'url_redirect' ); ?>
	    <?php if ( $url_redirect ) : ?>
            <a class="card-link" href="<?php echo esc_url( $url_redirect ); ?>">Learn More</a>
	    <?php else:?>
            <a class="card-link" href="<?php the_permalink();?>">Learn More <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                </svg></a>
	    <?php endif; ?>
        <div class="float-right">
		    <?php $post_type = get_post_type_object($post->post_type);?>
            <p class="text-sm-left text-md-right text-muted"><small><?php echo $post_type->labels->singular_name;?></small></p>
        </div>

    </div>
</div>