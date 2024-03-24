<?php
    namespace Ababilitworld\FlexNotifyByAbabilitworld\Core;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

    use Ababilitworld\FlexNotifyByAbabilitworld\{
        Core\Library\Function\CoreFunction
    };

    use function AbabilItWorld\FlexNotifyByAbabilitworld\{
		Core\Library\Function\core_function
	};
	
	if ( ! class_exists( '\AbabilItWorld\FlexNotifyByAbabilitworld\Core\Plugin' ) ) 
	{
		class Plugin 
		{
			/**
			 * Class error
			 *
			 * @var object
			 */
			private $error;
	
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
				$this->error = new \WP_Error();
                new CoreFunction();
				add_action('admin_notices', array($this, 'admin_notice' ) );
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
	
			/**
			 * Show admin panel error if any
			 *
			 * @return void
			 */
			public function admin_notice()
			{
				$core_function = core_function();	
				$core_function::error_notice($this->error);
			}
	
		}

        //new Plugin();
	
		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexNotifyByAbabilitworld\Core\Plugin
		 */
		function flex_notify() 
		{
			return Plugin::instance();
		}
	
		// take off
		//flex_notify();

		
	}
	
?>