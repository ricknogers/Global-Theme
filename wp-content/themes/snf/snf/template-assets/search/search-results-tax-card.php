<div class="card mb-3 mt-3 shadow p-1  bg-transparent">
    <div class="card-body archive-content ">
        <div class="archive-header">
            <h4 class="card-title"><?php the_title(); ?></h4>
        </div>
        <div class="tag-cloud">
            <section>
                <i class="fa fa-tags" aria-hidden="true"></i>
                <?php   // Get terms for post
                $terms = get_the_terms( $post->ID , 'country' );
                // Loop over each item since it's an array
                foreach ( $terms as $term ) {?>
                    <?php $termlinks = get_term_link($term);?>
                    <a href="<?php echo $termlinks ;?>" class="badge badge-tag country">
                        <?php echo $term->name;?>
                    </a>
                <?php }
                ?>
                <?php   // Get terms for post
                $terms = get_the_terms( $post->ID , 'snf-communication-types' );
                // Loop over each item since it's an array
                foreach ( $terms as $term ) {?>
                    <?php $termlinks = get_term_link($term);?>
                    <a href="<?php echo $termlinks ;?>" class="badge badge-tag comm-types">
                        <?php echo $term->name;?>
                    </a>
                <?php }
                ?>
                <?php   // Get terms for post
                $terms = get_the_terms( $post->ID , 'markets' );
                // Loop over each item since it's an array
                foreach ( $terms as $term ) {?>
                    <?php $termlinks = get_term_link($term);?>
                    <a href="<?php echo $termlinks ;?>" class="badge badge-tag markets">
                        <?php echo $term->name;?>
                    </a>
                <?php }
                ?>
            </section>
        </div>
        <?php if(custom_field_excerpt()):?>
            <p class="card-text text-black-50 lead"><?php echo custom_field_excerpt();; ?></p>
        <?php else:?>
            <p class="card-text text-black-50 lead"><?php the_excerpt();; ?></p>
        <?php endif;?>
        <p class="text-muted"><?php echo get_the_date('j M, Y');?></p>
    </div>
    <div class="card-footer bg-transparent border-top border-dark text-muted">
        <?php if ( get_field( 'file' ) ) : ?>
            <a class="card-link mr-2" href="<?php the_field( 'file' ); ?>"><i class="bi bi-filetype-pdf"></i>Download File</a>
        <?php endif; ?>
        <?php $url_redirect = get_field( 'url_redirect' ); ?>
        <?php if ( $url_redirect ) : ?>

            <a class="card-link" href="<?php echo esc_url( $url_redirect ); ?>">Learn More <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                </svg></a>
        <?php else:?>
            <a class="card-link" href="<?php the_permalink();?>">Learn More <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
                </svg></a>
        <?php endif; ?>

    </div>
</div>