<?php $accord_count = 0; $Acc_ID = uniqid(); ?>
	<?php if (get_sub_field( 'accordion_width' ) == 'full-width' ) : ?>
        <?php if( have_rows('accordion_repeater') ):
        $row_count = 0; $accord_count++;?>
        <div class="subsid-accordion  " id="accordion_<?php echo $Acc_ID;?>">
            <?php // loop through the rows of data for the tab header
            while ( have_rows('accordion_repeater') ) : the_row(); $row_count++;
                $header = get_sub_field('accordion_title');
                $content = get_sub_field('accordion_content');
                $expand = get_sub_field( 'expand_accordion' );
                ?>
            <div class="card">
                <div class="card-header bg-white shadow-sm border-0" id="heading_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>">
                    <h5 class="mb-0  ">
                        <a class="d-block position-relative text-dark text-uppercase collapsible-link py-2 subsididary-collapse" data-toggle="collapse" data-target="#collapse_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>" aria-expanded="<?php if($expand == 1){ echo 'true';}else{ echo 'false';} ?>" aria-controls="collapse_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>">
                            <?php echo $header; ?>
                        </a>
                    </h5>
                </div><!--card-header-->
                <div id="collapse_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>" class="collapse shadow-sm <?php if($expand == 1){ echo 'show';}else{ echo ' ';} ?>" aria-labelledby="heading_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>" data-parent="#accordion_<?php echo $Acc_ID; ?>">
                    <div class="card-body">
                        <?php echo $content; ?>
                    </div><!--card-body-->
                </div>
            </div><!--card-->
        <?php // Increment the increment variable
        endwhile; //End the loop
        echo '</div>'; //subsid-accordion
        else :
        endif;?>

    <?php else:?>
        <?php if (get_sub_field( 'accordion_width' ) == 'column' ) : ?>
            <div class="row row-cols-3 align-items-center column  justify-content-md-center flexible-card mb-5 mt-2">
                <?php $row_count = 0; $accord_count++;?>
                <?php if( have_rows('accordion_repeater') ):?>
                    <?php while ( have_rows('accordion_repeater') ) : the_row(); $row_count++; ?>
                    <?php $header = get_sub_field('accordion_title'); $content = get_sub_field('accordion_content'); ?>
                        <div class=" col subsid-accordion bye h-100 " id="accordion_<?php echo $Acc_ID;?>">
                            <div class=" card">
                                <div class="card-header bg-white shadow-sm border-0" id="heading_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>">
                                    <h5 class="mb-0  ">
                                        <a class="d-block position-relative text-dark text-uppercase collapsible-link py-2 subsididary-collapse" data-toggle="collapse" data-target="#collapse_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>" aria-expanded="<?php if($expand == 1){ echo 'true';}else{ echo 'false';} ?>" aria-controls="collapse_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>">
                                            <?php echo $header; ?>
                                        </a>
                                    </h5>
                                </div><!--card-header-->
                                <div id="collapse_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>" class="collapse shadow-sm" aria-labelledby="heading_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>" data-parent="#accordion_<?php echo $Acc_ID; ?>">
                                    <div class="card-body">
                                        <?php echo $content; ?>
                                    </div><!--card-body-->
                                </div>
                            </div><!--card-->
                        </div>
                    <?php endwhile;  ?>
                <?php endif;?>
            </div>
        <?php endif;?>
    <?php endif;?>
