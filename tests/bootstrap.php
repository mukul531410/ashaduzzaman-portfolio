<?php
/**
 * Bootstrap file for PHPUnit.
 */

if ( ! file_exists( dirname( __DIR__ ) . '/functions.php' ) ) {
	throw new Exception( 'Theme functions file not found.' );
}

require_once dirname( __DIR__ ) . '/functions.php';
