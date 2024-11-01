<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       nicogaldo.com.ar
 * @since      1.0.0
 *
 * @package    Wc_Expired_Products
 * @subpackage Wc_Expired_Products/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Expired_Products
 * @subpackage Wc_Expired_Products/admin
 * @author     Nicolas Galdo <soporte@devacid.com>
 */
class Wc_Expired_Products_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->wcep_options = get_option($this->plugin_name);

		if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			//notices 
			add_action( 'admin_notices', array( $this, 'wcwp_notices' ) );

			//expire products
			if ( !wp_next_scheduled( 'expire_products_hook' ) && isset($this->wcep_options['notifynow'] )) {
				wp_schedule_event( time(), 'daily', 'expire_products_hook' );
			}
			//add_action( 'expire_products_hook', array( $this, 'expire_products') );
			
			//notify before
			if ( !wp_next_scheduled( 'notifybefore_hook' ) && isset($this->wcep_options['notifybefore'] )) {
				wp_schedule_event( time(), 'daily', 'notifybefore_hook' );
			}
			//add_action( 'notifybefore_hook', array( $this, 'notifybefore') );
			
			//notify after
			if ( !wp_next_scheduled( 'notifyafter_hook' ) && isset($this->wcep_options['notifyafter'] )) {
				wp_schedule_event( time(), 'daily', 'notifyafter_hook' );
			}
			//add_action( 'notifyafter_hook', array( $this, 'notifyafter') );
			
			//delete product
			if ( !wp_next_scheduled( 'deleteproduct_hook' ) && isset($this->wcep_options['deleteproduct'] )) {
				wp_schedule_event( time(), 'daily', 'deleteproduct_hook' );
			}
			//add_action( 'deleteproduct_hook', array( $this, 'deleteproduct') );

		} else {
			add_action( 'admin_notices', array( $this, 'notifyWooCommerceMiss' ) );
		}
		
	}

	public function notifyWooCommerceMiss() {
		echo '<div class="error"><p>'
			.__( 'WC Expired Products requires <a href="https://wordpress.org/plugins/woocommerce/" target="_blank" >WooCommerce</a> installed and activated to run.', $this->plugin_name )
			.'</p></div>';
	}

	public function wcwp_notices($class, $message) {
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Expired_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Expired_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-expired-products-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Expired_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Expired_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-expired-products-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		//add_options_page( 'WC Expired Products', 'Expired Products', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		add_submenu_page( 'woocommerce', 'WC Expired Products', 'Expired Products', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')

		);
	}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
	   $settings_link = array(
		'<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	 
	public function display_plugin_setup_page() {


		if (isset($_POST['resetallproducts']) && check_admin_referer('iwanttoresetallproducts')) {
			// the button has been pressed AND we've passed the security check
			$this->configureallproducts();

			add_action( 'admin_notices', 'configureallproducts' );
		}

		include_once( 'partials/wc-expired-products-admin-display.php' );

		
	}

	/**
	 * Manage the settings.
	 *
	 * @since    1.0.0
	 */

	public function validate($input) {

		$valid = array();

		$valid['expiretime']    = $input['expiretime'];
		$valid['notifybefore']  = (isset($input['notifybefore']) && !empty($input['notifybefore'])) ? 1 : 0;
		$valid['notifynow']     = (isset($input['notifynow']) && !empty($input['notifynow'])) ? 1 : 0;
		$valid['notifyafter']   = (isset($input['notifyafter']) && !empty($input['notifyafter'])) ? 1 : 0;
		$valid['deleteproduct'] = (isset($input['deleteproduct']) && !empty($input['deleteproduct'])) ? 1 : 0;
		$valid['alsoimages']    = (isset($input['alsoimages']) && !empty($input['alsoimages'])) ? 1 : 0;

		return $valid;
	}

	public function options_update() {
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	/**
	 * Real functions for this plugin ;)
	 *
	 */

	//Notify the author when his product has expired.
	public function expire_products( $email_classes ) {

		if(isset($this->wcep_options['expiretime']) && !empty($this->wcep_options['expiretime'])){

			global $wpdb;
			$daystogo = $this->wcep_options['expiretime'];

			$sql = "SELECT *
			FROM {$wpdb->posts}
			WHERE post_type = 'product'
			AND post_status = 'publish'
			AND DATEDIFF(NOW(), post_modified) = {$daystogo}";
			$results = $wpdb->get_results($sql) or die(mysql_error());

			

			//if notifynow is checked and query success
			if ($results == true && isset($this->wcep_options['notifynow'])) {

				foreach( $results as $product ) {

					$user_info	= get_userdata($product->post_author);
					$user_email	= $user_info->user_email;
					$username	= $user_info->user_login;
					$sitename	= get_bloginfo('name');					

					global $woocommerce;
					$mailer = $woocommerce->mailer();					

					$message_title = sprintf( __('Your product "%1$s" has changed the status to Draft', $this->plugin_name), $product->post_title );

					$message_body = sprintf( __('Hi %1$s,', $this->plugin_name), $username );					
					$message_body .= sprintf( __('<br><br>it\'s been more than %1$s days and has not updated the publication period of your product <strong>"%2$s"</strong>.', $this->plugin_name), $this->wcep_options['expiretime'], $product->post_title );					
					$message_body .= sprintf( __('<br><br>Your product is now in draft, please login to <a href="%1$s">your account</a> and renew the period.', $this->plugin_name), get_permalink( get_option('woocommerce_myaccount_page_id') ) );

					$to = $user_email;

					$subject = sprintf( '%1$s | %2$s', $sitename, $message_title );

					$message = $mailer->wrap_message( $message_title, $message_body );
					
					//change status
					/*wp_update_post(array(
						'ID'			=> $product->ID,
						'post_status'	=> 'draft',
						'edit_date'		=> false
					));*/

					//change status
					$sql =
					"UPDATE {$wpdb->posts}
					SET post_status = 'draft'
					WHERE ID = $product->ID";
					$wpdb->query( $wpdb->prepare( $sql ) );

					//finaly send email
					$mailer->send( $to, $subject, $message );
				}
			}
		}
	}

	//Notify 5 days before the product expires.
	public function notifybefore( $email_classes ) {

		if (isset($this->wcep_options['notifybefore']) && !empty($this->wcep_options['expiretime'])) {

			global $wpdb;
			$productexpire = $this->wcep_options['expiretime'] - 5;

			$sql = "SELECT *
			FROM {$wpdb->posts}
			WHERE post_type = 'product'
			AND post_status = 'publish'
			AND DATEDIFF(NOW(), post_modified) = {$productexpire}";
			$results = $wpdb->get_results($sql) or die(mysql_error());

			if ($results == true && isset($this->wcep_options['notifybefore'])) {

				foreach( $results as $product ) {

					//$productdate = $product->post_modified;
					//$productexpired = strtotime("+".$this->wcep_options['expiretime']." days", strtotime($productdate));
					//$notifi = strtotime("-5 day", $productexpired);

					$user_info	= get_userdata($product->post_author);
					$user_email	= $user_info->user_email;
					$username	= $user_info->user_login;
					$sitename	= get_bloginfo('name');

					global $woocommerce;
					$mailer = $woocommerce->mailer();

					$message_title = sprintf( __('Your product "%1$s" is about to expire.', $this->plugin_name), $product->post_title );

					$message_body = sprintf( __('Hi %1$s,', $this->plugin_name), $username );
					$message_body .= sprintf( __('<br><br>Your product <strong>"%1$s"</strong> will expire in 5 days.', $this->plugin_name), $product->post_title );
					$message_body .= sprintf( __('<br><br>We recommend that you enter <a href="%1$s">your account</a> and update the publication, and will be visible on the site for %2$s days more.', $this->plugin_name), get_permalink( get_option('woocommerce_myaccount_page_id') ), $this->wcep_options['expiretime'] );

					//$message_body .= '<br><hr><br>';
					//$message_body .= 'Esta es la consulta: '.$sql;
					//$message_body .= '<br> el link del producto: '.get_permalink($product->ID);
					//$message_body .= '<br> la fecha de modificacion: '.$product->post_modified;
					//$message_body .= '<br> fecha a expirar: '.date( 'Y-m-d H:i:s', $productexpired );
					//$message_body .= '<br> fecha de aviso: '.date( 'Y-m-d H:i:s', $notifi );

					//$to = 'produccion@dev.criterionet.com';
					$to = $user_email;

					$subject = sprintf( '%1$s | %2$s', $sitename, $message_title );

					$message = $mailer->wrap_message( $message_title, $message_body );

					$mailer->send( $to, $subject, $message );

				}
			}
		}
	}

	//Remember (7 days later) that your product has expired.
	public function notifyafter( $email_classes ) {

		if (isset($this->wcep_options['notifyafter']) && !empty($this->wcep_options['expiretime'])) {

			global $wpdb;
			$productexpire = $this->wcep_options['expiretime'] + 7;

			$sql = "SELECT *
			FROM {$wpdb->posts}
			WHERE post_type = 'product'
			AND post_status = 'draft'
			AND DATEDIFF(NOW(), post_modified) = {$productexpire}";
			$results = $wpdb->get_results($sql) or die(mysql_error());

			if ($results == true && isset($this->wcep_options['notifyafter'])) {

				foreach( $results as $product ) {

					$user_info	= get_userdata($product->post_author);
					$user_email	= $user_info->user_email;
					$username	= $user_info->user_login;
					$sitename	= get_bloginfo('name');

					global $woocommerce;
					$mailer = $woocommerce->mailer();

					$message_title = sprintf( __('Remember that your product "%1$s" has expired', $this->plugin_name), $product->post_title );

					$message_body = sprintf( __('Hi %1$s,', $this->plugin_name), $username );
					$message_body .= sprintf( __('<br><br>Your product <strong>"%1$s"</strong> it has expired 7 days ago', $this->plugin_name), $product->post_title );
					$message_body .= __('<br><br>You can do two things: update the product to be visible on the site, or do nothing.', $this->plugin_name);

					if (isset($this->wcep_options['deleteproduct'])) {
						$message_body .= __('If you do nothing, the product will be removed 7 days after this notification.', $this->plugin_name);
					}
					

					$to = $user_email;

					$subject = sprintf( '%1$s | %2$s', $sitename, $message_title );

					$message = $mailer->wrap_message( $message_title, $message_body );

					$mailer->send( $to, $subject, $message );

				}
			}
		}
	}

	//Delete the product if it has not been updated 14 days after it has expired.
	public function deleteproduct( $email_classes ) {

		if (isset($this->wcep_options['deleteproduct']) && !empty($this->wcep_options['expiretime'])) {

			global $wpdb;
			$productexpire = $this->wcep_options['expiretime'] + 14;

			$sql = "SELECT *
			FROM {$wpdb->posts}
			WHERE post_type = 'product'
			AND post_status = 'draft'
			AND DATEDIFF(NOW(), post_modified) > {$productexpire}";
			$results = $wpdb->get_results($sql) or die(mysql_error());

			if ($results == true && isset($this->wcep_options['deleteproduct'])) {

				foreach( $results as $product ) {

					$user_info	= get_userdata($product->post_author);
					$user_email	= $user_info->user_email;
					$username	= $user_info->user_login;
					$sitename	= get_bloginfo('name');

					global $woocommerce;
					$mailer = $woocommerce->mailer();

					$message_title = sprintf( 'El producto "%1$s" se ha eliminado.', $product->post_title );

					$message_body = sprintf( 'El producto <strong>%1$s</strong> se ha eliminado', $product->post_title );

					$to = $user_email;

					$subject = sprintf( '%1$s | %2$s', $sitename, $message_title );

					$message = $mailer->wrap_message( $message_title, $message_body );

					$mailer->send( $to, $subject, $message );

					//delete images
					if (!empty($this->wcep_options['alsoimages']) && isset($this->wcep_options['alsoimages'])) {
						
						$sql = "SELECT * 
						FROM  {$wpdb->postmeta}
						WHERE (meta_key =  '_product_image_gallery'	OR meta_key =  '_thumbnail_id')
						AND post_id = {$product->ID}";
						$results = $wpdb->get_results($sql) or die(mysql_error());

						foreach( $results as $image ) {

							$gallery = explode(',', $image->meta_value);
							foreach ($gallery as $imageID) {
								wp_delete_attachment($imageID);
								/*$to = 'produccion@dev.criterionet.com';
								wp_mail($to, 'se ejecuta', 'el archivo '.$imageID.' fue eliminado');*/
							}							
						}
					}

					//finaly delete product
					wp_delete_post($product->ID);
				}
			}
		}
	}

	//first install
	public function configureallproducts( ) {

		if (isset($this->wcep_options['expiretime']) && !empty($this->wcep_options['expiretime'])) {

			global $wpdb;
			$productexpire = $this->wcep_options['expiretime'];

			$sql = "SELECT *
			FROM {$wpdb->posts}
			WHERE post_type = 'product'
			AND post_status = 'publish'
			AND DATEDIFF(NOW(), post_modified) > {$productexpire}";
			$results = $wpdb->get_results($sql); //or die(mysql_error());

			if ($results == true) {

				foreach( $results as $product ) {

					//change post date
					wp_update_post(array(
						'ID'            	=> $product->ID,
						'post_modified'		=> date("Y-m-d H:i:s"),
						'post_modified_gmt'	=> date("Y-m-d H:i:s")
					));

					//wp_mail('produccion@dev.criterionet.com','se ejecuta en el post #'.$product->ID,'post modificado a la fecha de hoy');
				}

				$class = 'notice notice-success is-dismissible';
				$message = __( 'Your products have been updated!', $this->plugin_name );
				$this->wcwp_notices($class, $message);

			} else {
				$class = 'notice notice-error';
				$message = __( 'There was an error or no products to modify.', $this->plugin_name );
				$this->wcwp_notices($class, $message);
			}
		}
	}

	//add column
	function expirationday( $columns ) {
	   $columns['expirationday_id'] = __('Expiration Day', $this->plugin_name );
	   return $columns;
	}

	function expirationday_content( $column ) {
		global $post;

		$productdate 	= $post->post_modified;
		$productexpired = strtotime("+".$this->wcep_options['expiretime']." days", strtotime($productdate));
		
		$datetime1 = new DateTime(date("Y-m-d", $productexpired));
		$datetime2 = new DateTime(date("Y-m-d"));
		$interval = $datetime1->diff($datetime2);
		$exprirein = $interval->format('%R%a');

		if (strpos($exprirein, '-') !== false) {
		    $exprirein = sprintf( __('in %d days', $this->plugin_name ) , substr($exprirein, 1) );

		} elseif (strpos($exprirein, '+0') !== false) {
			$exprirein = sprintf( __('Today', $this->plugin_name ) , substr($exprirein, 1) );
			
		} elseif (strpos($exprirein, '+') !== false) {
			$exprirein = sprintf( __('%d days ago', $this->plugin_name ) , substr($exprirein, 1) );

		}

		if( 'expirationday_id' == $column ) {
			echo date_i18n(get_option('date_format'), $productexpired).'<br>'.$exprirein;
		}
	}

	//sortable
	/*function expirationday_content_sortable( $columns ) {
	    $columns['expirationday_id'] = 'Expiration Day';

	    return $columns;
	}*/
	
	

} //end class
