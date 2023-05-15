<?php
# http://kovshenin.com/2012/the-wordpress-settings-api/
# http://codex.wordpress.org/Settings_API

function ajaxCreateShipment($order_id){

	$cc_api = New CallCourierApi();
	if($cc_api->createshipment($order_id)){
		return true;
	}else{
		return false;
	}
}

if(isset($_GET['fullfilment'])){

	if($_GET['orderid'] != ''){
		ajaxCreateShipment($_GET['orderid']);
	}else{
		echo 'failed';
	}
	die();
}



add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
    add_options_page( __('CallCourier Options', 'textdomain' ), __('CallCourier Options', 'textdomain' ), 'manage_options', 'callcourier-plugin', 'my_options_page' );
	
	$capability = 'administrator';
	if( current_user_can('manage_options') ) {
		$capability = 'manage_options';
	}else if( current_user_can('shop_manager') ) {
		$capability = 'shop_manager';		
	}
	
	add_options_page( __('CallCourier Booking', 'textdomain' ), __('CallCourier Booking', 'textdomain' ), $capability, 'callcourier-plugin-booking', 'my_booking_page' );
	//add_options_page( 	  $page_title, 							$menu_title, 							   $capability,      $menu_slug, 				$function = '')
}
add_action( 'admin_init', 'my_admin_init' );

function my_admin_init() {
  
  /* 
	 * http://codex.wordpress.org/Function_Reference/register_setting
	 * register_setting( $option_group, $option_name, $sanitize_callback );
	 * The second argument ($option_name) is the option name. It’s the one we use with functions like get_option() and update_option()
	 * */
  	# With input validation:
  	# register_setting( 'my-settings-group', 'callcourier-plugin-settings', 'my_settings_validate_and_sanitize' );    
  	register_setting( 'my-settings-group', 'callcourier-plugin-settings' );
	
  	/* 
	 * http://codex.wordpress.org/Function_Reference/add_settings_section
	 * add_settings_section( $id, $title, $callback, $page ); 
	 * */	 
  	add_settings_section( 'section-1', __( 'API Settings', 'textdomain' ), 'section_1_callback', 'callcourier-plugin' );
	
	/* 
	 * http://codex.wordpress.org/Function_Reference/add_settings_field
	 * add_settings_field( $id, $title, $callback, $page, $section, $args );
	 * */
  	add_settings_field( 'login-id', __( 'Login ID', 'textdomain' ), 'login_id_callback', 'callcourier-plugin', 'section-1' );
	add_settings_field( 'account-number', __( 'Account Number', 'textdomain' ), 'account_number_callback', 'callcourier-plugin', 'section-1' );
    add_settings_field( 'shipper_name', __( 'Shipper Name', 'textdomain' ), 'shipper_name_callback', 'callcourier-plugin', 'section-1' );
    
    add_settings_field( 'shipper_email', __( 'Shipper Email', 'textdomain' ), 'shipper_email_callback', 'callcourier-plugin', 'section-1' );
    add_settings_field( 'shipper_origin', __( 'Shipper Origin', 'textdomain' ), 'shipper_origin_callback', 'callcourier-plugin', 'section-1' );
    add_settings_field( 'shipper_city', __( 'Shipper City', 'textdomain' ), 'shipper_city_callback', 'callcourier-plugin', 'section-1' );
    add_settings_field( 'shipper_area', __( 'Shipper Area', 'textdomain' ), 'shipper_area_callback', 'callcourier-plugin', 'section-1' );
    add_settings_field( 'shipper_address', __( 'Shipper Address', 'textdomain' ), 'shipper_address_callback', 'callcourier-plugin', 'section-1' );
    add_settings_field( 'shipper_cell_no', __( 'Shipper Cell No.', 'textdomain' ), 'shipper_cell_no_callback', 'callcourier-plugin', 'section-1' );
    add_settings_field( 'shipper_land_line_no', __( 'Land Line No.', 'textdomain' ), 'shipper_land_line_no_callback', 'callcourier-plugin', 'section-1' );
	
}
/* 
 * THE ACTUAL PAGE 
 * */

function my_booking_page() {
	include( plugin_dir_path( __FILE__ ) . 'custom-booking.php');
}
	
function my_options_page() {
?>
  <div class="wrap">
      <h2><?php _e('CallCourier Options', 'textdomain'); ?></h2>
      <form action="options.php" method="POST">
        <?php settings_fields('my-settings-group'); ?>
        <?php do_settings_sections('callcourier-plugin'); ?>
        <?php submit_button(); ?>
      </form>
  </div>
<?php }


/*
* THE SECTIONS
* Hint: You can omit using add_settings_field() and instead
* directly put the input fields into the sections.
* */
function section_1_callback() {
	_e( 'Please enter detail below obtained from CallCourier.', 'textdomain' );
}

/*
* THE FIELDS
* */
function login_id_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );

	$field = "login_id";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function account_number_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );

	$field = "account_number";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_name_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_name";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_email_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_email";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_origin_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_origin";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_city_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_city";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_area_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_area";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_address_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_address";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_cell_no_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_cell_no";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}

function shipper_land_line_no_callback() {
	
	$settings = (array) get_option( 'callcourier-plugin-settings' );
	$field = "shipper_land_line_no";
	$value = esc_attr( $settings[$field] );
	
	echo "<input type='text' name='callcourier-plugin-settings[$field]' value='$value' />";
}



/*
* INPUT VALIDATION:
* */
function my_settings_validate_and_sanitize( $input ) {

	$settings = (array) get_option( 'callcourier-plugin-settings' );
	
	if ( $input['field_1_1'] != '' ) {
		$output['field_1_1'] = $input['field_1_1'];
	} else {
		add_settings_error( 'callcourier-plugin-settings', 'invalid-field_1_1', 'You have entered an invalid value into Field One.' );
	}
	
	if ( $input['field_1_2'] != '' ) {
		$output['field_1_2'] = $input['field_1_2'];
	} else {
		add_settings_error( 'callcourier-plugin-settings', 'invalid-field_1_2', 'You have entered an invalid value into Field One.' );
	}
	
	// and so on for each field
	
	return $output;
}