<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );
class Import {
	private $handle = "";
	private $filepath = FALSE;
	private $column_headers = FALSE;
	private $initial_line = 0;
	private $delimiter = ",";
	private $detect_line_endings = FALSE;
	public

	function get_array( $filepath = FALSE, $column_headers = FALSE, $detect_line_endings = FALSE, $initial_line = FALSE, $delimiter = FALSE ) {
		ini_set( 'memory_limit', '20M' );

		if ( !$filepath ) {
			$filepath = $this->_get_filepath();
		} else {
			$this->_set_filepath( $filepath );
		}
		if ( !file_exists( $filepath ) ) {
			return FALSE;
		}
		if ( !$detect_line_endings ) {
			$detect_line_endings = $this->_get_detect_line_endings();
		} else {
			$this->_set_detect_line_endings( $detect_line_endings );
		}
		if ( $detect_line_endings ) {
			ini_set( "auto_detect_line_endings", TRUE );
		}
		if ( !$initial_line ) {
			$initial_line = $this->_get_initial_line();
		} else {
			$this->_set_initial_line( $initial_line );
		}
		if ( !$delimiter ) {
			$delimiter = $this->_get_delimiter();
		} else {
			$this->_set_delimiter( $delimiter );
		}
		if ( !$column_headers ) {
			$column_headers = $this->_get_column_headers();
		} else {
			$this->_set_column_headers( $column_headers );
		}
		$this->_get_handle();

		$row = 0;
		while ( ( $data = fgetcsv( $this->handle, 0, $this->delimiter ) ) !== FALSE ) {
			if ( $data[ 0 ] != NULL ) {
				if ( $row < $this->initial_line ) {
					$row++;
					continue;
				}
				if ( $row == $this->initial_line ) {
					if ( $this->column_headers ) {
						foreach ( $this->column_headers as $key => $value ) {
							$column_headers[ $key ] = trim( $value );
						}
					} else {
						foreach ( $data as $key => $value ) {
							$column_headers[ $key ] = trim( $value );
						}
					}
				} else {
					$new_row = $row - $this->initial_line - 1;
					foreach ( $column_headers as $key => $value ) {
						$result[ $new_row ][ $value ] = utf8_encode( trim( $data[ $key ] ) );
					}
				}

				unset( $data );

				$row++;
			}
		}

		$this->_close_csv();
		return $result;
	}

	private

	function _set_detect_line_endings( $detect_line_endings ) {
		$this->detect_line_endings = $detect_line_endings;
	}

	public

	function detect_line_endings( $detect_line_endings ) {
		$this->_set_detect_line_endings( $detect_line_endings );
		return $this;
	}

	private

	function _get_detect_line_endings() {
		return $this->detect_line_endings;
	}

	private

	function _set_initial_line( $initial_line ) {
		return $this->initial_line = $initial_line;
	}

	public

	function initial_line( $initial_line ) {
		$this->_set_initial_line( $initial_line );
		return $this;
	}

	private

	function _get_initial_line() {
		return $this->initial_line;
	}

	private

	function _set_delimiter( $delimiter ) {
		$this->delimiter = $delimiter;
	}

	public

	function delimiter( $delimiter ) {
		$this->_set_delimiter( $delimiter );
		return $this;
	}

	private

	function _get_delimiter() {
		return $this->delimiter;
	}

	private

	function _set_filepath( $filepath ) {
		$this->filepath = $filepath;
	}

	public

	function filepath( $filepath ) {
		$this->_set_filepath( $filepath );
		return $this;
	}

	private

	function _get_filepath() {
		return $this->filepath;
	}

	private

	function _set_column_headers( $column_headers = '' ) {
		if ( is_array( $column_headers ) && !empty( $column_headers ) ) {
			$this->column_headers = $column_headers;
		}
	}

	public

	function column_headers( $column_headers ) {
		$this->_set_column_headers( $column_headers );
		return $this;
	}

	private

	function _get_column_headers() {
		return $this->column_headers;
	}

	private

	function _get_handle() {
		$this->handle = fopen( $this->filepath, "r" );
	}

	private

	function _close_csv() {
		fclose( $this->handle );
	}
}