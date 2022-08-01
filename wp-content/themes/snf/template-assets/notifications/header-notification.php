<?php
$format_in = 'Ymd'; // the format your value is saved in (set in the field options)
$format_out = 'Y-m-d';
$expire_date = DateTime::createFromFormat($format_in, get_field('date_for_notification_to_expire'));
$current_date = date('Y-m-d');
?>

    <?php if ( get_field( 'enable_notifications_banner_alert_for_this_page' ) == 1 ) : ?>
	    <?php if($current_date <   $expire_date->format( $format_out )  ):?>
            <div class="alert alert-info alert-dismissible d-none d-sm-block border-0" role="alert">
                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                    </svg></button>
                <?php if ( have_rows( 'header_notifications_and_alerts' ) ) : ?>
                    <?php while ( have_rows( 'header_notifications_and_alerts' ) ) : the_row(); ?>
                        <div class="d-inline-flex justify-content-center align-items-center">
                            <h4><?php the_sub_field( 'title' ); ?></h4>
                            <?php $link = get_sub_field( 'button_link' ); ?>
                            <?php if ( $link ) :?>
                            <a href="<?php echo $link['url'];?>"  target="<?php echo esc_attr( $link_target ); ?>">
                                <button class=" btn btn-sm btn-outline-light"><?php the_sub_field( 'button_text' ); ?></button>
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <?php // echo 'false'; ?>
    <?php endif; ?>
