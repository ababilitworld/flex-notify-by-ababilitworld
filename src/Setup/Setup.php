<?php
    namespace Ababilitworld\FlexNotifyByAbabilitworld\Setup;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

    use function AbabilItWorld\FlexCoreByAbabilitworld\{
		Core\Library\Function\wp_error_handler,
		Core\Library\Function\wp_function
	};

	if ( ! class_exists( '\AbabilItWorld\FlexNotifyByAbabilitworld\Setup\Setup' ) ) 
	{
		class Setup 
		{
			/**
			 * Object wp_error
			 *
			 * @var object
			 */
			private $wp_error;
	
			/**
			 * Constructor
			 */
			public function __construct() 
			{
				//$this->wp_error = wp_error_handler();
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
					__('Flex Notify Setup', 'flex-notify-by-ababilitworld'),
					__('Flex Notify Setup', 'flex-notify-by-ababilitworld'),
					'manage_options',
					'flex-notify-setup-by-ababilitworld',
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
					<div id="flex-notify-setup-by-ababilitworld-wrap">
						<div id="flex-notify-setup-by-ababilitworld">

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
	
		}

        //new Setup();
	
		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexNotifyByAbabilitworld\Setup\Setup
		 */
		function setup() 
		{
			return Setup::instance();
		}
	
		// take off
		//setup();

		
	}
	
?>