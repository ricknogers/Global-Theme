<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/6/18
 * Time: 10:42
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ( $logs ) : ?>
	<div id="pda-log-viewer-select">
		<div class="alignleft">
			<h2>
				<?php echo esc_html( $viewed_log ); ?>
				<?php if ( ! empty( $handle ) ) : ?>
					<a class="page-title-action"
					   href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'handle' => $handle, 'action' => 'download' ), admin_url( 'index.php?pda_log=download_logs' ) ), 'download_log' ) ); ?>"
					   class="button"><?php esc_html_e( 'Download log', 'prevent-direct-access-gold' ); ?></a>
				<?php endif; ?>
			</h2>
		</div>
		<div class="alignright">
			<form action="<?php echo esc_url( admin_url( 'admin.php?page=pda-status&tab=logs' ) ); ?>"
			      method="post">
				<select autocomplete="off" name="log_file">
					<?php foreach ( $logs as $log_key => $log_file ) : ?>
						<?php
						$timestamp = filemtime(  trailingslashit( PDA_LOG_DIR ) . $log_file );
						/* translators: 1: last access date 2: last access time */
						$date = sprintf( __( '%1$s at %2$s', 'prevent-direct-access-gold' ), date_i18n( get_option( 'date_format' ), $timestamp ), date_i18n( get_option( 'date_format' ), $timestamp ) );
						?>
						<option value="<?php echo esc_attr( $log_key ); ?>" <?php selected( sanitize_title( $viewed_log ), $log_key ); ?>><?php echo esc_html( $log_file ); ?>
							(<?php echo esc_html( $date ); ?>)
						</option>
					<?php endforeach; ?>
				</select>
				<button type="submit" class="button"
				        value="<?php esc_attr_e( 'View', 'prevent-direct-access-gold' ); ?>"><?php esc_html_e( 'View', 'prevent-direct-access-gold' ); ?></button>
			</form>
		</div>
		<div class="clear"></div>
	</div>
	<div id="pda-log-viewer">
		<pre><?php echo self::massage_log_contents( file_get_contents( trailingslashit( PDA_LOG_DIR ) . $viewed_log ) ); ?></pre>
	</div>
<?php else : ?>
	<div class="updated pda-message inline"><p><?php esc_html_e( 'There are currently no logs to view.', 'prevent-direct-access-gold' ); ?></p></div>
<?php endif; ?>