<?php
    namespace Ababilitworld\FlexNotifyByAbabilitworld\PluginFunction;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

    use function AbabilItWorld\FlexCoreByAbabilitworld\{
		Core\Library\Function\wp_error_handler,
		Core\Library\Function\wp_function
	};

	if ( ! class_exists( '\AbabilItWorld\FlexNotifyByAbabilitworld\PluginFunction\PluginFunction' ) ) 
	{
		class PluginFunction 
		{	
			/**
			 * Constructor
			 */
			public function __construct() 
			{

			}

            public static function required_plugin_list(): array 
			{
				return array(
                    'flex-core-by-ababilitworld' => array(
                        'plugin_dir_name' => 'flex-core-by-ababilitworld',
                        'plugin_file_name' => 'flex-core-by-ababilitworld.php',
                        'plugin_install_link' => 'https://github.com/ababilitworld/flex-core-by-ababilitworld/archive/refs/heads/main.zip'
                    ),
                    'flex-auth-by-ababilitworld' => array(
                        'plugin_dir_name' => 'flex-auth-by-ababilitworld',
                        'plugin_file_name' => 'flex-auth-by-ababilitworld.php',
                        'plugin_install_link' => 'https://github.com/ababilitworld/flex-auth-by-ababilitworld/archive/refs/heads/main.zip'
                    ),
                );
			}

            public static function get_plugin_status($plugin_dir_name)
            {
                $required_plugins = self::required_plugin_list();
                if(is_array($required_plugins) && array_key_exists($plugin_dir_name,$required_plugins))
                {
                    $plugin_information = $required_plugins[$plugin_dir_name];
                    return self::check_plugin_status($plugin_information['plugin_dir_name'],$plugin_information['plugin_file_name']);
                }
            }

			public static function check_plugin_status($plugin_dir_name,$plugin_file): int 
			{
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				$plugin_dir = ABSPATH . 'wp-content/plugins/'.$plugin_dir_name;
				if ( is_plugin_active( $plugin_dir_name.'/'.$plugin_file ) ) 
				{
					return 'activated';
				} 
				elseif ( is_dir( $plugin_dir ) ) 
				{
					return 'installed';
				} 
				else 
				{
					return 'not_found';
				}
			}

			public static function install_plugin($plugin_dir_name)
            {
                $required_plugins = self::required_plugin_list();
                if(is_array($required_plugins) && array_key_exists($plugin_dir_name,$required_plugins))
                {
                    $plugin_information = $required_plugins[$plugin_dir_name];
                    return self::install_plugin_from_url($plugin_information['plugin_install_link']);
                }

				return false;
            }

			public static function install_plugin_from_url($plugin_file_url) 
			{
				include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
				include_once( ABSPATH . 'wp-admin/includes/file.php' );
				include_once( ABSPATH . 'wp-admin/includes/misc.php' );
				include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
				$upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
				$upgrader->install( $plugin_file_url );
				//$upgrader->install( 'https://github.com/magepeopleteam/magepeople-pdf-support/archive/master.zip' );
				if (is_wp_error($result)) 
				{
					return false;
				}
				else
				{
					return true;
				}
			}

            public static function activate_the_plugin( $plugin_dir_name )
            {
                $required_plugins = self::required_plugin_list();
                if(is_array($required_plugins) && array_key_exists($plugin_dir_name,$required_plugins))
                {
                    $plugin_information = $required_plugins[$plugin_dir_name];
                    $activation_status = self::activate_plugin($plugin_information['plugin_dir_name'].'/'.$plugin_information['plugin_file_name']);
					if ($activation_status === null) 
					{
						return true;
					} 
					elseif ($activation_status instanceof \WP_Error) 
					{
						return false;
					}
                }
				
				return false;
            }

			public static function activate_plugin( $plugin, $redirect_url = '', $network_wide = false, $silent = false )
            {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            
                return activate_plugin($plugin, $redirect_url, $network_wide, $silent);                
            }

			public static function download_and_extract_file($romote_file_url,$local_directory_path = ABSPATH . 'wp-content/plugins/',$file_name) 
			{
				$download_path = $local_directory_path . $file_name;
				$response = wp_remote_get( $romote_file_url, array( 'timeout' => 300 ) );

				if ( is_wp_error( $response ) )
				{
					echo 'Failed to download file: ' . $response->get_error_message();
				}
				else
				{
					$file_handle = fopen( $download_path, 'w' );
					fwrite( $file_handle, $response['body'] );
					fclose( $file_handle );
					$zip = new \ZipArchive;
					if ( $zip->open( $download_path ) === TRUE ) 
					{
						$zip->extractTo( $local_directory_path );
						$zip->close();
						echo 'File extracted successfully.';
					}
					else
					{
						echo 'Failed to extract file.';
					}

					unlink( $download_path );
				}
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

        //new PluginFunction();
	
		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexNotifyByAbabilitworld\PluginFunction\PluginFunction
		 */
		function plugin_function() 
		{
			return PluginFunction::instance();
		}
	
		// take off
		//plugin_function();

		
	}
	
?>