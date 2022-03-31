<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle log entries by writing to a file
 *
 * Class PDA_Log_Handler_file
 */
class PDA_Log_Handler_file extends PDA_Log_Handler {
	/**
	 * Store open file handles.
	 *
	 * @var array
	 */
	protected $handles = array();
	/**
	 * File size limit for log files in bytes.
	 *
	 * @var int
	 */
	protected $log_size_limit;
	/**
	 * Cache logs that could be written
	 *
	 * @var array
	 */
	protected $cached_logs = array();
	/**
	 * Maximum number of historical logs
	 *
	 * @var int
	 */
	protected $max_historical_history = 8;
	/**
	 * PDA_Log_Handler_file constructor.
	 *
	 * @param int|null $log_size_limit Log file's size limit.
	 */
	public function __construct( $log_size_limit = null ) {
		if ( null === $log_size_limit ) {
			$log_size_limit = 5 * 1024 * 1024;
		}
		$this->log_size_limit = apply_filters( 'pda_log_file_size_limit', $log_size_limit );
		add_action( 'plugins_loaded', array( $this, 'write_cached_logs' ) );
	}
	/**
	 * Destructor.
	 */
	public function __destruct() {
		foreach ( $this->handles as $handle ) {
			if ( is_resource( $handle ) ) {
				fclose( $handle ); // @codingStandardsIgnoreLine.
			}
		}
	}
	/**
	 * Handle a log entry.
	 *
	 * @param int    $timestamp Log timestamp.
	 * @param string $level emergency|alert|critical|error|warning|notice|info|debug.
	 * @param string $message Log message.
	 * @param array  $context {
	 *      Additional information for log handlers.
	 *
	 *     @type string $source Optional. Determines log file to write to. Default 'log'.
	 *     @type bool $_legacy Optional. Default false. True to use outdated log format
	 *         originally used in deprecated WC_Logger::add calls.
	 * }
	 *
	 * @return bool False if value was not handled and true if value was handled.
	 */
	public function handle( $timestamp, $level, $message, $context ) {
		if ( isset( $context['source'] ) && $context['source'] ) {
			$handle = $context['source'];
		} else {
			$handle = 'log';
		}
		$entry = self::format_entry( $timestamp, $level, $message, $context );
		return $this->add( $entry, $handle );
	}
	/**
	 * Write cached logs.
	 */
	public function write_cached_logs() {
		foreach ( $this->cached_logs as $log ) {
			$this->add( $log['entry'], $log['handle'] );
		}
	}
	/**
	 * Builds a log entry text from timestamp, level and message.
	 *
	 * @param int    $timestamp Log timestamp.
	 * @param string $level emergency|alert|critical|error|warning|notice|info|debug.
	 * @param string $message Log message.
	 * @param array  $context Additional information for log handlers.
	 *
	 * @return string Formatted log entry.
	 */
	protected static function format_entry( $timestamp, $level, $message, $context ) {
		if ( isset( $context['_legacy'] ) && true === $context['_legacy'] ) {
			if ( isset( $context['source'] ) && $context['source'] ) {
				$handle = $context['source'];
			} else {
				$handle = 'log';
			}
			$message = apply_filters( 'pda_logger_add_message', $message, $handle );
			$time    = date_i18n( 'm-d-Y @ H:i:s' );
			$entry   = "{$time} - {$message}";
		} else {
			$entry = parent::format_entry( $timestamp, $level, $message, $context );
		}
		return $entry;
	}
	/**
	 * Add a log entry to chosen file.
	 *
	 * @param string $entry Log entry text.
	 * @param string $handle Log entry handle.
	 *
	 * @return bool True if write was successful.
	 */
	protected function add( $entry, $handle ) {
		$result = false;
		if ( $this->should_rotate( $handle ) ) {
			$this->log_rotate( $handle );
		}
		if ( $this->open( $handle ) && is_resource( $this->handles[ $handle ] ) ) {
			$result = fwrite( $this->handles[ $handle ], $entry . PHP_EOL ); // @codingStandardsIgnoreLine.
		} else {
			$this->cache_log( $entry, $handle );
		}
		return false !== $result;
	}
	/**
	 * Open log file for writing
	 *
	 * @param string $handle Log handle.
	 * @param string $mode Optional. File mode. Default 'a'.
	 *
	 * @return bool Success.
	 */
	protected function open( $handle, $mode = 'a' ) {
		if ( $this->is_open( $handle ) ) {
			return true;
		}
		$file = self::get_log_file_path( $handle );
		if ( $file ) {
			if ( ! file_exists( $file ) ) {
				$temphandle = @fopen( $file, 'w+' ); // @codingStandardsIgnoreLine.
				@fclose( $temphandle );  // @codingStandardsIgnoreLine.
				if ( defined( 'FS_CHMOD_FILE' ) ) {
					@chmod( $file, FS_CHMOD_FILE ); // @codingStandardsIgnoreLine.
				}
			}
			$resource = @fopen( $file, $mode ); // @codingStandardsIgnoreLine.
			if ( $resource ) {
				$this->handles[ $handle ] = $resource;
				return true;
			}
		}
		return false;
	}
	/**
	 * Check if a handle is open.
	 *
	 * @param string $handle Log handle.
	 * @return bool success
	 */
	protected function is_open( $handle ) {
		return array_key_exists( $handle, $this->handles ) && is_resource( $this->handles[ $handle ] );
	}
	/**
	 * Close a handle
	 *
	 * @param string $handle Log handle.
	 *
	 * @return bool sucess
	 */
	protected function close( $handle ) {
		$result = false;
		if ( $this->is_open( $handle ) ) {
			$result = @fclose( $this->handles[ $handle ] ); // @codingStandardsIgnoreLine.
			unset( $this->handles[ $handle ] );
		}
		return $result;
	}
	/**
	 * Get a log file path.
	 *
	 * @param string $handle Log name.
	 *
	 * @return bool|string The log file path or false if cannot be determined.
	 */
	public static function get_log_file_path( $handle ) {
		if ( function_exists( 'wp_hash' ) ) {
			return trailingslashit( PDA_LOG_DIR ) . self::get_log_file_name( $handle );
		} else {
			return false;
		}
	}
	/**
	 * Get a log file name.
	 *
	 * File name consist of the handle, followed by the date, followed by a hash and .log.
	 *
	 * @param string $handle Log name.
	 * @return bool|string The log file name or false if cannot be determined.
	 */
	public static function get_log_file_name( $handle ) {
		if ( function_exists( 'wp_hash' ) ) {
			$date_suffix = date( 'Y-m-d', current_time( 'timestamp', true ) );
			$hash_suffix = wp_hash( $handle );
			return sanitize_file_name( implode( '-', array( $handle, $date_suffix, $hash_suffix ) ) . '.log' );
		} else {
			return false;
		}
	}
	/**
	 * Check if the log file should be rotated.
	 *
	 * Compare the size of the log file to determine whether it is over the size limit.
	 *
	 * @param string $handle Log handle.
	 *
	 * @return bool True if it should be rotated.
	 */
	protected function should_rotate( $handle ) {
		$file = self::get_log_file_path( $handle );
		if ( $file ) {
			if ( $this->is_open( $handle ) ) {
				$file_stat = fstat( $this->handles[ $handle ] );
				return $file_stat['size'] > $this->log_size_limit;
			} elseif ( file_exists( $file ) ) {
				return filesize( $file ) > $this->log_size_limit;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	/**
	 * Rotate log files.
	 *
	 * Logs are rotated by prpending '.x' to the '.log' suffix.
	 * The current log plus 10 historical logs are maintained.
	 * For example:
	 *  hello.9.log -> [ Removed ]
	 *  hello.8.log -> hello.9.log
	 *  ...
	 *  hello.0.log -> hello.1.log
	 *  base.log    -> base.0.log
	 *
	 * @param string $handle Log handle.
	 */
	protected function log_rotate( $handle ) {
		for ( $i = $this->max_historical_history; $i >= 0; $i-- ) {
			$this->increment_log_infix( $handle, $i );
		}
		$this->increment_log_infix( $handle );
	}
	/**
	 * Increment a log file suffix.
	 *
	 * @param string   $handle Log handle.
	 * @param null|int $number Default null. Log suffix number to be incremented.
	 * @return bool True if increment was sucessful, otherwise false.
	 */
	protected function increment_log_infix( $handle, $number = null ) {
		if ( null === $number ) {
			$suffix      = '';
			$next_suffix = '.0';
		} else {
			$suffix      = ".{$number}";
			$next_suffix = '.' . ( $number + 1 );
		}
		$rename_from = self::get_log_file_path( "{$handle}{$suffix}" );
		$rename_to   = self::get_log_file_path( "{$handle}{$next_suffix}" );
		if ( $this->is_open( $rename_from ) ) {
			$this->close( $rename_from );
		}
		if ( is_writable( $rename_from ) ) { // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
			return rename( $rename_from, $rename_to ); // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_rename
		} else {
			return false;
		}
	}
	/**
	 * Cache log to write later
	 *
	 * @param string $entry Log entry text.
	 * @param string $handle Log entry handle.
	 */
	protected function cache_log( $entry, $handle ) {
		$this->cached_logs[] = array(
			'entry'  => $entry,
			'handle' => $handle,
		);
	}
	/**
	 * Delete all logs older than a defined timestamp.
	 *
	 * @since 3.4.0
	 * @param integer $timestamp Timestamp to delete logs before.
	 */
	public static function delete_logs_before_timestamp( $timestamp = 0 ) {
		if ( ! $timestamp ) {
			return;
		}
		$log_files = self::get_log_files();
		foreach ( $log_files as $log_file ) {
			$last_modified = filemtime( trailingslashit( PDA_LOG_DIR ) . $log_file );
			if ( $last_modified < $timestamp ) {
				@unlink( trailingslashit( PDA_LOG_DIR ) . $log_file ); // @codingStandardsIgnoreLine.
			}
		}
	}
	/**
	 * Get all log files in the log directory.
	 *
	 * @since 3.4.0
	 * @return array
	 */
	public static function get_log_files() {
		$files  = @scandir( PDA_LOG_DIR ); // @codingStandardsIgnoreLine.
		$result = array();
		if ( ! empty( $files ) ) {
			foreach ( $files as $key => $value ) {
				if ( ! in_array( $value, array( '.', '..' ), true ) ) {
					if ( ! is_dir( $value ) && strstr( $value, '.log' ) ) {
						$result[ sanitize_title( $value ) ] = $value;
					}
				}
			}
		}
		return $result;
	}
	/**
	 * Remove/delete the chosen file.
	 *
	 * @param string $handle Log handle.
	 *
	 * @return bool
	 */
	public function remove( $handle ) {
		$removed = false;
		$file    = trailingslashit( PDA_LOG_DIR ) . $handle;
		if ( $file ) {
			if ( is_file( $file ) && is_writable( $file ) ) { // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
				$this->close( $handle ); // Close first to be certain no processes keep it alive after it is unlinked.
				$removed = unlink( $file ); // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_unlink
			}
			do_action( 'pda_log_remove', $handle, $removed );
		}
		return $removed;
	}
	
	public function download( $handle ) {
		$file    = trailingslashit( PDA_LOG_DIR ) . $handle;
		if ( $file ) {
			if ( is_file ( $file ) && is_readable( $file ) ) { // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
				header('Content-Type: text/plain');
				header("Content-Disposition: attachment; filename=\"$handle\"");
				readfile( $file );
				exit();
			}
			do_action( 'pda_log_download', $handle );
		}
	}

}