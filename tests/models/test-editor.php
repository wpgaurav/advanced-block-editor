<?php
/**
 * Class EditorTest
 *
 * @package Tests
 */

namespace Advanced_Block_Editor\Tests\Models;

use Advanced_Block_Editor\Classes\Models\Editor;
use PHPUnit\Framework\TestCase;
use WP_Framework;
use WP_UnitTestCase;

/**
 * @noinspection PhpUndefinedClassInspection
 * Editor test case.
 *
 * @mixin TestCase
 */
class EditorTest extends WP_UnitTestCase {

	/**
	 * @var WP_Framework
	 */
	protected static $app;

	/**
	 * @var Editor $editor
	 */
	private static $editor;

	/**
	 * @var bool $is_ci
	 */
	private static $is_ci;

	/**
	 * @SuppressWarnings(StaticAccess)
	 */
	public static function setUpBeforeClass() {
		static::$app    = WP_Framework::get_instance( ADVANCED_BLOCK_EDITOR );
		static::$editor = Editor::get_instance( static::$app );
		static::$is_ci  = ! empty( getenv( 'CI' ) );
		static::reset();
	}

	public static function tearDownAfterClass() {
		static::reset();
	}

	private static function reset() {
		wp_dequeue_script( 'advanced-block-editor' );
		wp_dequeue_style( 'advanced-block-editor' );
		if ( static::$is_ci ) {
			static::$app->file->delete( static::$app->define->plugin_assets_dir . DS . 'js' . DS . 'index.min.js' );
		}
	}

	public function test_enqueue_block_editor_assets() {
		wp_dequeue_script( 'advanced-block-editor' );
		wp_dequeue_style( 'advanced-block-editor' );
		if ( static::$is_ci ) {
			static::$app->file->put_contents( static::$app->define->plugin_assets_dir . DS . 'js' . DS . 'index.min.js', '' );
		}

		$this->assertFalse( wp_script_is( 'advanced-block-editor' ) );
		$this->assertFalse( wp_style_is( 'advanced-block-editor' ) );
		do_action( 'enqueue_block_editor_assets' );
		$this->assertTrue( wp_script_is( 'advanced-block-editor' ) );
		$this->assertTrue( wp_style_is( 'advanced-block-editor' ) );
	}
}
