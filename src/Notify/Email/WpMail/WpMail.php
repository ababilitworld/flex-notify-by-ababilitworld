<?php

    namespace Ababilitworld\FlexNotifyByAbabilitworld\Notify\Email\WpMail;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

    use function AbabilItWorld\FlexNotifyByAbabilitworld\{
        Core\Library\Function\wp_error_handler,
		Core\Library\Function\wp_function
	};
	
	if ( ! class_exists( '\Ababilitworld\FlexNotifyByAbabilitworld\Notify\Email\WpMail\WpMail' ) ) 
	{

        class WpMail 
        {
            /**
			 * Object wp_error
			 *
			 * @var object
			 */
			private $wp_error;
            private $wp_function;
            private $wp_mail_settings;

            public function __construct() 
            {
                $this->wp_error = wp_error_handler();
                $this->wp_function = wp_function();
                add_action('phpmailer_init', array($this,'init_smtp'));
                add_action(PLUGIN_PRE_UNDS.'_wpmail_send', array($this,'wpmail_send'));
                $this->wp_error->wp_add_error('invalid_data', esc_html__( 'Bismillah . It is working!!! '),'');
                //echo "<pre>";print_r($this->wp_error->wp_error);echo "</pre>";exit;
                $this->wp_error->wp_add_error('invalid_data', esc_html__( 'Alhamdulillah . It is working!!! '),'');
                //$this->wp_error->wp_error_log('display');
            }

            public function init_wpmail()
            {
                $email_settings = get_option(PLUGIN_PRE_UNDS.'_wpmail_settings',array());	
				$this->wp_mail_settings = $this->wp_function::sanitize_data($email_settings);
            }

            public function init_smtp($phpmailer)
            {
                $phpmailer->isSMTP();
                $phpmailer->Host = $this->wp_function::check_array_key_exists($this->wp_mail_settings,array('smtp','host'))?$this->wp_mail_settings['smtp']['host']:'';
                $phpmailer->Port = $this->wp_function::check_array_key_exists($this->wp_mail_settings,array('smtp','port'))?$this->wp_mail_settings['smtp']['port']:'';
                $phpmailer->SMTPAuth = $this->wp_function::check_array_key_exists($this->wp_mail_settings,array('smtp','auth'))?$this->wp_mail_settings['smtp']['auth']:'';
                $phpmailer->Username = $this->wp_function::check_array_key_exists($this->wp_mail_settings,array('smtp','username'))?$this->wp_mail_settings['smtp']['username']:'';
                $phpmailer->Password = $this->wp_function::check_array_key_exists($this->wp_mail_settings,array('smtp','password'))?$this->wp_mail_settings['smtp']['password']:'';
                $phpmailer->SMTPSecure = $this->wp_function::check_array_key_exists($this->wp_mail_settings,array('smtp','security'))?$this->wp_mail_settings['smtp']['security']:'';
            }

            public function wpmail_send($args) 
            {
                $to = $this->wp_function::check_array_key_exists($args,array('to'))?$args['to']:'';
                $subject = $this->wp_function::check_array_key_exists($args,array('subject'))?$args['subject']:'';
                $message = $this->wp_function::check_array_key_exists($args,array('message'))?$args['message']:'';
                $headers = $this->wp_function::check_array_key_exists($args,array('headers'))?$args['headers']:'';
                $attachments = $this->wp_function::check_array_key_exists($args,array('attachments'))?$args['attachments']:array();
                
                $to = sanitize_email($to);
                $subject = sanitize_text_field($subject);
                $message = wp_kses_post($message);
                
                $headers = empty($headers) ? array() : (array) $headers;
                
                if (!is_email($to)) 
                {
                    do_action(PLUGIN_PRE_UNDS.'_wpmail_validation_failed',array('to'));
                }

                if(!$this->wp_error->has_errors())
                {
                                   
                    $sent = wp_mail($to, $subject, $message, $headers, $attachments);
                    
                    if ($sent) 
                    {
                        do_action(PLUGIN_PRE_UNDS.'_wpmail_send_successfull',$args);
                        return true;
                    }
                    else 
                    {
                        do_action(PLUGIN_PRE_UNDS.'_wpmail_send_failed',$args);
                        return false;
                    }
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

        /**
		 * Return the instance
		 *
		 * @return Ababilitworld\FlexNotifyByAbabilitworld\Notify\Email\WpMail\WpMail
		 */
		function notify_email_wpmail() 
		{
			return WpMail::instance();
		}

    }

?>
