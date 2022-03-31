<section>
    <i class="fa fa-tags" aria-hidden="true"></i>

    <?php   // Get terms for post
    // $terms = get_the_terms( $post->ID , 'country' );
    // Loop over each item since it's an array
    //foreach ( $terms as $term ) :?>
        <?php // $termlinks = get_term_link($term);?>
      <a href="<?php echo $termlinks ;?>" class="badge badge-tag btn-outline-primary ">
            <?php // echo $term->name;?>
        </a>
    <?php // endforeach;?>
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
        <a href="<?php echo home_url('/')?>industries/<?php echo $term->slug;?>" class="badge badge-tag markets">
            <?php echo $term->name;?>
        </a>
    <?php }
    ?>
</section>