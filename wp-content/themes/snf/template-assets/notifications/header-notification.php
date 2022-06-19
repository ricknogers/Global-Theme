<?php if ( get_field( 'enable_notifications_banner_alert_for_this_page' ) == 1 ) : ?>
	<div class="alert alert-info alert-dismissible d-none d-sm-block" role="alert">
		<button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button>
		<?php if ( have_rows( 'header_notifications_and_alerts' ) ) : ?>
			<?php while ( have_rows( 'header_notifications_and_alerts' ) ) : the_row(); ?>
                <div class="d-inline-flex justify-content-center align-items-center">
                    <h4><?php the_sub_field( 'title' ); ?></h4>


                    <?php $link = get_sub_field( 'button_link' ); ?>
                    <?php if ( $link ) :
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';?>
                        <a href="<?php echo esc_url( $link_url) ; ?>" target="<?php echo esc_attr( $link_target ); ?>">
                            <button class=" btn btn-sm btn-outline-light"><?php the_sub_field( 'button_text' ); ?></button>
                        </a>
                    <?php endif; ?>
                </div>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>

<?php else : ?>
	<?php // echo 'false'; ?>
<?php endif; ?>
