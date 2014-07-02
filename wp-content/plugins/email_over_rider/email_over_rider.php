<?php
/**
 * Plugin Name: Email Over Rider
 * Plugin URI: http://www.nyayapati.com/srao/
 * Description: This plugin over-rides the wp-mail pluggin with user provided email
 * Author: Srinivas Nyayapati
 * Version: 1.0
 * Author URI: http://www.nyayapati.com/srao/
 */
    
add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
    add_options_page( 'Email Over-rider Options', 'Email Over-rider', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

function my_plugin_options() {
    include('email_over_rider_admin.php');
}

$ovr_email = get_option('over_ride_email');
$ovr_name = get_option('over_ride_name');

if(isset($ovr_email)){
	add_filter( 'wp_mail_from', 'my_custom_mail_from' );
}
/**
 * Changes the email address that WP puts in the 'from' address
 *
 * @param  string $email
 * @return string the email address to send from
 */
function my_custom_mail_from( $email )
{
	/* change the value below to the email address you want 
	   wp_mail to send from
	*/
	return get_option('over_ride_email');
}

if(isset($ovr_name)){
	add_filter( 'wp_mail_from_name', 'my_custom_name_from' );
}
/**
 * Changes the email name that WP puts in the 'from' address
 *
 * @param  string $name
 * @return string the email name to send from
 */
function my_custom_name_from( $name )
{
	/* change the value below to the email name you want 
	   wp_mail to send from. For example, if you set the
	   address above as john.doe@mydomain.com and you want
	   the displayed name to be "John Doe" (i.e.
	   John Doe <john.doe@mydomain.com>).  This could be
	   a name like John Doe, or Administrator, or 
	   Registrations, etc.  It could also be an email
	   address, which would make the message come from
	   myemail@mydomain.com <myemail@mydomain.com>
	*/
	return stripslashes(get_option('over_ride_name'));
}

?>
