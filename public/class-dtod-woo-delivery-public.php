<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dtod.com
 * @since      1.0.0
 *
 * @package    dtod_Woo_Delivery
 * @subpackage dtod_Woo_Delivery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    dtod_Woo_Delivery
 * @subpackage dtod_Woo_Delivery/public
 * @author     dtod <admin@dtod.com>
 */
class dtod_Woo_Delivery_Public {

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

	public $helper;

	public $hpos;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		add_action( 'before_woocommerce_init', function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\OrderUtil::class ) ) {
				if ( \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
					// HPOS usage is enabled.
					$this->hpos = true;
				} else {
					// Traditional CPT-based orders are in use.
					$this->hpos = false;
				}
			}
		} );

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helper = new dtod_Woo_Delivery_Helper();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in dtod_Woo_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The dtod_Woo_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if( is_checkout() && ! ( is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' )) ) {
			wp_enqueue_style( "flatpickr_css", plugin_dir_url( __FILE__ ) . 'css/flatpickr.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dtod-woo-delivery-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in dtod_Woo_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The dtod_Woo_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if( is_checkout() && ! ( is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' )) ) {
			
			wp_enqueue_script( "flatpickr_js", plugin_dir_url( __FILE__ ) . 'js/flatpickr.min.js', ['jquery'], $this->version, true );


			$theme_name = esc_html( wp_get_theme()->get( 'Name' ) );

			$theme = wp_get_theme( );

			if(strpos($theme_name,"Flatsome") !== false || strpos($theme->parent_theme,"Flatsome") !== false) {
				wp_enqueue_script( "select2_js", plugin_dir_url( __FILE__ ) . 'js/select2.dtod.delivery.min.js', array('jquery'), $this->version, true );
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dtod-woo-delivery-public-flatsome.js', array( 'jquery','select2_js', 'flatpickr_js' ), $this->version, true );
			} else {
				wp_enqueue_script( "selectWoo_js", plugin_dir_url( __FILE__ ) . 'js/selectWoo.dtod.delivery.min.js', array('jquery'), $this->version, true );
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dtod-woo-delivery-public.js', array( 'jquery','selectWoo_js', 'flatpickr_js' ), $this->version, true );
			}

		}
		$dtod_woo_delivery_nonce = wp_create_nonce('dtod_woo_delivery_nonce');
	        wp_localize_script($this->plugin_name, 'dtod_woo_delivery_ajax_obj', array(
	            'dtod_woo_delivery_ajax_url' => admin_url('admin-ajax.php'),
	            'nonce' => $dtod_woo_delivery_nonce,
	        ));

	}


	public function dequeue_salient_theme_hoverintent_script() {
		if( is_checkout() && ! ( is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' )) ){

			$theme_name = esc_html( wp_get_theme()->get( 'Name' ) );
			$theme = wp_get_theme( );
			if(strpos($theme_name,"Salient") !== false || strpos($theme->parent_theme,"Salient") !== false) {
				wp_dequeue_script( 'hoverintent' );
			}
		}
	}


		// This function adds the delivery time and delivery date fields and it's functionalities
	public function dtod_woo_delivery_add_custom_field() {

		// get custimer email
		$user = wp_get_current_user();
		$user_email = wp_get_current_user()->user_email;

		//unset the plugin session & cookie first

		if(isset($_COOKIE['dtod_woo_delivery_option_time_pickup'])) {
		    unset($_COOKIE['dtod_woo_delivery_option_time_pickup']);
			setcookie("dtod_woo_delivery_option_time_pickup", null, -1, '/');
		} elseif(!is_null(WC()->session)) {		  
			WC()->session->__unset( 'dtod_woo_delivery_option_time_pickup' );  
		}


		// retrieving the data for delivery time
		$delivery_date_settings = get_option('dtod_woo_delivery_date_settings');
		$pickup_date_settings = get_option('dtod_woo_delivery_pickup_date_settings');
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');
		$delivery_option_settings = get_option('dtod_woo_delivery_option_delivery_settings');
		$other_settings = get_option('dtod_woo_delivery_other_settings');

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		// starting the creating of view of delivery date and delivery time

		$today = date('Y-m-d', time());
		
		echo "<div data-today_date='".$today."' data-plugin-url='".dtod_WOO_DELIVERY_URL."' id='dtod_woo_delivery_setting_wrapper'>";

		$delivery_heading_checkout = (isset($other_settings['delivery_heading_checkout']) && !empty($other_settings['delivery_heading_checkout'])) ? stripslashes($other_settings['delivery_heading_checkout']) : "";

		if($delivery_heading_checkout != "") {
			echo "<div id='dtod-woo-delivery-public-delivery-details'>";
			echo "<h3 style='margin-bottom:0;padding: 20px 0;'>".__($delivery_heading_checkout, 'woo-delivery')."</h3>";
			if ( d2d_prime_is_active_prime_member($user_email)) {
				echo "<p>SCHEDULING FOR <strong style='color:#3d9cd2;'>PRIME MEMBERS</strong> - $0</p>";
			} else {
				echo "<p><strong>WOULD YOU LIKE TO SCHEDULE YOUR DELIVERY?</strong></p>";
				echo "<button id='disableschedule' type='button'>NO</button> <button id='enableschedule' type='button'>YES</button> ";
				echo "<p>SCHEDULING FOR <strong style='color:#3d9cd2;'>NON-PRIME MEMBERS</strong> - $50</p>";
			}
			echo "</div>";
		}

		$disable_fields_for_downloadable_products = (isset(get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products']) && !empty(get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products'])) ? get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products'] : false;

		$has_virtual_downloadable_products = $this->helper->check_virtual_downloadable_products();

		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;
		$delivery_option_field_label = (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : __("Order Type", "woo-delivery");
		$delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : __("Delivery","woo-delivery");
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : __("Pickup","woo-delivery");

		if($enable_delivery_option && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			echo '<div id="dtod_woo_delivery_delivery_selection_field" style="display:none;">';
				woocommerce_form_field('dtod_woo_delivery_delivery_selection_box',
				[
					'type' => 'select',
					'class' => [
						'dtod_woo_delivery_delivery_selection_box form-row-wide'
					],
					'label' => __($delivery_option_field_label, 'woo-delivery'),
					'placeholder' => __($delivery_option_field_label, 'woo-delivery'),
				    'options' => dtod_Woo_Delivery_Delivery_Option::delivery_option($delivery_option_settings),
					'required' => true,
				], WC()->checkout->get_value('dtod_woo_delivery_delivery_selection_box'));
			echo '</div>';
		}


		$today = date('Y-m-d', time());

		$disable_dates = [];
		$pickup_disable_dates = [];

		$selectable_start_date = date('Y-m-d H:i:s', time());
		$start_date = new DateTime($selectable_start_date);

		$off_days = (isset($delivery_date_settings['off_days']) && !empty($delivery_date_settings['off_days'])) ? $delivery_date_settings['off_days'] : array();

		if(count($off_days)) {
				$date = $start_date;
				foreach ($off_days as $year => $months) {
					foreach($months as $month =>$days){
						$month_num = date_parse($month)['month'];
						if(strlen($month_num) == 1) {
							$month_num_final = "0".$month_num;
						} else {
							$month_num_final = $month_num;
						}
						$days = explode(',', $days);
						foreach($days as $day){
							$disable_dates[] = $year . "-" . $month_num_final . "-" .$day;
							$pickup_disable_dates[] = $year . "-" . $month_num_final . "-" .$day;
						}
					}
				}
			}


		// Delivery Date --------------------------------------------------------------

		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;

		$auto_select_first_date = (isset($delivery_date_settings['auto_select_first_date']) && !empty($delivery_date_settings['auto_select_first_date'])) ? $delivery_date_settings['auto_select_first_date'] : false;


		if( $enable_delivery_date && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {

			$delivery_days = isset($delivery_date_settings['delivery_days']) && $delivery_date_settings['delivery_days'] != "" ? $delivery_date_settings['delivery_days'] : "6,0,1,2,3,4,5";			

			$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? $delivery_date_settings['field_label'] : __("Delivery Date","woo-delivery");
			$delivery_date_mandatory = (isset($delivery_date_settings['delivery_date_mandatory']) && !empty($delivery_date_settings['delivery_date_mandatory'])) ? $delivery_date_settings['delivery_date_mandatory'] : false;
			$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";
			$week_starts_from = (isset($delivery_date_settings['week_starts_from']) && !empty($delivery_date_settings['week_starts_from'])) ? $delivery_date_settings['week_starts_from']:"0";
			
			$selectable_date = (isset($delivery_date_settings['selectable_date']) && !empty($delivery_date_settings['selectable_date']))?$delivery_date_settings['selectable_date']:"365";

			$delivery_days = explode(',', $delivery_days);

			$week_days = ['0', '1', '2', '3', '4', '5', '6'];
			$disable_week_days = array_values(array_diff($week_days, $delivery_days));

			$disable_dates = array_unique($disable_dates, false);
			$disable_dates = array_values($disable_dates);

			echo '<div id="dtod_woo_delivery_delivery_date_section" style="display:none;">';
			woocommerce_form_field('dtod_woo_delivery_date_field',
			[
				'type' => 'text',
				'class' => array(
				  'dtod_woo_delivery_date_field form-row-wide'
				) ,
				'id' => "dtod_woo_delivery_date_datepicker",
				'label' => $delivery_date_field_label,
				'placeholder' => $delivery_date_field_label,
				'required' => $delivery_date_mandatory,
				'custom_attributes' => [
					'data-selectable_dates' => $selectable_date,
					'data-disable_week_days' => json_encode($disable_week_days),
					'data-date_format' => $delivery_date_format,
					'data-disable_dates' => json_encode($disable_dates),
					'data-week_starts_from' => $week_starts_from,
					'data-default_date' => $auto_select_first_date,
				],
			] , WC()->checkout->get_value('dtod_woo_delivery_date_field'));
			echo '</div>';
		}

		// End Delivery Date


		// Delivery Time --------------------------------------------------------------
		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;

		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? $delivery_time_settings['field_label'] : __("Delivery Time","woo-delivery");

		$delivery_time_mandatory = (isset($delivery_time_settings['delivery_time_mandatory']) && !empty($delivery_time_settings['delivery_time_mandatory'])) ? $delivery_time_settings['delivery_time_mandatory'] : false;

		$auto_select_first_time = (isset($delivery_time_settings['auto_select_first_time']) && !empty($delivery_time_settings['auto_select_first_time'])) ? $delivery_time_settings['auto_select_first_time'] : false;

		$order_limit_notice = (isset(get_option('dtod_woo_delivery_localization_settings')['order_limit_notice']) && !empty(get_option('dtod_woo_delivery_localization_settings')['order_limit_notice'])) ? "(".get_option('dtod_woo_delivery_localization_settings')['order_limit_notice'].")" : __("(Maximum Order Limit Exceed)","woo-delivery");

		if( $enable_delivery_time && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products)) {

			echo '<div id="dtod_woo_delivery_delivery_time_section" style="display:none;">';
			
			woocommerce_form_field('dtod_woo_delivery_time_field',
			[
				'type' => 'select',
				'class' => [
					'dtod_woo_delivery_time_field form-row-wide'
				],
				'label' => __($delivery_time_field_label, "woo-delivery"),
				'placeholder' => __($delivery_time_field_label, "woo-delivery"),
				'options' => dtod_Woo_Delivery_Time_Option::delivery_time_option($delivery_time_settings),
				'required' => $delivery_time_mandatory,
				'custom_attributes' => [
					'data-default_time' => $auto_select_first_time,
					'data-order_limit_notice' => $order_limit_notice
				],
			], WC()->checkout->get_value('dtod_woo_delivery_time_field'));
			echo '</div>';
		}
		
		// End Delivery Time

		// Pickup Date --------------------------------------------------------------

		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

		if( $enable_pickup_date  && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {

			$auto_select_first_pickup_date = (isset($pickup_date_settings['auto_select_first_pickup_date']) && !empty($pickup_date_settings['auto_select_first_pickup_date'])) ? $pickup_date_settings['auto_select_first_pickup_date'] : false;			

			$pickup_days = isset($pickup_date_settings['pickup_days']) && $pickup_date_settings['pickup_days'] != "" ? $pickup_date_settings['pickup_days'] : "6,0,1,2,3,4,5";			

			$pickup_date_mandatory = (isset($pickup_date_settings['pickup_date_mandatory']) && !empty($pickup_date_settings['pickup_date_mandatory'])) ? $pickup_date_settings['pickup_date_mandatory'] : false;
			$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";	

			$pickup_week_starts_from = (isset($pickup_date_settings['week_starts_from']) && !empty($pickup_date_settings['week_starts_from'])) ? $pickup_date_settings['week_starts_from']:"0";
			
			$pickup_selectable_date = (isset($pickup_date_settings['selectable_date']) && !empty($pickup_date_settings['selectable_date']))?$pickup_date_settings['selectable_date']:"365";

			$pickup_days = explode(',', $pickup_days);

			$week_days = ['0', '1', '2', '3', '4', '5', '6'];
			$pickup_disable_week_days = array_values(array_diff($week_days, $pickup_days));


			$pickup_disable_dates = array_unique($pickup_disable_dates, false);
			$pickup_disable_dates = array_values($pickup_disable_dates);

			$pickup_date_field_heading = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __("Pickup Date","woo-delivery");

			echo '<div id="dtod_woo_delivery_pickup_date_section" style="display:none;">';

			woocommerce_form_field('dtod_woo_delivery_pickup_date_field',
			[
				'type' => 'text',
				'class' => array(
				  'dtod_woo_delivery_pickup_date_field form-row-wide'
				) ,
				'id' => "dtod_woo_delivery_pickup_date_datepicker",
				'label' => __($pickup_date_field_heading, 'woo-delivery'),
				'placeholder' => __($pickup_date_field_heading, 'woo-delivery'),
				'required' => $pickup_date_mandatory, 
				'custom_attributes' => [
					'data-pickup_selectable_dates' => $pickup_selectable_date,
					'data-pickup_disable_week_days' => json_encode($pickup_disable_week_days),
					'data-pickup_date_format' => $pickup_date_format,
					'data-pickup_disable_dates' => json_encode($pickup_disable_dates),
					'data-pickup_week_starts_from' => $pickup_week_starts_from,
					'data-pickup_default_date' => $auto_select_first_pickup_date,
				],
			] , WC()->checkout->get_value('dtod_woo_delivery_pickup_date_field'));
			echo '</div>';

		}

		// End Pickup Date


		// Pickup Time --------------------------------------------------------------
		
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : __("Pickup Time","woo-delivery");

		$pickup_time_mandatory = (isset($pickup_time_settings['pickup_time_mandatory']) && !empty($pickup_time_settings['pickup_time_mandatory'])) ? $pickup_time_settings['pickup_time_mandatory'] : false;
		$pickup_auto_select_first_time = (isset($pickup_time_settings['auto_select_first_time']) && !empty($pickup_time_settings['auto_select_first_time'])) ? $pickup_time_settings['auto_select_first_time'] : false;

		$pickup_limit_notice = (isset(get_option('dtod_woo_delivery_localization_settings')['pickup_limit_notice']) && !empty(get_option('dtod_woo_delivery_localization_settings')['pickup_limit_notice'])) ? "(".stripslashes(get_option('dtod_woo_delivery_localization_settings')['pickup_limit_notice']).")" : __("(Maximum Pickup Limit Exceed)","woo-delivery");

		if($enable_pickup_time && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products)) {


			echo '<div id="dtod_woo_delivery_pickup_time_section" style="display:none;">';

			woocommerce_form_field('dtod_woo_delivery_pickup_time_field',
			[
				'type' => 'select',
				'class' => [
					'dtod_woo_delivery_pickup_time_field form-row-wide'
				],
				'label' => __($pickup_time_field_label, 'woo-delivery'),
				'placeholder' => __($pickup_time_field_label, 'woo-delivery'),
				'options' => dtod_Woo_Delivery_Pickup_Option::pickup_time_option($pickup_time_settings),
				'required' => $pickup_time_mandatory,
				'custom_attributes' => [
					'data-default_time' => $pickup_auto_select_first_time,
					'data-pickup_limit_notice' => $pickup_limit_notice,
				],
			], WC()->checkout->get_value('dtod_woo_delivery_pickup_time_field'));
			echo '</div>';

		}
		// End Pickup Time

		echo "</div>";
	}

	/**
	 * Checkout Process
	*/	
	public function dtod_woo_delivery_customise_checkout_field_process() {
		
		$delivery_option_settings = get_option('dtod_woo_delivery_option_delivery_settings');
		$delivery_date_settings = get_option('dtod_woo_delivery_date_settings');
		$pickup_date_settings = get_option('dtod_woo_delivery_pickup_date_settings');
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$delivery_date_mandatory = (isset($delivery_date_settings['delivery_date_mandatory']) && !empty($delivery_date_settings['delivery_date_mandatory'])) ? $delivery_date_settings['delivery_date_mandatory'] : false;

		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;
		$pickup_date_mandatory = (isset($pickup_date_settings['pickup_date_mandatory']) && !empty($pickup_date_settings['pickup_date_mandatory'])) ? $pickup_date_settings['pickup_date_mandatory'] : false;


		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
		$delivery_time_mandatory = (isset($delivery_time_settings['delivery_time_mandatory']) && !empty($delivery_time_settings['delivery_time_mandatory'])) ? $delivery_time_settings['delivery_time_mandatory'] : false;


		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		$pickup_time_mandatory = (isset($pickup_time_settings['pickup_time_mandatory']) && !empty($pickup_time_settings['pickup_time_mandatory'])) ? $pickup_time_settings['pickup_time_mandatory'] : false;

		$disable_fields_for_downloadable_products = (isset(get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products']) && !empty(get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products'])) ? get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products'] : false;

		$checkout_notice = get_option('dtod_woo_delivery_localization_settings');
		$checkout_delivery_option_notice = (isset($checkout_notice['checkout_delivery_option_notice']) && !empty($checkout_notice['checkout_delivery_option_notice'])) ? stripslashes($checkout_notice['checkout_delivery_option_notice']) : __("Please Select Your Order Type.","woo-delivery");
		$checkout_date_notice = (isset($checkout_notice['checkout_date_notice']) && !empty($checkout_notice['checkout_date_notice'])) ? stripslashes($checkout_notice['checkout_date_notice']) : __("Please Enter Delivery Date.","woo-delivery");
		$checkout_pickup_date_notice = (isset($checkout_notice['checkout_pickup_date_notice']) && !empty($checkout_notice['checkout_pickup_date_notice'])) ? stripslashes($checkout_notice['checkout_pickup_date_notice']) : __("Please Enter Pickup Date.","woo-delivery");
		$checkout_time_notice = (isset($checkout_notice['checkout_time_notice']) && !empty($checkout_notice['checkout_time_notice'])) ? stripslashes($checkout_notice['checkout_time_notice']) : __("Please Enter Delivery Time.","woo-delivery");	
		$checkout_pickup_time_notice = (isset($checkout_notice['checkout_pickup_time_notice']) && !empty($checkout_notice['checkout_pickup_time_notice'])) ? stripslashes($checkout_notice['checkout_pickup_time_notice']) : __("Please Enter Pickup Time.","woo-delivery");	

		

		$has_virtual_downloadable_products = $this->helper->check_virtual_downloadable_products();

		if(isset($_COOKIE['dtod_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['dtod_woo_delivery_option_time_pickup'];
		} elseif(!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'dtod_woo_delivery_option_time_pickup' );
		}

		if ($enable_delivery_option && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products)) {
			if (!isset($_POST['dtod_woo_delivery_delivery_selection_box'])) wc_add_notice(__($checkout_delivery_option_notice, "woo-delivery") , 'error');
		}

		// if the field is set, if not then show an error message.

		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_date && $delivery_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && isset($_POST['dtod_woo_delivery_date_field'])) {
			if ($_POST['dtod_woo_delivery_date_field'] == "") wc_add_notice(__($checkout_date_notice, "woo-delivery") , 'error');
		} elseif (!$enable_delivery_option && $enable_delivery_date && $delivery_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && isset($_POST['dtod_woo_delivery_date_field'])) {
			if ($_POST['dtod_woo_delivery_date_field'] == "") wc_add_notice(__($checkout_date_notice, "woo-delivery") , 'error');
		}


		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_date && $pickup_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && isset($_POST['dtod_woo_delivery_pickup_date_field'])) {
			if ($_POST['dtod_woo_delivery_pickup_date_field'] == "") wc_add_notice(__($checkout_pickup_date_notice, "woo-delivery") , 'error');
		} elseif (!$enable_delivery_option && $enable_pickup_date && $pickup_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && isset($_POST['dtod_woo_delivery_pickup_date_field'])) {
			if ($_POST['dtod_woo_delivery_pickup_date_field'] == "") wc_add_notice(__($checkout_pickup_date_notice, "woo-delivery") , 'error');
		}

		// if the field is set, if not then show an error message.
		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_time && $delivery_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products)) {

			if (!$_POST['dtod_woo_delivery_time_field']) wc_add_notice(__($checkout_time_notice, "woo-delivery") , 'error');


			if(($enable_delivery_date && $_POST['dtod_woo_delivery_date_field'] && !empty($_POST['dtod_woo_delivery_date_field'])) && ($enable_delivery_time && $_POST['dtod_woo_delivery_time_field'] && $_POST['dtod_woo_delivery_time_field'] != "")) {
				$this->check_delivery_quantity_before_placed($_POST['dtod_woo_delivery_date_field'],$_POST['dtod_woo_delivery_time_field']);
			} elseif((!$enable_delivery_date) && ($enable_delivery_time && $_POST['dtod_woo_delivery_time_field'] && $_POST['dtod_woo_delivery_time_field'] != "")) {

				$this->check_delivery_quantity_before_placed('no_date',$_POST['dtod_woo_delivery_time_field'],true);

			}
			
		} elseif (!$enable_delivery_option && $enable_delivery_time && $delivery_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products)) {
			if (!$_POST['dtod_woo_delivery_time_field']) wc_add_notice(__($checkout_time_notice, "woo-delivery") , 'error');
			if(($enable_delivery_date && $_POST['dtod_woo_delivery_date_field'] && !empty($_POST['dtod_woo_delivery_date_field'])) && ($enable_delivery_time && $_POST['dtod_woo_delivery_time_field'] && !empty($_POST['dtod_woo_delivery_time_field']) )) {
				$this->check_delivery_quantity_before_placed($_POST['dtod_woo_delivery_date_field'],$_POST['dtod_woo_delivery_time_field']);
			} elseif((!$enable_delivery_date) && ($enable_delivery_time && $_POST['dtod_woo_delivery_time_field'] && !empty($_POST['dtod_woo_delivery_time_field']) )) {

				$this->check_delivery_quantity_before_placed('no_date',$_POST['dtod_woo_delivery_time_field'],true);

			}
		}
		
		// if the field is set, if not then show an error message.
		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_time && $pickup_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products)) {
			if (!$_POST['dtod_woo_delivery_pickup_time_field']) wc_add_notice(__($checkout_pickup_time_notice, "woo-delivery") , 'error');

			if(($enable_pickup_date && $_POST['dtod_woo_delivery_pickup_date_field'] && !empty($_POST['dtod_woo_delivery_pickup_date_field'])) && ($enable_pickup_time && $_POST['dtod_woo_delivery_pickup_time_field'] && !empty($_POST['dtod_woo_delivery_pickup_time_field']))) {
				$this->check_pickup_quantity_before_placed($_POST['dtod_woo_delivery_pickup_date_field'],$_POST['dtod_woo_delivery_pickup_time_field']);
			} elseif((!$enable_pickup_date) && ($enable_pickup_time && $_POST['dtod_woo_delivery_pickup_time_field'] && !empty($_POST['dtod_woo_delivery_pickup_time_field']))) {

				$this->check_pickup_quantity_before_placed('no_date',$_POST['dtod_woo_delivery_pickup_time_field'],true);

			}



		} elseif(!$enable_delivery_option && $enable_pickup_time && $pickup_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products)) {
			if (!$_POST['dtod_woo_delivery_pickup_time_field']) wc_add_notice(__($checkout_pickup_time_notice, "woo-delivery") , 'error');
			if(($enable_pickup_date && $_POST['dtod_woo_delivery_pickup_date_field'] && !empty($_POST['dtod_woo_delivery_pickup_date_field'])) && ($enable_pickup_time && $_POST['dtod_woo_delivery_pickup_time_field'] && !empty($_POST['dtod_woo_delivery_pickup_time_field']))) {
				$this->check_pickup_quantity_before_placed($_POST['dtod_woo_delivery_pickup_date_field'],$_POST['dtod_woo_delivery_pickup_time_field']);
			} elseif((!$enable_pickup_date) && ($enable_pickup_time && $_POST['dtod_woo_delivery_pickup_time_field'] && !empty($_POST['dtod_woo_delivery_pickup_time_field']))) {

				$this->check_pickup_quantity_before_placed('no_date',$_POST['dtod_woo_delivery_pickup_time_field'],true);

			}
		}
		
	}

	public function check_delivery_quantity_before_placed($delivery_date,$delivery_time,$no_delivery_date = false) {
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		if($delivery_date == "no_date") {
			$delivery_date = date('Y-m-d', time());
		}
		$delivery_time = sanitize_text_field($delivery_time);
	    if($no_delivery_date) {
			$order_date = date("Y-m-d", (int)sanitize_text_field(strtotime($delivery_date)));
			$selected_date = $order_date; 

			if($this->hpos) {
		    	$args = array(
			        'limit' => -1,
					'type' => array( 'shop_order' ),
					'date_created' => $order_date,
					'meta_query' => array(
			            array(
			                'key'     => 'delivery_time',
			                'value'   => $delivery_time,
			                'compare' => '==',
			            ),
			            array(
			                'key'     => 'delivery_type',
			                'value'   => 'delivery',
			                'compare' => '==',
			            ),
			        ),
			        'return' => 'ids'
			    );
		    } else {
		    	$args = array(
			        'limit' => -1,
			        'date_created' => $order_date,
			        'delivery_time' => $delivery_time,
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
		    }

		} else {
			$selected_date = date("Y-m-d", strtotime(sanitize_text_field($delivery_date)));
		    if($this->hpos) {
		    	$args = array(
			        'limit' => -1,
					'type' => array( 'shop_order' ),
					'meta_query' => array(
			            array(
			                'key'     => 'delivery_date',
			                'value'   => date("Y-m-d", strtotime(sanitize_text_field($delivery_date))),
			                'compare' => '==',
			            ),
			            array(
			                'key'     => 'delivery_time',
			                'value'   => $delivery_time,
			                'compare' => '==',
			            ),
			            array(
			                'key'     => 'delivery_type',
			                'value'   => 'delivery',
			                'compare' => '==',
			            ),
			        ),
			        'return' => 'ids'
			    );
		    } else {
		    	$args = array(
			        'limit' => -1,
			        'delivery_date' => date("Y-m-d", strtotime(sanitize_text_field($delivery_date))),
			        'delivery_time' => $delivery_time,
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
		    }	    
		}

	    $order_ids = wc_get_orders( $args );

	    if($delivery_time != "") {
        	$delivery_times = explode(' - ', $delivery_time);
			$slot_key_one = explode(':', $delivery_times[0]);
			$slot_key_two = explode(':', $delivery_times[1]);
			$delivery_time = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]).' - '.((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);
			$delivery_times = explode(" - ",$delivery_time);
			$delivery_time_last_time = ((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);   		
		}

		$today = date('Y-m-d', time());
		$current_time = (date("G")*60)+date("i");

		if($today == $selected_date && $current_time > $delivery_time_last_time) wc_add_notice(__('Selected delivery time already passed. Please Reload The Page', "woo-delivery") , 'error');


	    $time_settings = get_option('dtod_woo_delivery_time_settings');
  		$x = (int)$time_settings['delivery_time_starts'];
  		$each_time_slot = (isset($time_settings['each_time_slot']) && !empty($time_settings['each_time_slot'])) ? (int)$time_settings['each_time_slot'] : (int)$time_settings['delivery_time_ends']-(int)$time_settings['delivery_time_starts'];
  		$max_order = (isset($time_settings['max_order_per_slot']) && $time_settings['max_order_per_slot'] != "") ? $time_settings['max_order_per_slot'] : 10000000000000;

		while((int)$time_settings['delivery_time_ends']>$x) {
			$second_time = $x+$each_time_slot;
			if($second_time > (int)$time_settings['delivery_time_ends']) {
				$second_time = (int)$time_settings['delivery_time_ends'];
			}
			$key = $x . ' - ' . $second_time; 
			if(!empty($delivery_time) && ($delivery_time == $key) ) {	
				$time_max_order = (int)$max_order;
				if (count($order_ids)>=$time_max_order) {
					wc_add_notice(__('Maximum Order Limit Exceed For This Time Slot. Please Reload The Page', "woo-delivery") , 'error');
				}

				break; 
		    }
			$x = $second_time;
		}

	}


	public function check_pickup_quantity_before_placed($pickup_date,$pickup_time,$no_pickup_date = false) {
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		if($pickup_date == 'no_date') {
			$pickup_date = date('Y-m-d', time());
		}
		$pickup_time = sanitize_text_field($pickup_time);
	    if($no_pickup_date) {
			$order_date = date("Y-m-d", (int)sanitize_text_field(strtotime($pickup_date)));
			$selected_date = $order_date;

		    if($this->hpos) {
			    	$args = array(
				        'limit' => -1,
						'type' => array( 'shop_order' ),
						'date_created' => $order_date,
						'meta_query' => array(
				            array(
				                'key'     => 'pickup_time',
				                'value'   => $pickup_time,
				                'compare' => '==',
				            ),
				            array(
				                'key'     => 'delivery_type',
				                'value'   => 'pickup',
				                'compare' => '==',
				            ),
				        ),
				        'return' => 'ids'
				    );
			    } else {
			    	$args = array(
				        'limit' => -1,
				        'date_created' => $order_date,
				        'pickup_time' => $pickup_time,
				        'delivery_type' => "pickup",
				        'return' => 'ids'
				    );
			    }

		} else {
			$selected_date = date("Y-m-d", strtotime(sanitize_text_field($pickup_date)));
		    if($this->hpos) {
			    	$args = array(
				        'limit' => -1,
						'type' => array( 'shop_order' ),
						'meta_query' => array(
				            array(
				                'key'     => 'pickup_time',
				                'value'   => $pickup_time,
				                'compare' => '==',
				            ),
				            array(
				                'key'     => 'pickup_date',
				                'value'   => date("Y-m-d", strtotime(sanitize_text_field($pickup_date))),
				                'compare' => '==',
				            ),
				            array(
				                'key'     => 'delivery_type',
				                'value'   => 'pickup',
				                'compare' => '==',
				            ),
				        ),
				        'return' => 'ids'
				    );
			    } else {
			    	$args = array(
				        'limit' => -1,
				        'pickup_date' => date("Y-m-d", strtotime(sanitize_text_field($pickup_date))),
				        'pickup_time' => $pickup_time,
				        'delivery_type' => "pickup",
				        'return' => 'ids'
				    );
			    }		    
		}

	    $order_ids = wc_get_orders( $args );

	    if($pickup_time != "") {
        	if(strpos($pickup_time, ' - ') !== false) {
        		$pickup_times = explode(' - ', $pickup_time);
				$slot_key_one = explode(':', $pickup_times[0]);
				$slot_key_two = explode(':', $pickup_times[1]);
				$pickup_time = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]).' - '.((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);
				$pickup_times = explode(" - ",$pickup_time);
				$pickup_time_last_time = ((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);
        	} else {
        		$pickup_times = [];
        		$slot_key_one = explode(':', $pickup_time);
        		$pickup_time = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]);
        		$pickup_times[] = $pickup_time;
        	}
    		
		}

		$today = date('Y-m-d', time());
		$current_time = (date("G")*60)+date("i");

		if($today == $selected_date && $current_time > $pickup_time_last_time) wc_add_notice(__('Selected pickup time already passed. Please Reload The Page', "woo-delivery") , 'error');


	    $pickup_settings = get_option('dtod_woo_delivery_pickup_settings');
  		$x = (int)$pickup_settings['pickup_time_starts'];
  		$each_time_slot = (isset($pickup_settings['each_time_slot']) && !empty($pickup_settings['each_time_slot'])) ? (int)$pickup_settings['each_time_slot'] : (int)$pickup_settings['pickup_time_ends']-(int)$pickup_settings['pickup_time_starts'];
  		$max_order = (isset($pickup_settings['max_pickup_per_slot']) && $pickup_settings['max_pickup_per_slot'] != "") ? $pickup_settings['max_pickup_per_slot'] : 10000000000000;

		while((int)$pickup_settings['pickup_time_ends']>$x) {
			$second_time = $x+$each_time_slot;
			if($second_time > (int)$pickup_settings['pickup_time_ends']) {
				$second_time = (int)$pickup_settings['pickup_time_ends'];
			}
			$key = $x . ' - ' . $second_time; 
			if(!empty($pickup_time) && ($pickup_time == $key) ) {	
				$pickup_max_order = (int)$max_order;
				if (count($order_ids)>=$pickup_max_order) {
					wc_add_notice(__('Maximum Order Limit Exceed For This Pickup Slot. Please Reload The Page', "woo-delivery") , 'error');
				}

				break; 
		    }
			$x = $second_time;
		}

	}

	/**
	 * Update value of field
	*/
	public function dtod_woo_delivery_customise_checkout_field_update_order_meta($order_id) {

		$order = wc_get_order( $order_id );
		
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		if(isset($_POST['dtod_woo_delivery_date_field'])) {
			$en_delivery_date = sanitize_text_field($_POST['dtod_woo_delivery_date_field']);
		}
		
		if(isset($_POST['dtod_woo_delivery_pickup_date_field'])) {
			$en_pickup_date = sanitize_text_field($_POST['dtod_woo_delivery_pickup_date_field']);
		}
		
		$delivery_option_settings = get_option('dtod_woo_delivery_option_delivery_settings');
		$delivery_date_settings = get_option('dtod_woo_delivery_date_settings');
		$pickup_date_settings = get_option('dtod_woo_delivery_pickup_date_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');
		$prime_delivery_settings = get_option('dtod_woo_delivery_prime_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;

		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$disable_fields_for_downloadable_products = (isset(get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products']) && !empty(get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products'])) ? get_option('dtod_woo_delivery_other_settings')['disable_fields_for_downloadable_products'] : false;

		$has_virtual_downloadable_products = $this->helper->check_virtual_downloadable_products();

		$previousErrorLevel = error_reporting();
		error_reporting(\E_ERROR);
	  	
		if ($enable_delivery_option && $_POST['dtod_woo_delivery_delivery_selection_box'] != "" ) {
			if($this->hpos) {
				$order->update_meta_data( 'delivery_type', $_POST['dtod_woo_delivery_delivery_selection_box'] );
			} else {
				update_post_meta($order_id, 'delivery_type', $_POST['dtod_woo_delivery_delivery_selection_box']);
			}
		} elseif(!$enable_delivery_option && (($enable_delivery_time && !$enable_pickup_time) || ($enable_delivery_date && !$enable_pickup_date)) && $_POST['dtod_woo_delivery_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'delivery_type', 'delivery' );
			} else {
				update_post_meta($order_id, 'delivery_type', 'delivery');
			}
		} elseif(!$enable_delivery_option && ((!$enable_delivery_time && $enable_pickup_time) || (!$enable_delivery_date && $enable_pickup_date)) && $_POST['dtod_woo_delivery_pickup_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'delivery_type', 'pickup' );
			} else {
				update_post_meta($order_id, 'delivery_type', 'pickup');
			}
		}


		if(isset($_COOKIE['dtod_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['dtod_woo_delivery_option_time_pickup'];
		} elseif(!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'dtod_woo_delivery_option_time_pickup' );
		}

	  	if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_date && $_POST['dtod_woo_delivery_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'delivery_date', date("Y-m-d", strtotime($en_delivery_date)) );
			} else {
				update_post_meta($order_id, 'delivery_date', date("Y-m-d", strtotime($en_delivery_date)));
			}
		} elseif (!$enable_delivery_option && $enable_delivery_date && $_POST['dtod_woo_delivery_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'delivery_date', date("Y-m-d", strtotime($en_delivery_date)) );
			} else {
				update_post_meta($order_id, 'delivery_date', date("Y-m-d", strtotime($en_delivery_date)));
			}
		}

		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_date && $_POST['dtod_woo_delivery_pickup_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'pickup_date', date("Y-m-d", strtotime($en_pickup_date)) );
			} else {
				update_post_meta($order_id, 'pickup_date', date("Y-m-d", strtotime($en_pickup_date)));
			}
		} elseif (!$enable_delivery_option && $enable_pickup_date && $_POST['dtod_woo_delivery_pickup_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'pickup_date', date("Y-m-d", strtotime($en_pickup_date)) );
			} else {
				update_post_meta($order_id, 'pickup_date', date("Y-m-d", strtotime($en_pickup_date)));
			}
		}


		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_time && $_POST['dtod_woo_delivery_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'delivery_time', sanitize_text_field($_POST['dtod_woo_delivery_time_field']) );
			} else {
				update_post_meta($order_id, 'delivery_time', sanitize_text_field($_POST['dtod_woo_delivery_time_field']));
			}
		} elseif (!$enable_delivery_option && $enable_delivery_time && $_POST['dtod_woo_delivery_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'delivery_time', sanitize_text_field($_POST['dtod_woo_delivery_time_field']) );
			} else {
				update_post_meta($order_id, 'delivery_time', sanitize_text_field($_POST['dtod_woo_delivery_time_field']));
			}
		}

		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_time && $_POST['dtod_woo_delivery_pickup_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'pickup_time', sanitize_text_field($_POST['dtod_woo_delivery_pickup_time_field']) );
			} else {
				update_post_meta($order_id, 'pickup_time', sanitize_text_field($_POST['dtod_woo_delivery_pickup_time_field']));
			}
		} elseif(!$enable_delivery_option && $enable_pickup_time && $_POST['dtod_woo_delivery_pickup_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) ) {
			if($this->hpos) {
				$order->update_meta_data( 'pickup_time', sanitize_text_field($_POST['dtod_woo_delivery_pickup_time_field']) );
			} else {
				update_post_meta($order_id, 'pickup_time', sanitize_text_field($_POST['dtod_woo_delivery_pickup_time_field']));
			}
		}

		error_reporting($previousErrorLevel);
		if($this->hpos) {
	  		$order->save();
		}

	}

	// CHECK FOR PRIME USER EMAIL EXIST (CLALLA)
	function d2d_prime_is_active_prime_member($email) {
		global $wpdb;
		$prime_members_table = d2d_prime_get_table_name();

		$result = $wpdb->get_var(sprintf("SELECT `active_subscription` FROM $prime_members_table WHERE email='%s'", $email));
		return $result;
	}

	// SET DATA AJAX  (CLALLA)
	function d2d_woo_get_ajax_data() {
		if(isset($_POST['orderscheduled'])) {
			$schedule = sanitize_key($_POST['orderscheduled']);
			WC()->session->set('orderscheduled', $schedule);
			echo json_encode($schedule);
		}
		die();
	}

	// ADD SCHEDULE FEE TO CHECKOUT (CLALLA)
	public function d2d_woo_add_schedule($cart) { 
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		
		global $woocommerce; 
		$domain = "woocommerce";
		// RESET SESSION ON CART PAGE
		if ( is_page( 'cart' ) || is_cart() ) {
			WC()->session->__unset('orderscheduled');
		}
		$scheduled = WC()->session->get('orderscheduled');
		
		$prime_delivery_settings = get_option('dtod_woo_delivery_prime_settings');
		$prime_default_schedule_amount = (isset($prime_delivery_settings['default_prime_amount']) && !empty($prime_delivery_settings['default_prime_amount'])) ? stripslashes($prime_delivery_settings['default_prime_amount']) : "";
		$prime_product_id = (isset($prime_delivery_settings['prime_prod_id']) && !empty($prime_delivery_settings['prime_prod_id'])) ? stripslashes($prime_delivery_settings['prime_prod_id']) : "";
		$user_email = wp_get_current_user()->user_email;

		// CHECK IF PRIME IN CART
		$product_cart_id = WC()->cart->generate_cart_id( $prime_product_id );
   		$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

		// TRY PRIME
		$prime_try_product_id = (isset($prime_delivery_settings['prime_try_prod_id']) && !empty($prime_delivery_settings['prime_try_prod_id'])) ? stripslashes($prime_delivery_settings['prime_try_prod_id']) : "";
		$product_tryprime_cart_id = WC()->cart->generate_cart_id( $prime_try_product_id );
		$try_prime_in_cart = WC()->cart->find_product_in_cart( $product_tryprime_cart_id );
		
		   if ( !d2d_prime_is_active_prime_member($user_email) && isset($scheduled) && $scheduled == "true") {
			if($in_cart || $try_prime_in_cart) {
				$label = __("Schedule Fee", $domain);
				$primefee = 0.00;
			}else{
				$label = __("Schedule Fee", $domain);
				$primefee = $prime_default_schedule_amount;
			}	
		}else{
			$label = __("Schedule Fee", $domain);
			$primefee = 0.00;
		}
		if(isset($primefee)){
			$cart->add_fee($label, $primefee);
		}

	}

	// UPDATE SHIPPING COST FOR PRIME USERS (CLALLA)
	public function dtod_woo_package_prime_rates( $rates, $package ) {
		if ( ! $_POST || ( is_admin() && ! defined( 'DOING_AJAX' ) ) ) {
			return;
		}

		$prime_delivery_settings = get_option('dtod_woo_delivery_prime_settings');
		$prime_set_purchase_limit = (isset($prime_delivery_settings['purchase_limit']) && !empty($prime_delivery_settings['purchase_limit'])) ? stripslashes($prime_delivery_settings['purchase_limit']) : "";
		$prime_set_default_delivery = (isset($prime_delivery_settings['default_delivery']) && !empty($prime_delivery_settings['default_delivery'])) ? stripslashes($prime_delivery_settings['default_delivery']) : "";
		$prime_set_non_prime_amount = (isset($prime_delivery_settings['non_prime_amount']) && !empty($prime_delivery_settings['non_prime_amount'])) ? stripslashes($prime_delivery_settings['non_prime_amount']) : "";
		$prime_product_id = (isset($prime_delivery_settings['prime_prod_id']) && !empty($prime_delivery_settings['prime_prod_id'])) ? stripslashes($prime_delivery_settings['prime_prod_id']) : "";
		$order_total = WC()->cart->get_subtotal();
		$shipping_total_wc = WC()->cart->get_shipping_total();
		$user = wp_get_current_user();
		$user_email = wp_get_current_user()->user_email;

		// CHECK IF PRIME IN CART
		$product_cart_id = WC()->cart->generate_cart_id( $prime_product_id );
   		$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

		// TRY PRIME
		$prime_try_product_id = (isset($prime_delivery_settings['prime_try_prod_id']) && !empty($prime_delivery_settings['prime_try_prod_id'])) ? stripslashes($prime_delivery_settings['prime_try_prod_id']) : "";
		$product_tryprime_cart_id = WC()->cart->generate_cart_id( $prime_try_product_id );
		$try_prime_in_cart = WC()->cart->find_product_in_cart( $product_tryprime_cart_id );

		foreach($rates as $key => $rate) {
			$rates[$key]->label = 'Delivery';
			$rates[$key]->cost = $prime_set_default_delivery;
			$rates[$key]->method_id = 'flat_rate';
			
			if ( d2d_prime_is_active_prime_member($user_email)) {
				$rates[$key]->label = 'Free Delivery';
				$rates[$key]->cost = 0;
			}elseif($in_cart || $try_prime_in_cart){
				$rates[$key]->label = 'Free Delivery';
				$rates[$key]->cost = 0;
			}else{
				if($order_total >= $prime_set_purchase_limit) {
					$newshipping = $prime_set_default_delivery;
					$rates[$key]->label = 'Delivery Fee';
					$rates[$key]->cost = $newshipping;
				} else {
					$newshipping = $prime_set_default_delivery + $prime_set_non_prime_amount;
					$rates[$key]->label = 'Delivery Fee';
					$rates[$key]->cost = $newshipping;
				}
			}
		}
			
		return $rates;
	}

	// JQUERY DYNAMIC RELOAD CHECKOUT (CLALLA) WORKS
	public function dtod_woo_prime_js() {
		//error_log("--------------------------------------------------------------------");
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
		
		// PRIME DB CALLS
		$prime_delivery_settings = get_option('dtod_woo_delivery_prime_settings');
		$prime_set_non_prime_amount = (isset($prime_delivery_settings['non_prime_amount']) && !empty($prime_delivery_settings['non_prime_amount'])) ? stripslashes($prime_delivery_settings['non_prime_amount']) : "50";
		$prime_set_default_prime_amount = (isset($prime_delivery_settings['default_prime_amount']) && !empty($prime_delivery_settings['default_prime_amount'])) ? stripslashes($prime_delivery_settings['default_prime_amount']) : "0";
		$prime_set_purchase_limit = (isset($prime_delivery_settings['purchase_limit']) && !empty($prime_delivery_settings['purchase_limit'])) ? stripslashes($prime_delivery_settings['purchase_limit']) : "";
		$prime_product_id = (isset($prime_delivery_settings['prime_prod_id']) && !empty($prime_delivery_settings['prime_prod_id'])) ? stripslashes($prime_delivery_settings['prime_prod_id']) : "";

		$user = wp_get_current_user();
		$order_total = WC()->cart->total;
		$shipping_total_wc = WC()->cart->get_shipping_total();
		WC()->cart->add_fee(__('Additional Fee', 'woocommerce'), 55);

		$user_email = wp_get_current_user()->user_email;

		// CHECK IF PRIME IN CART
		$product_cart_id = WC()->cart->generate_cart_id( $prime_product_id );
   		$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

		// TRY PRIME
		$prime_try_product_id = (isset($prime_delivery_settings['prime_try_prod_id']) && !empty($prime_delivery_settings['prime_try_prod_id'])) ? stripslashes($prime_delivery_settings['prime_try_prod_id']) : "";
		$product_tryprime_cart_id = WC()->cart->generate_cart_id( $prime_try_product_id );
		$try_prime_in_cart = WC()->cart->find_product_in_cart( $product_tryprime_cart_id );

		if ( ! d2d_prime_is_active_prime_member($user_email)) {
			if( $order_total <= $prime_set_purchase_limit ) {
				$primecrt = number_format( floatval($prime_set_default_prime_amount),2,".","," );
				$primecrtjs = $prime_set_default_prime_amount;
			} elseif($in_cart || $prime_try_product_id) {
				$primecrt = number_format( '0',2,".","," );
				$primecrtjs = '0';
			} else {
				$primecrt = number_format( floatval($prime_set_default_prime_amount),2,".","," );
				$primecrtjs = $prime_set_default_prime_amount;
			}
		} else {
			$primecrt = number_format( '0',2,".","," );
			$primecrtjs = '0';
		}
		if ( is_checkout() && ! is_wc_endpoint_url()) :

			WC()->session->__unset('orderscheduled');
		?>
		<script type="text/javascript" >
			jQuery(document).ready(function($) {
				var primeprice = '<?php echo $primecrt; ?>';

				<?php if( ! d2d_prime_is_active_prime_member($user_email) ) { ?>
				$("#dtod_woo_delivery_time_field, #dtod_woo_delivery_date_datepicker").attr('disabled', 'disabled');
				$("#disableschedule").css({"backgroundColor":"#aed136","color":"white"});
				<?php } ?>

    			$(document).on("change", "#dtod_woo_delivery_time_field", function() {
					var dateval = $("#dtod_woo_delivery_date_datepicker").val();
					if(!$("#dtod_woo_delivery_date_datepicker").val()) {
						if( !$('#datealert').length ) {
							$("#dtod_woo_delivery_date_datepicker_field").append('<span id="datealert" style="color:red;">Please select a delivery date!</span>');
							$('#place_order').prop('disabled', true);
						}
					}else{
						$('#datealert').remove();
						$('#place_order').prop('disabled', false);
					}
					jQuery.ajax({
						type: 'POST',
						url: wc_checkout_params.ajax_url,
						data: {
							action: 'd2d_woo_get_ajax_data',
							orderscheduled: 'true',
						},
						success: function (code) {
                			console.log(code);
                			jQuery('body').trigger('update_checkout', {  });
            			},
					});
   				 });
					$(document).on("click", "#clearprime, #disableschedule",function() {
						jQuery.ajax({
						type: 'POST',
						url: wc_checkout_params.ajax_url,
						data: {
							action: 'd2d_woo_get_ajax_data',
							orderscheduled: 'false',
						},
						success: function (code) {
                			console.log(code);
							if($('#place_order').prop('disabled', false)) {
								$('#datealert').remove();
								$('#place_order').prop('disabled', false);
							}
                			jQuery('body').trigger('update_checkout', {  });
            			},
						});
						// Disable schedule button
						$("#dtod_woo_delivery_time_field, #dtod_woo_delivery_date_datepicker").attr('disabled', 'disabled');
						$("#disableschedule").css({"backgroundColor":"#aed136","color":"white"});
						$('#enableschedule').removeAttr('style');
						// remove schedule fields
						$('#dtod_woo_delivery_date_datepicker, #dtod_woo_delivery_time_field').val('');
						$("#dtod_woo_delivery_time_field").val(0);
						jQuery('body').trigger('update_checkout', {  });
					});
					$(document).on("click", "#enableschedule", function() {
						$("#dtod_woo_delivery_time_field, #dtod_woo_delivery_date_datepicker").removeAttr('disabled');
						$("#enableschedule").css({"backgroundColor":"#aed136","color":"white"});
						$('#disableschedule').removeAttr('style');
						if( !$('#datealert').length ) {
							$("#dtod_woo_delivery_time_field").append('<span id="datealert" style="color:red;">Please select a delivery time!</span>');
							$('#place_order').prop('disabled', true);
						}
						jQuery('body').trigger('update_checkout', {  });
					});
					$(document).on("mouseenter", "#place_order", function() {
						if($("#dtod_woo_delivery_date_datepicker").val() && !$("#dtod_woo_delivery_time_field").val()) {
							$("#dtod_woo_delivery_time_field").append('<span id="datealert" style="color:red;">Please select a delivery time!</span>');
							$('#place_order').prop('disabled', true);
						}else{
							$('#datealert').remove();
							$('#place_order').prop('disabled', false);
						}
					});
			});
			
		</script>
		<?php
		endif;
	}

	// ADD CLOSE ICON NEXT TO SCHEDULE FEE (CLALLA)
	public function d2d_an_icon_next_to_schedule_fee_label( $safe_text, $text ){
		if( ( is_checkout() ) && $text == 'Schedule Fee' ){
			$safe_text = $safe_text.'<span id="clearprime" style="margin-left:20px;" class="dtodtooltip dtodicons">x<p style="width: 126px; padding-left: 30px;" class="prime-tooltip">Cancel Scheduling. </p></span>';
		}
		return $safe_text;
	}

	// NO SHIPPIING AVAILABLE SCRIPT (CLALLA)
	public function dtod_no_shipping_available_html( $message ) {
		$country = WC()->customer->get_shipping_country();
		if ( !empty( $country ) ) {
			$all_countries  = WC()->countries->get_countries(); 
			return sprintf( 'Prime Members enjoy FREE delivery and FREE Schedule Delivery. Please verify your Address before Checkout.', $all_countries[ $country ] );
		}
		return 'Prime Members enjoy FREE delivery and FREE Schedule Delivery. Please verify your Address before Checkout.';
	}

	public function dtod_woo_delivery_option_delivery_time_pickup() {
		check_ajax_referer('dtod_woo_delivery_nonce');

		$delivery_option = (isset($_POST['deliveryOption']) && $_POST['deliveryOption'] !="") ? sanitize_text_field($_POST['deliveryOption']) : "";
		setcookie('dtod_woo_delivery_option_time_pickup', $delivery_option, time() + 60 * 60 * 24, '/');
		WC()->session->set( 'dtod_woo_delivery_option_time_pickup', $delivery_option );


		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);


		$disable_delivery_date_passed_time = [];
		$disable_pickup_date_passed_time = [];

		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		
		
		if($enable_delivery_time) {
			$time_slot_end = [0];
			$time_settings = get_option('dtod_woo_delivery_time_settings');
			$time_slot_end[] = (int)$time_settings['delivery_time_ends'];												
			$highest_timeslot_end = max($time_slot_end);

			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_timeslot_end) {
				$disable_delivery_date_passed_time[] = date('Y-m-d', time());
			}

		}

		if($enable_pickup_time) {

			$pickup_slot_end = [0];

		    $pickup_settings = get_option('dtod_woo_delivery_pickup_settings');
			$pickup_slot_end[] = (int)$pickup_settings['pickup_time_ends'];

			$highest_pickupslot_end = max($pickup_slot_end);

			$current_time = (date("G")*60)+date("i");
			if($current_time>$highest_pickupslot_end) {
				$disable_pickup_date_passed_time[] = date('Y-m-d', time());
			}
		}

		$response=[
			"disable_delivery_date_passed_time" => $disable_delivery_date_passed_time,
			"disable_pickup_date_passed_time" => $disable_pickup_date_passed_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);
	}

	//Without this function of filter "woocommerce_order_data_store_cpt_get_orders_query" query with post_meta "delivery_date" is not possible
	public function dtod_woo_delivery_handle_custom_query_var( $query, $query_vars ) {
		if ( ! empty( $query_vars['delivery_date'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'delivery_date',
				'value' => esc_attr( $query_vars['delivery_date'] ),
			);
		}

		if ( ! empty( $query_vars['pickup_date'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'pickup_date',
				'value' => esc_attr( $query_vars['pickup_date'] ),
			);
		}

		if ( ! empty( $query_vars['delivery_type'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'delivery_type',
				'value' => esc_attr( $query_vars['delivery_type'] ),
			);
		}

		if ( ! empty( $query_vars['delivery_time'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'delivery_time',
				'value' => esc_attr( $query_vars['delivery_time'] ),
			);
		}

		if ( ! empty( $query_vars['schedule_fee'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'schedule_fee',
				'value' => esc_attr( $query_vars['schedule_fee'] ),
			);
		}

		if ( ! empty( $query_vars['pickup_time'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'pickup_time',
				'value' => esc_attr( $query_vars['pickup_time'] ),
			);
		}

		return $query;
	}

	public function dtod_woo_delivery_get_orders() {

		check_ajax_referer('dtod_woo_delivery_nonce');
		
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$max_order_per_slot = (isset($delivery_time_settings['max_order_per_slot']) && !empty($delivery_time_settings['max_order_per_slot'])) ? $delivery_time_settings['max_order_per_slot'] : 0;
		
		$disabled_current_time_slot = (isset($delivery_time_settings['disabled_current_time_slot']) && !empty($delivery_time_settings['disabled_current_time_slot'])) ? $delivery_time_settings['disabled_current_time_slot'] : false;

		if(isset($_POST['onlyDeliveryTime']) && $_POST['onlyDeliveryTime']) {
			$order_date = date("Y-m-d", sanitize_text_field(strtotime($_POST['date']))); 
			if($this->hpos) {
		    	$args = array(
			        'limit' => -1,
					'type' => array( 'shop_order' ),
					'date_created' => $order_date,
					'meta_query' => array(
			            array(
			                'key'     => 'delivery_type',
			                'value'   => 'delivery',
			                'compare' => '==',
			            ),
			        ),
			        'return' => 'ids'
			    );
		    } else {
		    	$args = array(
			        'limit' => -1,
			        'date_created' => $order_date,
			        'delivery_type' => 'delivery',
			        'return' => 'ids'
			    );
		    }

		} else {

		    if($this->hpos) {
		    	$args = array(
			        'limit' => -1,
					'type' => array( 'shop_order' ),
					'meta_query' => array(
			            array(
			                'key'     => 'delivery_date',
			                'value'   => date("Y-m-d", strtotime(sanitize_text_field($_POST['date']))),
			                'compare' => '==',
			            ),
			        ),
			        'return' => 'ids'
			    );
		    } else {
		    	$args = array(
			        'limit' => -1,
			        'delivery_date' => date("Y-m-d", strtotime(sanitize_text_field($_POST['date']))),
			        'return' => 'ids'
			    );
		    }
		}

	    $order_ids = wc_get_orders( $args );

		$delivery_times = [];

		foreach ($order_ids as $order) {
			$order_ref = wc_get_order($order);
	  		if($this->hpos) {	  			
				$date = $order_ref->get_meta( 'delivery_date', true );
				$time = $order_ref->get_meta( 'delivery_time', true );
			} else {
				$date = get_post_meta($order,"delivery_date",true);
				$time = get_post_meta($order,"delivery_time",true);
			}

			if((isset($date) && isset($time)) || isset($time)) {
				$delivery_times[] = $time;
			}
		}

		$current_time = (date("G")*60)+date("i");

		$response = [
			"delivery_times" => $delivery_times,
			"max_order_per_slot" => $max_order_per_slot,
			'disabled_current_time_slot' => $disabled_current_time_slot,
			"current_time" => $current_time+120, /* SET 2 HOUR SCHEDULE BUFFER TO +120 ON SCHEDULE TIME DROPDOWN (CLALLA) */
		];
		$response = json_encode($response);
		wp_send_json_success($response);
	}


	public function dtod_woo_delivery_get_orders_pickup() {

		check_ajax_referer('dtod_woo_delivery_nonce');
		
		$delivery_pickup_settings = get_option('dtod_woo_delivery_pickup_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$pickup_max_order_per_slot = (isset($delivery_pickup_settings['max_pickup_per_slot']) && !empty($delivery_pickup_settings['max_pickup_per_slot'])) ? $delivery_pickup_settings['max_pickup_per_slot'] : 0;

		
		$pickup_disabled_current_time_slot = (isset($delivery_pickup_settings['disabled_current_pickup_time_slot']) && !empty($delivery_pickup_settings['disabled_current_pickup_time_slot'])) ? $delivery_pickup_settings['disabled_current_pickup_time_slot'] : false;

		
		if(isset($_POST['onlyPickupTime']) && $_POST['onlyPickupTime']) {
			$order_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date']))); 
		    if($this->hpos) {
			    	$args = array(
				        'limit' => -1,
						'type' => array( 'shop_order' ),
						'date_created' => $order_date,
						'meta_query' => array(
				            array(
				                'key'     => 'delivery_type',
				                'value'   => 'pickup',
				                'compare' => '==',
				            ),
				        ),
				        'return' => 'ids'
				    );
			    } else {
			    	$args = array(
				        'limit' => -1,
				        'date_created' => $order_date,
				        'delivery_type' => 'pickup',
				        'return' => 'ids'
				    );
			    }

		} else {
			$pickup_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date'])));
			if($this->hpos) {
			    	$args = array(
				        'limit' => -1,
						'type' => array( 'shop_order' ),
						'meta_query' => array(
				            array(
				                'key'     => 'pickup_date',
				                'value'   => $pickup_date,
				                'compare' => '==',
				            ),
				        ),
				        'return' => 'ids'
				    );
			    } else {
			    	$args = array(
				        'limit' => -1,
				        'pickup_date' => $pickup_date,
				        'return' => 'ids'
				    );
			    }	    
		}

		$order_ids = wc_get_orders( $args );

		$pickup_delivery_times = [];

	  	foreach ($order_ids as $order) {
			$order_ref = wc_get_order($order);
	  		if($this->hpos) {
	  			
				$date = $order_ref->get_meta( 'pickup_date', true );
				$time = $order_ref->get_meta( 'pickup_time', true );
			} else {
				$date = get_post_meta($order,"pickup_date",true);
				$time = get_post_meta($order,"pickup_time",true);
			}
			
			if((isset($date) && isset($time)) || isset($time)) {
				$pickup_delivery_times[] = $time;
			}

		}

		$current_time = (date("G")*60)+date("i");

		$response = [
			"pickup_delivery_times" => $pickup_delivery_times,
			"pickup_max_order_per_slot" => $pickup_max_order_per_slot,
			'pickup_disabled_current_time_slot' => $pickup_disabled_current_time_slot,
			"current_time" => $current_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);

	}


	public function dtod_woo_delivery_disable_max_delivery_pickup_date() {
		check_ajax_referer('dtod_woo_delivery_nonce');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$disable_delivery_date_passed_time = [];
		$disable_pickup_date_passed_time = [];

		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		
		
		if($enable_delivery_time) {

			$time_slot_end = [0];

			$time_settings = get_option('dtod_woo_delivery_time_settings');
			$time_slot_end[] = (int)$time_settings['delivery_time_ends'];												
			$highest_timeslot_end = max($time_slot_end);
			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_timeslot_end) {
				$disable_delivery_date_passed_time[] = date('Y-m-d', time());
			}
		}

		if($enable_pickup_time) {

			$pickup_slot_end = [0];

	    	$pickup_settings = get_option('dtod_woo_delivery_pickup_settings');
			$pickup_slot_end[] = (int)$pickup_settings['pickup_time_ends'];

			$highest_pickupslot_end = max($pickup_slot_end);

			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_pickupslot_end) {
				$disable_pickup_date_passed_time[] = date('Y-m-d', time());
			}

		}

		$response=[
			"disable_delivery_date_passed_time" => $disable_delivery_date_passed_time,
			"disable_pickup_date_passed_time" => $disable_pickup_date_passed_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);
		
	}

	
	public function dtod_woo_delivery_add_account_orders_column( $columns ) {
		if(class_exists('dtod_Woo_Delivery')) {
			$columns  = array_splice($columns, 0, 3, true) +
				['order_delivery_details' => "Delivery Details"] +
				array_splice($columns, 1, count($columns) - 1, true);
		}
		
	    return $columns;
	}

	public function dtod_woo_delivery_show_delivery_details_my_account_tab($order) {
		if(class_exists('dtod_Woo_Delivery')) {
			if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
				$order_id = $order->get_id();
			} else {
				$order_id = $order->id;
			}
			$delivery_date_settings = get_option('dtod_woo_delivery_date_settings');			
			$pickup_date_settings = get_option('dtod_woo_delivery_pickup_date_settings');			
			$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
			$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');
			$delivery_pickup_settings = get_option('dtod_woo_delivery_pickup_location_settings');
			$additional_field_settings = get_option('dtod_woo_delivery_additional_field_settings');

			$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : __("Delivery Date", "woo-delivery");
			$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __("Pickup Date", "woo-delivery");
			$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : __("Delivery Time", "woo-delivery");
			$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : __("Pickup Time", "woo-delivery");


			// if any timezone data is saved, set default timezone with the data
			$timezone = $this->helper->get_the_timezone();
			date_default_timezone_set($timezone);

			$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";


			$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

			
			
			$my_account_column = "";
			if((metadata_exists('post', $order_id, 'delivery_date') && get_post_meta( $order_id, 'delivery_date', true ) != "") || ($order->meta_exists('delivery_date') && $order->get_meta( 'delivery_date', true ) != "")) {

				
				if($this->hpos) {
					$delivery_date = date($delivery_date_format, strtotime($order->get_meta( 'delivery_date', true )));
				} else {
					$delivery_date = date($delivery_date_format, strtotime(get_post_meta( $order->get_id(), 'delivery_date', true )));
				}

				$my_account_column .= __($delivery_date_field_label, "woo-delivery").": " . $delivery_date;
				$my_account_column .= "<br>";
			}

			if((metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id,"delivery_time",true) != "") || ($order->meta_exists('delivery_time') && $order->get_meta( 'delivery_time', true ) != "")) {

				$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
				if($time_format == 12) {
					$time_format = "h:i A";
				} elseif ($time_format == 24) {
					$time_format = "H:i";
				}

				if($this->hpos) {
					$minutes = $order->get_meta( 'delivery_time', true );
				} else {
					$minutes = get_post_meta($order_id,"delivery_time",true);
				}

				$minutes = explode(' - ', $minutes);

	    		$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));


				$my_account_column .= __($delivery_time_field_label, "woo-delivery").": " . $time_value;
				$my_account_column .= "<br>";
			}

			if((metadata_exists('post', $order_id, 'pickup_date') && get_post_meta( $order_id, 'pickup_date', true ) != "") || ($order->meta_exists('pickup_date') && $order->get_meta( 'pickup_date', true ) != "")) {
				
				if($this->hpos) {
					$pickup_date = date($pickup_date_format, strtotime($order->get_meta( 'pickup_date', true )));
				} else {
					$pickup_date = date($pickup_date_format, strtotime(get_post_meta( $order->get_id(), 'pickup_date', true )));
				}
				$my_account_column .= __($pickup_date_field_label, "woo-delivery").": " . $pickup_date;
				$my_account_column .= "<br>";
			}

			if((metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id,"pickup_time",true) != "") || ($order->meta_exists('pickup_time') && $order->get_meta( 'pickup_time', true ) != "")) {
				if($this->hpos) {
					$pickup_minutes = $order->get_meta( 'pickup_time', true );
				} else {
					$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
				}
				$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
				if($pickup_time_format == 12) {
					$pickup_time_format = "h:i A";
				} elseif ($pickup_time_format == 24) {
					$pickup_time_format = "H:i";
				}
				$pickup_minutes = explode(' - ', $pickup_minutes);

	    		$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));


				$my_account_column .= __($pickup_time_field_label, "woo-delivery").": " . $pickup_time_value;
				$my_account_column .= "<br>";

			}

			echo $my_account_column;
		}
	}

	public function dtod_woo_delivery_add_delivery_information_row( $total_rows, $order ) {
		if ( ! is_wc_endpoint_url('order-received') ) return $total_rows;
 		$args=array();
		$delivery_date_settings = get_option('dtod_woo_delivery_date_settings');			
		$pickup_date_settings = get_option('dtod_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');

		// PRIME DB CALLS
		$prime_delivery_settings = get_option('dtod_woo_delivery_prime_settings');
		//error_log('THIS IS THE PRIME DELIVERY SETTINGS: '.print_r($prime_delivery_settings));
		$prime_set_non_prime_amount = (isset($prime_delivery_settings['non_prime_amount']) && !empty($prime_delivery_settings['non_prime_amount'])) ? stripslashes($prime_delivery_settings['non_prime_amount']) : "";
		$prime_set_default_prime_amount = (isset($prime_delivery_settings['default_prime_amount']) && !empty($prime_delivery_settings['default_prime_amount'])) ? stripslashes($prime_delivery_settings['default_prime_amount']) : "";
		$prime_set_purchase_limit = (isset($prime_delivery_settings['purchase_limit']) && !empty($prime_delivery_settings['purchase_limit'])) ? stripslashes($prime_delivery_settings['purchase_limit']) : "";
		$prime_product_id = (isset($prime_delivery_settings['prime_prod_id']) && !empty($prime_delivery_settings['prime_prod_id'])) ? stripslashes($prime_delivery_settings['prime_prod_id']) : "";

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : __("Delivery Date", "woo-delivery");
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __("Pickup Date", "woo-delivery");
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : __("Delivery Time", "woo-delivery");
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : __("Pickup Time", "woo-delivery");

		// CHECK IF PRIME DELEVERY OPTION SELECTED (CLALLA)
		$shipping = $total_rows['shipping'];
		$shipping = wc_price( $shipping , $args);
		$order_total = $order->get_total();
		
		// USER DETAILS FROM WOO
		$user = wp_get_current_user();
		// CHECK IF PRIME DELEVERY OPTION SELECTED (CLALLA)


		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";

		$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";


		$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
		if($time_format == 12) {
			$time_format = "h:i A";
		} elseif ($time_format == 24) {
			$time_format = "H:i";
		}

		$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
		if($pickup_time_format == 12) {
			$pickup_time_format = "h:i A";
		} elseif ($pickup_time_format == 24) {
			$pickup_time_format = "H:i";
		}

		if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
	        $order_id = $order->get_id();
	    } else {
	        $order_id = $order->id;
	    }

	    $delivery_option_settings = get_option('dtod_woo_delivery_option_delivery_settings');
	    $enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;
	    
	    if((metadata_exists('post', $order_id, 'delivery_date') && get_post_meta( $order_id, 'delivery_date', true ) != "") || ($order->meta_exists('delivery_date') && $order->get_meta( 'delivery_date', true ) != "")) {

	    	if($this->hpos) {
				$delivery_date = date($delivery_date_format, strtotime($order->get_meta( 'delivery_date', true )));
			} else {
				$delivery_date = date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true )));
			}

	    	$total_rows['delivery_date'] = array(
			   'label' => __($delivery_date_field_label, "woo-delivery"),
			   'value'   => $delivery_date
			);
	    }
		
	    if((metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id,"delivery_time",true) != "") || ($order->meta_exists('delivery_time') && $order->get_meta( 'delivery_time', true ) != "")) {

	    	if($this->hpos) {
	    		$minutes = $order->get_meta( 'delivery_time', true );
	    	} else {
	    		$minutes = get_post_meta($order_id,"delivery_time",true);
	    	}

			$minutes = explode(' - ', $minutes);

    		$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));

			$total_rows['delivery_time'] = array(
			   'label' => __($delivery_time_field_label, "woo-delivery"),
			   'value'   => $time_value
			);
			// SET PRIME SHIPPING COST (CLALLA)
			$shipping_total_wc = $prime_set_default_prime_amount;
			$woo_cart_total = WC()->cart->total;
			// CHECK IF PRIME IN CART
			$product_cart_id = WC()->cart->generate_cart_id( $prime_product_id );
			$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );
			// TRY PRIME
			$prime_try_product_id = (isset($prime_delivery_settings['prime_try_prod_id']) && !empty($prime_delivery_settings['prime_try_prod_id'])) ? stripslashes($prime_delivery_settings['prime_try_prod_id']) : "";
			$product_tryprime_cart_id = WC()->cart->generate_cart_id( $prime_try_product_id );
			$try_prime_in_cart = WC()->cart->find_product_in_cart( $product_tryprime_cart_id );

			$user_email = wp_get_current_user()->user_email;

			if ( !d2d_prime_is_active_prime_member($user_email) ) {
				if( $order_total <= $prime_set_purchase_limit ) {
					$shipping = wc_price( $shipping_total_wc , $args);
					WC()->cart->cart_contents_total = $order_total;
				} elseif($in_cart || $try_prime_in_cart) {
					$shipping = wc_price( 0 , $args);
					$order_total = $order_total;
				} else {
					$shipping = wc_price( $shipping_total_wc , $args);
					WC()->cart->cart_contents_total = $order_total;
				}
			} else {
				$shipping = wc_price( 0 , $args);
				$order_total = $order_total;
			}
			$total_rows['shipping']['label'] = __('Delivery Fee:', 'woocommerce');
			//UPDATE ORDER TOTAL
			$order->set_total( $order_total );
		}

		if((metadata_exists('post', $order_id, 'pickup_date') && get_post_meta( $order_id, 'pickup_date', true ) != "") || ($order->meta_exists('pickup_date') && $order->get_meta( 'pickup_date', true ) != "")) {

			
			if($this->hpos) {
				$pickup_date = date($pickup_date_format, strtotime($order->get_meta( 'pickup_date', true )));
			} else {
				$pickup_date = date($pickup_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true )));
			}

	    	$total_rows['pickup_date'] = array(
			   'label' => __($pickup_date_field_label, "woo-delivery"),
			   'value'   => $pickup_date
			);
	    }

		if((metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id,"pickup_time",true) != "") || ($order->meta_exists('pickup_time') && $order->get_meta( 'pickup_time', true ) != "")) {
			if($this->hpos) {
				$pickup_minutes = $order->get_meta( 'pickup_time', true );
			} else {
				$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
			}
			$pickup_minutes = explode(' - ', $pickup_minutes);

    		$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));

			$total_rows['pickup_time'] = array(
			   'label' => __($pickup_time_field_label, "woo-delivery"),
			   'value'   => $pickup_time_value
			);
		}
		 
		return $total_rows;
	}

	public function dtod_woo_delivery_load_custom_css() {
		if( is_checkout() && ! ( is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' )) ){
			$other_settings = get_option('dtod_woo_delivery_other_settings');
			$custom_css = isset($other_settings['custom_css']) && $other_settings['custom_css'] != "" ? stripslashes($other_settings['custom_css']) : "";
			$custom_css = wp_unslash($custom_css);
			echo '<style>' . $custom_css . '</style>';
		}
		
	}

	public function dtod_woo_delivery_prevent_field_value_change( $field, $key, $args, $value ) {
		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		if ( is_plugin_active( 'woocommerce-checkout-manager/woocommerce-checkout-manager.php' ) || is_plugin_active( 'add-fields-to-checkout-page-woocommerce/checkout-form-editor.php' ) ) {
			if ( 'select' === $args['type'] && ( 'dtod_woo_delivery_delivery_selection_box' === $key || 'dtod_woo_delivery_time_field' === $key || 'dtod_woo_delivery_pickup_time_field' === $key || 'dtod_woo_delivery_pickup_location_field' === $key ) ) {
				$sort            = $args['priority'] ? $args['priority'] : '';
				$field_container = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';

				// Custom attribute handling.
				$custom_attributes         = array();
				$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

				if ( $args['maxlength'] ) {
					$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
				}

				if ( ! empty( $args['autocomplete'] ) ) {
					$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
				}

				if ( true === $args['autofocus'] ) {
					$args['custom_attributes']['autofocus'] = 'autofocus';
				}

				if ( $args['description'] ) {
					$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
				}

				if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
					foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
						$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
					}
				}
				$field = '';

				if ( ! empty( $args['options'] ) ) {
					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">';
					if ( ! empty( $args['placeholder'] ) ) {
						$field .= '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
					}
					foreach ( $args['options'] as $option_key => $option_text ) {
						if($args['default'] == $option_key) {
							$field .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $args['default'], false ) . '>' . esc_attr( $option_text ) . '</option>';
						} else {
							$field .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_text, false ) . '>' . esc_attr( $option_text ) . '</option>';
						}
						
					}
					$field .= '</select>';
				}

				if ( ! empty( $field ) ) {
					$field_html = '';
					$label_id   = $args['id'];
					if ( $args['required'] ) {
						$args['class'][] = 'validate-required';
						$required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
					} else {
						$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
					}

					if ( $args['label'] && 'checkbox' !== $args['type'] ) {
						$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . $args['label'] . $required . '</label>';
					}

					$field_html .= '<span class="woocommerce-input-wrapper">' . $field;

					if ( $args['description'] ) {
						$field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
					}

					$field_html .= '</span>';

					$container_class = esc_attr( implode( ' ', $args['class'] ) );
					$container_id    = esc_attr( $args['id'] ) . '_field';
					$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
				}
			}
		}

		return $field;
	}

	public function dtod_woo_delivery_info_at_wpi_invoice( $invoice ) {

		if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
			$order_id = $invoice->order->get_id();
		} else {
			$order_id = $invoice->order->id;
		}

		$order = wc_get_order($order_id);

		$delivery_date_settings = get_option('dtod_woo_delivery_date_settings');			
		$pickup_date_settings = get_option('dtod_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('dtod_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('dtod_woo_delivery_additional_field_settings');

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : __( "Delivery Date", 'dtod-woo-delivery' );
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __( "Pickup Date", 'dtod-woo-delivery' );
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : __( "Delivery Time", 'dtod-woo-delivery' );
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : __( "Pickup Time", 'dtod-woo-delivery' );
		$pickup_location_field_label = (isset($delivery_pickup_settings['field_label']) && !empty($delivery_pickup_settings['field_label'])) ? stripslashes($delivery_pickup_settings['field_label']) : __( "Pickup Location", 'dtod-woo-delivery' );
		$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : __( "Special Note About Delivery", 'dtod-woo-delivery' );


		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";
		$add_weekday_name = (isset($delivery_date_settings['add_weekday_name']) && !empty($delivery_date_settings['add_weekday_name'])) ? $delivery_date_settings['add_weekday_name'] : false;

		if($add_weekday_name) {
			$delivery_date_format = "l ".$delivery_date_format;
		}

		$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

		$pickup_add_weekday_name = (isset($pickup_date_settings['add_weekday_name']) && !empty($pickup_date_settings['add_weekday_name'])) ? $pickup_date_settings['add_weekday_name'] : false;

		if($pickup_add_weekday_name) {
			$pickup_date_format = "l ".$pickup_date_format;
		}

		$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
		if($time_format == 12) {
			$time_format = "h:i A";
		} elseif ($time_format == 24) {
			$time_format = "H:i";
		}
		
		$column = "<br>";
		if((metadata_exists('post', $order_id, 'delivery_date') && get_post_meta( $order_id, 'delivery_date', true ) != "") || ($order->meta_exists('delivery_date') && $order->get_meta( 'delivery_date', true ) != "")) {

			if($this->hpos) {
				$delivery_date = date($delivery_date_format, strtotime($order->get_meta( 'delivery_date', true )));
			} else {
				$delivery_date = date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true )));
			}

			$column .= "<strong>".$delivery_date_field_label.": </strong>" . $delivery_date;
			$column .= "<br>";
		}

		if((metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id,"delivery_time",true) != "") || ($order->meta_exists('delivery_time') && $order->get_meta( 'delivery_time', true ) != "")) {

			if($this->hpos) {
				$minutes = $order->get_meta( 'delivery_time', true );
			} else {
				$minutes = get_post_meta($order_id,"delivery_time",true);
			}

			$minutes = explode(' - ', $minutes);
    		if(!isset($minutes[1])) {
    			$time_value = date($time_format, strtotime($minutes[0]));
    		} else {
    			$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));
    		}

			$column .= "<strong>".$delivery_time_field_label.": </strong>" . $time_value;
			$column .= "<br>";
		}

		if((metadata_exists('post', $order_id, 'pickup_date') && get_post_meta( $order_id, 'pickup_date', true ) != "") || ($order->meta_exists('pickup_date') && $order->get_meta( 'pickup_date', true ) != "")) {
			
			if($this->hpos) {
				$pickup_date = date($pickup_date_format, strtotime($order->get_meta( 'pickup_date', true )));
			} else {
				$pickup_date = date($pickup_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true )));
			}
			$column .= "<strong>".$pickup_date_field_label.": </strong>" . $pickup_date;
			$column .= "<br>";
		}

		if((metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id,"pickup_time",true) != "") || ($order->meta_exists('pickup_time') && $order->get_meta( 'pickup_time', true ) != "")) {
			if($this->hpos) {
				$pickup_minutes = $order->get_meta( 'pickup_time', true );
			} else {
				$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
			}
			$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
			if($pickup_time_format == 12) {
				$pickup_time_format = "h:i A";
			} elseif ($pickup_time_format == 24) {
				$pickup_time_format = "H:i";
			}
			$pickup_minutes = explode(' - ', $pickup_minutes);
    		if(!isset($pickup_minutes[1])) {
    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0]));
    		} else {
    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
    		}

			$column .= "<strong>".$pickup_time_field_label.": </strong>" . $pickup_time_value;
			$column .= "<br>";

		}

		echo $column;

	}

	public function dtod_woo_delivery_cloud_print_fields( $order ) {
		if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
			$order_id = $order->get_id();
		} else {
			$order_id = $order->id;
		}

		$delivery_date_settings = get_option('dtod_woo_delivery_date_settings');			
		$pickup_date_settings = get_option('dtod_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('dtod_woo_delivery_time_settings');
		$pickup_time_settings = get_option('dtod_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('dtod_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('dtod_woo_delivery_additional_field_settings');

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : __( "Delivery Date", 'dtod-woo-delivery' );
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : __( "Pickup Date", 'dtod-woo-delivery' );
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : __( "Delivery Time", 'dtod-woo-delivery' );
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : __( "Pickup Time", 'dtod-woo-delivery' );
		$pickup_location_field_label = (isset($delivery_pickup_settings['field_label']) && !empty($delivery_pickup_settings['field_label'])) ? stripslashes($delivery_pickup_settings['field_label']) : __( "Pickup Location", 'dtod-woo-delivery' );
		$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : __( "Special Note About Delivery", 'dtod-woo-delivery' );


		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";
		$add_weekday_name = (isset($delivery_date_settings['add_weekday_name']) && !empty($delivery_date_settings['add_weekday_name'])) ? $delivery_date_settings['add_weekday_name'] : false;

		if($add_weekday_name) {
			$delivery_date_format = "l ".$delivery_date_format;
		}

		$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

		$pickup_add_weekday_name = (isset($pickup_date_settings['add_weekday_name']) && !empty($pickup_date_settings['add_weekday_name'])) ? $pickup_date_settings['add_weekday_name'] : false;

		if($pickup_add_weekday_name) {
			$pickup_date_format = "l ".$pickup_date_format;
		}

		$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
		if($time_format == 12) {
			$time_format = "h:i A";
		} elseif ($time_format == 24) {
			$time_format = "H:i";
		}
		
		$column = "<br>";
		if((metadata_exists('post', $order_id, 'delivery_date') && get_post_meta( $order_id, 'delivery_date', true ) != "") || ($order->meta_exists('delivery_date') && $order->get_meta( 'delivery_date', true ) != "")) {

			if($this->hpos) {
				$delivery_date = date($delivery_date_format, strtotime($order->get_meta( 'delivery_date', true )));
			} else {
				$delivery_date = date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true )));
			}

			$column .= "<strong>".$delivery_date_field_label.": </strong>" . $delivery_date;
			$column .= "<br>";
		}

		if((metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id,"delivery_time",true) != "") || ($order->meta_exists('delivery_time') && $order->get_meta( 'delivery_time', true ) != "")) {

			if($this->hpos) {
				$minutes = $order->get_meta( 'delivery_time', true );
			} else {
				$minutes = get_post_meta($order_id,"delivery_time",true);
			}

			$minutes = explode(' - ', $minutes);
    		if(!isset($minutes[1])) {
    			$time_value = date($time_format, strtotime($minutes[0]));
    		} else {
    			$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));
    		}

			$column .= "<strong>".$delivery_time_field_label.": </strong>" . $time_value;
			$column .= "<br>";
		}

		if((metadata_exists('post', $order_id, 'pickup_date') && get_post_meta( $order_id, 'pickup_date', true ) != "") || ($order->meta_exists('pickup_date') && $order->get_meta( 'pickup_date', true ) != "")) {
			if($this->hpos) {
				$pickup_date = date($pickup_date_format, strtotime($order->get_meta( 'pickup_date', true )));
			} else {
				$pickup_date = date($pickup_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true )));
			}
			$column .= "<strong>".$pickup_date_field_label.": </strong>" . $pickup_date;
			$column .= "<br>";
		}

		if((metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id,"pickup_time",true) != "") || ($order->meta_exists('pickup_time') && $order->get_meta( 'pickup_time', true ) != "")) {
			if($this->hpos) {
				$pickup_minutes = $order->get_meta( 'pickup_time', true );
			} else {
				$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
			}
			$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
			if($pickup_time_format == 12) {
				$pickup_time_format = "h:i A";
			} elseif ($pickup_time_format == 24) {
				$pickup_time_format = "H:i";
			}
			$pickup_minutes = explode(' - ', $pickup_minutes);
    		if(!isset($pickup_minutes[1])) {
    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0]));
    		} else {
    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
    		}

			$column .= "<strong>".$pickup_time_field_label.": </strong>" . $pickup_time_value;
			$column .= "<br>";

		}

		echo $column;
	}


	public function dtod_woo_delivery_init_functionality() {
		
		$theme_name = esc_html( wp_get_theme()->get( 'Name' ) );
		if(strpos($theme_name,"Divi") !== false) {
			if(get_option('et_divi') == false) {

			} else {
				if(isset(get_option('et_divi')['divi_enable_jquery_body']) && get_option('et_divi')['divi_enable_jquery_body'] == 'on') {
					$temp_et_divi['divi_enable_jquery_body'] = 'off';
					$temp_et_divi = array_merge(get_option('et_divi'),$temp_et_divi);
					update_option('et_divi', $temp_et_divi);
				} elseif(!isset(get_option('et_divi')['divi_enable_jquery_body'])) {
					$temp_et_divi['divi_enable_jquery_body'] = 'off';
					$temp_et_divi = array_merge(get_option('et_divi'),$temp_et_divi);
					update_option('et_divi', $temp_et_divi);
				}
				
			}
		}
		
	}

}
