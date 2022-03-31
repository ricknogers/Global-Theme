<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 6/28/18
 * Time: 10:48
 * @package
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface PDA_Log_Handler_Interface {
	/***
	 * Handle a log entry
	 *
	 * @param int $timestamp Log timestamp
	 * @param string $level emergency|alert|critical|error|warning|notice|info|debug
	 * @param $message Log message
	 * @param $context Additional information for log handlers
	 *
	 * @return bool False if value was not handled or true if value was handled.
	 */
	public function handle( $timestamp, $level, $message, $context );
}