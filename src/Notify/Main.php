<?php
    namespace Ababilitworld\FlexNotifyByAbabilitworld\Notify;

use Exception;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

    use function AbabilItWorld\FlexNotifyByAbabilitworld\{
		Core\Library\Function\core_function
	};
	
	if ( ! class_exists( '\AbabilItWorld\FlexNotifyByAbabilitworld\Notify\Main' ) ) 
	{
		class Main 
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
				add_action('admin_notices', array($this, 'admin_notice' ) );
				add_action('plugins_loaded',array($this, 'plugins_loaded'));				
				add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts' ) );
				add_action('admin_menu', array($this, 'admin_menu' ) );				
			}

			/**
			 * Add Admin menu
			 *
			 * Add admin menu
			 */
            public function plugins_loaded()
            {
											
			}

			/**
			 * Add Admin menu
			 *
			 * Add admin menu
			 */
            public function enqueue_scripts()
            {
				wp_enqueue_script( 'flex-notify-by-ababilitworld', PLUGIN_URL . '/dist/bundle.js', [ 'jquery', 'wp-element' ], wp_rand(), true );
				wp_localize_script( 'flex-notify-by-ababilitworld', 'appLocalizer', [
					'apiUrl' => home_url( '/wp-json' ),
					'nonce' => wp_create_nonce( 'wp_rest' ),
				] );											
			}
	
			/**
			 * Add Admin menu
			 *
			 * Add admin menu
			 */
            public function admin_menu()
            {
				add_menu_page(
					__('Flex Notify', 'flex-notify-by-ababilitworld'),
					__('Flex Notify', 'flex-notify-by-ababilitworld'),
					'manage_options',
					'flex-notify-by-ababilitworld',
					array($this, 'render_page'),
					'dashicons-admin-post',
					9
				);
            }

			/**
			 * Render Flex Notify page
			 */
			public function render_page() 
			{
				?>
					<div id="flex-notify-by-ababilitworld-wrap">
						<div id="flex-notify-by-ababilitworld">

						</div>
					</div>
				<?php
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

        //new Main();
	
		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexNotifyByAbabilitworld\Core\Main
		 */
		function notify() 
		{
			return Main::instance();
		}
	
		// take off
		//notify();

		
	}
	
?>