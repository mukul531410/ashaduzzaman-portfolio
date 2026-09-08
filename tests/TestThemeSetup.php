<?php
/**
 * PHPUnit tests for the headless theme.
 *
 * @package AshaduzzamanPortfolio
 */

use PHPUnit\Framework\TestCase;

class TestThemeSetup extends TestCase {
	public function testThemeFunctionsFileExists(): void {
		$this->assertFileExists( dirname( __DIR__ ) . '/functions.php' );
	}

	public function testThemeSupportFunctionsExist(): void {
		$this->assertTrue( function_exists( 'ashp_register_headless_theme_support' ) );
		$this->assertTrue( function_exists( 'ashp_setup_theme' ) );
	}

	public function testPostTypeRegistrationFunctionExists(): void {
		$this->assertTrue( function_exists( 'ashp_register_project_post_type' ) );
	}
}
