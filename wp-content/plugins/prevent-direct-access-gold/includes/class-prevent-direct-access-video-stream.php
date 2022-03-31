<?php
	/**
	 * Reference here: https://gist.github.com/ranacseruet/9826293
	 * Created by PhpStorm.
	 * User: gaupoit
	 * Date: 6/29/18
	 * Time: 14:38
	 */

class PDA_Video_Stream
{
	private $path = "";
	private $stream = "";
	private $buffer = 1024;
	private $start = -1;
	private $end = -1;
	private $size = 0;

	public function __construct( $filePath ) {
		$this->path = $filePath;
	}

	/**
	 * Open stream
	 */
	private function open() {
		if ( ! $this->stream = fopen( $this->path, 'rb' ) ) {
			die( 'Could not open stream for reading' );
		}
	}

	/**
	 * Set streaming header
	 *
	 * @param string $file_type File type
	 */
	private function setHeader( $file_type ) {
		ob_get_clean();
		header( 'X-Robots-Tag: none' );
		header("Content-Type: $file_type");
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header("Expires: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT');
		header("Last-Modified: ".gmdate('D, d M Y H:i:s', @filemtime($this->path)) . ' GMT' );
		$this->start = 0;
		$this->size  = filesize($this->path);
		$this->end   = $this->size - 1;
		header( "Accept-Ranges: 0-" . $this->end );
		if ( isset(  $_SERVER['HTTP_RANGE'] ) ) {
			$c_start = $this->start;
			$c_end = $this->end;

			list( , $range ) = explode( '=', $_SERVER['HTTP_RANGE'], 2 );
			if (strpos($range, ',') !== false) {
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				header("Content-Range: bytes $this->start-$this->end/$this->size");
				exit;
			}
			if ($range == '-') {
				$c_start = $this->size - substr($range, 1);
			}else{
				$range = explode('-', $range);
				$c_start = $range[0];

				$c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $c_end;
			}
			$c_end = ($c_end > $this->end) ? $this->end : $c_end;
			if ($c_start > $c_end || $c_start > $this->size - 1 || $c_end >= $this->size) {
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				header("Content-Range: bytes $this->start-$this->end/$this->size");
				exit;
			}
			$this->start = $c_start;
			$this->end = $c_end;
			$length = $this->end - $this->start + 1;
//			fseek($this->stream, $this->start);
			header('HTTP/1.1 206 Partial Content');
			header("Content-Length: ".$length);
			header("Content-Range: bytes $this->start-$this->end/".$this->size);
		} else {
			header("Content-Length: ".$this->size);
		}
	}

	/**
	 * Close currently opening stream
	 */
	private function end() {
		fclose( $this->stream );
		exit;
	}

	private function stream() {
		$i = $this->start;
		set_time_limit(0);
		while(!feof($this->stream) && $i <= $this->end) {
			$bytesToRead = $this->buffer;
			if( ($i+$bytesToRead) > $this->end ) {
				$bytesToRead = $this->end - $i + 1;
			}
			$data = @stream_get_contents($this->stream, $bytesToRead, $i);
			echo $data;
			if ( ob_get_level() > 0 ) {
				ob_flush();
			}
			flush();
			$i += $bytesToRead;
		}
	}

	public function start( $file_type ) {
		$this->open();
		$this->setHeader( $file_type );
		$this->stream();
		$this->end();
	}

}
