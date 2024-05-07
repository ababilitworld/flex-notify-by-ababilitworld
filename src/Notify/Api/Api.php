<?php

namespace Ababilitworld\FlexNotifyByAbabilitworld\Notify\Api;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

defined( 'API_BASE_URL' ) || define('API_BASE_URL','flex-auth-by-ababilitworld');

use Ababilitworld\FlexCoreByAbabilitworld\Core\Library\Util\Api\Firebase\PhpJwtHelper\PhpJwtHelper;

if (!class_exists('Ababilitworld\FlexNotifyByAbabilitworld\Notify\Api\Api')) 
{
    class Api
    {
        public function __construct()
        {
            add_action('rest_api_init', array($this, 'register_endpoints'));
        }

        public function register_endpoints()
        {
            register_rest_route(API_BASE_URL.'/v1', '/login', array(
                'methods' => 'POST',
                'callback'            => array($this, 'login'),
                'permission_callback' => function(){
                    return true;
                },
            ));

            register_rest_route(API_BASE_URL.'/v1', '/verify-token', array(
                'methods' => 'POST',
                'callback'            => array($this, 'verify_token'),
                'permission_callback' => function(){
                    return true;
                },
            ));

            register_rest_route(API_BASE_URL.'/v1', '/logout', array(
                'methods'             => 'POST',
                'callback'            => array($this, 'logout'),
                'permission_callback' => function($request){
                    return true || $this->check_permission($request);
                },
            ));
        }

        public function login($request)
        {
            $creds = $request->get_json_params();
            
            require_once(ABSPATH . 'wp-includes/pluggable.php');

            $username = $creds['username'];
            $password = $creds['password'];

            $user = get_user_by('login', $username);

            if ($user && wp_check_password($password, $user->user_pass, $user->ID)) 
            {
                $data = array(
                    'user_id' => $user->ID,
                    'user_email' => $user->user_email,
                    'user_login' => $user->user_login,
                );

                $token = PhpJwtHelper::generate_token($data);

                return array('success' => true, 'message' => __('Token Generated Successfully', 'simple-task-manager'), 'data'=>array('stmfsToken'=>$token));
            }
            else
            {
                return array('success' => false, 'message' => new \WP_Error('invalid_credentials', __('Invalid username or password.', 'simple-task-manager')), 'data'=>array('token'=>''));
            }
        }

        public function verify_token($request)
        {
            try 
            {
                $data = PhpJwtHelper::verify_request_token($request);
                if($data && !is_string($data)) 
                {
                    return array('success' => true, 'message' => __('Token is verified successfully','simple-task-manager'),'data'=>array('verifiedData'=>$data,'isVerified'=>true));
                }
                else 
                {
                    return array('success' => false, 'message' => __('Token is rejected or not verified !!!','simple-task-manager'),'data'=>array('verifiedData'=>null,'isVerified'=>false));
                }
            }
            catch (\Exception $e)
            {
                return array('success' => false, 'message' => $e->getMessage(),'data'=>array('verifiedData'=>null,'isVerified'=>false));
            }
        }

        public function check_permission($request)
        {
            $data = PhpJwtHelper::verify_request_token($request);
            if($data && !is_string($data)) 
            {
                //now apply authorization logic here
                return true;
            }
            else
            {
                return false;
            }
        }

        public function logout($request)
        {
            $data = PhpJwtHelper::verify_request_token($request);
            if($data && !is_string($data)) 
            {
                $token = PhpJwtHelper::get_token_from_request($request);
                try 
                {
                    if(!PhpJwtHelper::is_token_invalid($token))
                    {
                        PhpJwtHelper::force_invalidate_token($token);
                    }

                    return array('success' => true, 'message' => __('Logged out successfully','simple-task-manager'),'data'=>array('isLoggedOut'=>true));

                } 
                catch (\Exception $e) 
                {
                    return array('success' => false, 'message' => $e->getMessage(),'data'=>array('isLoggedOut'=>false));
                }
            }
            else
            {
                return array('success' => true, 'message' => __('Logged out successfully','simple-task-manager'),'data'=>array('isLoggedOut'=>true));
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
