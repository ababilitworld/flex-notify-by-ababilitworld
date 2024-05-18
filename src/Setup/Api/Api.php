<?php

namespace Ababilitworld\FlexNotifyByAbabilitworld\Setup\Api;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

defined( 'API_BASE_URL' ) || define('API_BASE_URL','flex-notify-by-ababilitworld');

use Ababilitworld\FlexCoreByAbabilitworld\Core\Library\Util\Api\Firebase\PhpJwtHelper;
use Ababilitworld\FlexNotifyByAbabilitworld\PluginFunction\PluginFunction;

if (!class_exists('Ababilitworld\FlexNotifyByAbabilitworld\Setup\Api\Api')) 
{
    class Api
    {
        public function __construct()
        {
            add_action('rest_api_init', array($this, 'register_endpoints'));
        }

        public function register_endpoints()
        {
            register_rest_route(API_BASE_URL.'/v1', '/plugin-status', array(
                'methods' => 'POST',
                'callback'            => array($this, 'get_plugin_status'),
                'permission_callback' => function($request){
                    return true || $this->check_permission($request);
                },
            ));

            register_rest_route(API_BASE_URL.'/v1', '/install-plugin', array(
                'methods' => 'POST',
                'callback'            => array($this, 'install_plugin'),
                'permission_callback' => function($request){
                    return true || $this->check_permission($request);
                },
            ));

            register_rest_route(API_BASE_URL.'/v1', '/activate-plugin', array(
                'methods'             => 'POST',
                'callback'            => array($this, 'activate_plugin'),
                'permission_callback' => function($request){
                    return true || $this->check_permission($request);
                },
            ));
        }

        public function plugin_status($request)
        {
            $inputs = $request->get_json_params();

            $plugin_dir_name = $inputs['pluginDirName'];

            $plugin_status = PluginFunction::get_plugin_status($plugin_dir_name);

            if ($plugin_status) 
            {
                return array('success' => true, 'message' => __('Plugin Status Retrieve Operation Successful', 'flex-notify-by-ababilitworld'), 'data'=>array('pluginStatus'=>$plugin_status));
            }
            else
            {
                return array('success' => false, 'message' => new \WP_Error('operation_failed', __('Plugin Status Retrieve Operation Failed.', 'flex-notify-by-ababilitworld')), 'data'=>array('pluginStatus'=>''));
            }
            
        }

        public function install_plugin($request)
        {
            $inputs = $request->get_json_params();

            $plugin_dir_name = $inputs['pluginDirName'];
            
            $plugin_installation_status = PluginFunction::install_plugin($plugin_dir_name);

            if ($plugin_installation_status) 
            {
                return array('success' => true, 'message' => __('Plugin Install Operation Successful', 'flex-notify-by-ababilitworld'), 'data'=>array('pluginInstallationStatus'=>$plugin_installation_status));
            }
            else
            {
                return array('success' => false, 'message' => new \WP_Error('operation_failed', __('Plugin Installation Operation Failed.', 'flex-notify-by-ababilitworld')), 'data'=>array('pluginInstallationStatus'=>''));
            }
            
        }

        public function activate($request)
        {
            $inputs = $request->get_json_params();

            $plugin_dir_name = $inputs['pluginDirName'];

            $plugin_activation_status = PluginFunction::activate_the_plugin($plugin_dir_name);

            if ($plugin_activation_status) 
            {
                return array('success' => true, 'message' => __('Plugin Activation Operation Successful', 'flex-notify-by-ababilitworld'), 'data'=>array('pluginActivationStatus'=>$plugin_activation_status));
            }
            else
            {
                return array('success' => false, 'message' => new \WP_Error('operation_failed', __('Plugin Activation Operation Failed.', 'flex-notify-by-ababilitworld')), 'data'=>array('pluginActivationStatus'=>''));
            }
            
        }

        public function check_permission($request)
        {
            if(class_exists(PhpJwtHelper::class))
            {   
                $data = PhpJwtHelper::verify_request_token($request);
                if($data && !is_string($data)) 
                {
                    return true;
                }
                else
                {
                    return false;
                }

            }
            else
            {
                return false;
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

    //new Api();
	
    /**
     * Return the instance
     *
     * @return Ababilitworld\FlexNotifyByAbabilitworld\Notify\Api\Api
     */
    function api() 
    {
        return Api::instance();
    }

    // take off
    //api();
}
