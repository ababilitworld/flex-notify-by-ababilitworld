<?php
    namespace Ababilitworld\FlexNotifyByAbabilitworld;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

    use function AbabilItWorld\FlexCoreByAbabilitworld\{
		Core\Library\Function\wp_error_handler,
		Core\Library\Function\wp_function
	};

	if ( ! class_exists( '\AbabilItWorld\FlexNotifyByAbabilitworld\Plugin' ) ) 
	{
		class Plugin 
		{
			/**
			 * Objcet wp_error
			 *
			 * @var object
			 */
			private $wp_error;

			/**
			 * Objcet wp_function
			 *
			 * @var object
			 */
			private $wp_function;
	
			/**
			 * Plugin version
			 *
			 * @var string
			 */
			public $version = '1.0.0';
	
			/**
			 * Constructor
			 */
			public function __construct() 
			{
				//$this->wp_error = wp_error_handler();
                //$this->wp_function = wp_function();
				register_deactivation_hook(PLUGIN_FILE, array($this, 'deactivate'));
				register_uninstall_hook(PLUGIN_FILE, array('self', 'uninstall'));
                
			}
	
			/**
			 * Initializes the class
			 *
			 * Create instance if not exist.
			 */
			public static function instance() 
			{
				static $instance = false;
	
				if ( ! $instance ) 
				{
					$instance = new self();
				}
	
				return $instance;
			}
	
			/**
			 * Run the isntaller
			 * 
			 * @return void
			 */
			public static function run() 
			{
				$installed = get_option( PLUGIN_NAME.'-installed' );
	
				if ( ! $installed ) 
				{
					update_option( PLUGIN_NAME.'-installed', time() );
				}
	
				update_option( PLUGIN_NAME.'-version', PLUGIN_VERSION );
			}
	
			/**
			 * Activate The class
			 *
			 * @return void
			 */
			public static function activate(): void 
			{
				flush_rewrite_rules();
                self::run();
			}
	
			/**
			 * Dectivate The class
			 *
			 * @return void
			 */
			public static function deactivate(): void 
			{
				flush_rewrite_rules();
			}
	
			/**
			 * Uninstall The class
			 *
			 * @return void
			 */
			public static function uninstall(): void 
			{
				flush_rewrite_rules();
			}
	
		}

        //new Plugin();
	
		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexNotifyByAbabilitworld\Plugin
		 */
		function plugin() 
		{
			return Plugin::instance();
		}
	
		// take off
		//plugin();
	}
	
?>