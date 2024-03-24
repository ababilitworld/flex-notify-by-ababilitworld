<?php
    namespace Ababilitworld\FlexNotifyByAbabilitworld\Core\Library\Function;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();
	
	if ( ! class_exists( '\Ababilitworld\FlexNotifyByAbabilitworld\Core\Library\Function\CoreFunction' ) ) 
	{
		class CoreFunction 
		{
			public static function error_notice($error)
			{				
				if($error->has_errors())
				{
					foreach($error->get_error_messages() as $error)
					{
						$class = 'notice notice-error';
						printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $error ) );
					}
					
				}
			}

			public static function check_plugin($plugin_dir_name,$plugin_file): int 
			{
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				$plugin_dir = ABSPATH . 'wp-content/plugins/'.$plugin_dir_name;
				if ( is_plugin_active( $plugin_dir_name.'/'.$plugin_file ) ) 
				{
					return 1;
				} 
				elseif ( is_dir( $plugin_dir ) ) 
				{
					return 2;
				} 
				else 
				{
					return 0;
				}
			}

			public static function install_plugin($plugin_file_url) 
			{
				include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
				include_once( ABSPATH . 'wp-admin/includes/file.php' );
				include_once( ABSPATH . 'wp-admin/includes/misc.php' );
				include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
				$upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
				$upgrader->install( $plugin_file_url );
				//$upgrader->install( 'https://github.com/magepeopleteam/magepeople-pdf-support/archive/master.zip' );
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
			 * Initializes the FlexNotifyByAbabilitworld class
			 *
			 * Create FlexNotifyByAbabilitworld instance if not exist.
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

        //new CoreFunction();
	
		/**
		 * Return the instance
		 *
		 * @return Ababilitworld\FlexNotifyByAbabilitworld\Core\Library\Function\CoreFunction
		 */
		function core_function() 
		{
			return CoreFunction::instance();
		}
	
		// take off
		//core_function();

		
	}
	
?>