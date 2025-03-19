<?php
/**
 * Autoload hooks related shortcode autoloader plugin functionality.
 *
 * @note we require our autoload files everytime and everywhere after plugin load.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class VcShortcodeAutoloader
 */
class VcShortcodeAutoloader {

	/**
	 * Instance of VcShortcodeAutoloader
	 *
	 * @var VcShortcodeAutoloader
	 */
	private static $instance = null;

	/**
	 * Configuration
	 *
	 * @var array
	 */
	private static $config = null;

	/**
	 * Cached
	 *
	 * @var null|bool
	 */
	private static $cached = null;

	/**
	 * Get instance of VcShortcodeAutoloader
	 *
	 * @param bool $load_config
	 * @return \VcShortcodeAutoloader|null
	 */
	public static function getInstance( $load_config = true ) {
		if ( null === self::$instance ) {
			self::$instance = new VcShortcodeAutoloader( $load_config );
		}

		return self::$instance;
	}

	/**
	 * VcShortcodeAutoloader constructor.
	 *
	 * @param bool $load_config
	 */
	private function __construct( $load_config = true ) {
		if ( ! $load_config ) {
			return;
		}

		$this->loadConfig();
	}

	/**
	 * Include class dependencies
	 *
	 * @param string $class_name Class name.
	 *
	 * @return string[] Included (if any) files
	 */
	public static function includeClass( $class_name ) {
		// call the constructor (php 7.4 compat).
		self::getInstance();
		if ( ! is_array( self::$config ) ) {
			self::loadConfig();
		}
		$class_name = strtolower( $class_name );
		$files = [];

		if ( self::$config['classmap'] ) {
			$files = isset( self::$config['classmap'][ $class_name ] ) ? self::$config['classmap'][ $class_name ] : [];
		}

		if ( $files ) {
			foreach ( $files as $k => $file ) {
				if ( self::$cached ) {
					$file = self::$config['root_dir'] . DIRECTORY_SEPARATOR . $file;
					$files[ $k ] = $file;
				}

				if ( is_file( $file ) ) {
					require_once $file;
				}
			}
		}

		return $files;
	}

	/**
	 * Find all classes defined in file
	 *
	 * @param string $file Full path to file.
	 *
	 * @return string[]
	 */
	public static function extractClassNames( $file ) {
		$classes = [];

		// @codingStandardsIgnoreLine
		$contents = file_get_contents( $file );
		if ( ! $contents ) {
			return $classes;
		}

		$tokens = token_get_all( $contents );
		$class_token = false;
		foreach ( $tokens as $token ) {
			if ( is_array( $token ) ) {
				if ( T_CLASS === $token[0] ) {
					$class_token = true;
				} elseif ( $class_token && T_STRING === $token[0] ) {
					$classes[] = $token[1];
					$class_token = false;
				}
			}
		}

		return $classes;
	}

	/**
	 * Extract all classes from file with their extends
	 *
	 * @param string $file
	 *
	 * @return array Associative array where key is class name and value is parent class name (if any))
	 */
	public static function extractClassesAndExtends( $file ) {
		$classes = [];

		// @codingStandardsIgnoreLine
		$contents = file_get_contents( $file );
		if ( ! $contents ) {
			return $classes;
		}

		// class Foo extends Bar {.
		preg_match_all( '/class\s+(\w+)\s+extends\s(\w+)\s+\{/i', $contents, $matches, PREG_SET_ORDER );
		foreach ( $matches as $v ) {
			$classes[ $v[1] ] = $v[2];
		}

		// class Foo {.
		preg_match_all( '/class\s+(\w+)\s+\{/i', $contents, $matches, PREG_SET_ORDER );
		foreach ( $matches as $v ) {
			$classes[ $v[1] ] = null;
		}

		return $classes;
	}

	/**
	 * Find file by class name
	 *
	 * Search is case-insensitive
	 *
	 * @param string $class_name
	 * @param string[]|string $dirs One or more directories where to look (recursive).
	 *
	 * @return string|false Full path to class file
	 */
	public static function findClassFile( $class_name, $dirs ) {
		foreach ( (array) $dirs as $dir ) {
			$Directory = new RecursiveDirectoryIterator( $dir );
			$Iterator = new RecursiveIteratorIterator( $Directory );
			$Regex = new RegexIterator( $Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH );
			$class_name = strtolower( $class_name );

			foreach ( $Regex as $file => $object ) {
				$classes = self::extractClassNames( $file );

				if ( $classes && in_array( $class_name, array_map( 'strtolower', $classes ), true ) ) {
					return $file;
				}
			}
		}

		return false;
	}

	/**
	 * Construct full dependency list of classes for each class in right order (including class itself)
	 *
	 * @param string[]|string $dirs Directories where to look (recursive).
	 *
	 * @return array Associative array where key is lowercase class name and value is array of files to include for
	 *     that class to work
	 */
	public static function generateClassMap( $dirs ) {
		$flat_map = [];
		foreach ( (array) $dirs as $dir ) {
			$Directory = new RecursiveDirectoryIterator( $dir );
			$Iterator = new RecursiveIteratorIterator( $Directory );
			$Regex = new RegexIterator( $Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH );

			foreach ( $Regex as $file => $object ) {
				$classes = self::extractClassesAndExtends( $file );

				foreach ( $classes as $class => $extends ) {
					$class = strtolower( $class );
					$extends = is_string( $extends ) ? strtolower( $extends ) : $extends;
					if ( in_array( $extends, [
						'wpbakeryshortcodescontainer',
						'wpbakeryvisualcomposer',
						'wpbakeryshortcode',
						'wpbmap',
					], true ) ) {
						$extends = null;
					}
					$flat_map[ $class ] = [
						'class' => $class,
						'file' => $file,
						'extends' => $extends,
					];
				}
			}
		}

		$map = [];
		foreach ( $flat_map as $params ) {
			$dependencies = [
				[
					'class' => $params['class'],
					'file' => $params['file'],
				],
			];

			if ( $params['extends'] ) {
				$queue = [ $params['extends'] ];

				while ( $queue ) {
					$current_class = array_pop( $queue );
					$current_class = $flat_map[ $current_class ];

					$dependencies[] = [
						'class' => $current_class['class'],
						'file' => $current_class['file'],
					];

					if ( ! empty( $current_class['extends'] ) ) {
						$queue[] = $current_class['extends'];
					}
				}

				$map[ $params['class'] ] = array_reverse( $dependencies );
			} else {
				$map[ $params['class'] ] = $dependencies;
			}
		}

		// simplify array.
		$classmap = [];
		foreach ( $map as $class => $dependencies ) {
			$classmap[ $class ] = [];
			foreach ( $dependencies as $v ) {
				$classmap[ $class ][] = str_replace( '\\', '/', $v['file'] );
			}
		}

		return $classmap;
	}

	/**
	 * Regenerate and save class map file
	 *
	 * @param string[]|string $dirs Directories where to look (recursive).
	 * @param string $target Output file.
	 *
	 * @return bool
	 */
	public static function saveClassMap( $dirs, $target ) {
		if ( ! $target ) {
			return false;
		}

		$classmap = self::generateClassMap( $dirs );

		// @codingStandardsIgnoreLine
		$code = '<?php return (array) json_decode(\'' . json_encode( $classmap ) . '\') ?>';

		// @codingStandardsIgnoreLine
		return (bool) file_put_contents( $target, $code );
	}

	/**
	 * Load configuration
	 */
	protected static function loadConfig() {
		$config = [
			'classmap_file' => vc_path_dir( 'APP_ROOT', 'vc_classmap.json.php' ),
			'shortcodes_dir' => vc_path_dir( 'SHORTCODES_DIR' ),
			'root_dir' => vc_path_dir( 'APP_ROOT' ),
		];

		if ( is_file( $config['classmap_file'] ) ) {
			$config['classmap'] = require $config['classmap_file'];
			self::$cached = true;
		} else {
			$config['classmap'] = self::generateClassMap( $config['shortcodes_dir'] );
			self::$cached = false;
		}

		self::$config = $config;
	}
}
