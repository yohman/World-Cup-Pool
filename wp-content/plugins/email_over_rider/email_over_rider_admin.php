<?php
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
 ?>
 <?php   
    if($_POST['my_hidden'] == 'Y') {  
    	//Form data sent
    	$ovr_email =  $_POST['over_ride_email'];
    	update_option('over_ride_email', $ovr_email);
    	$ovr_name = $_POST['over_ride_name'];
    	update_option('over_ride_name', $ovr_name);

?>
<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p>
</div>
<?php
	} else {
		$ovr_email = get_option('over_ride_email');
		$ovr_name = get_option('over_ride_name');	
?>
<div class="wrap">
    <?php  echo "<h2>" . __( 'Email Over-rider Options', 'my_trdom' ) . "</h2>";  ?>
	<form name="my_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
	        <input type="hidden" name="my_hidden" value="Y">  
	        <?php    echo "<h4>" . __( 'Email Over-rider Database Settings', 'my_trdom' ) . "</h4>"; ?>  
	        <p><?php _e("Over-ride Email: " ); ?><input type="text" name="over_ride_email" value="<?php echo $ovr_email; ?>" size="50"></p>
	        <p><?php _e("Over-ride Name: " ); ?><input type="text" name="over_ride_name" value="<?php echo $ovr_name; ?>" size="50"></p> 
	        <p class="submit">  
	        <input type="submit" name="Submit" value="<?php _e('Update Options', 'my_trdom' ) ?>" />  
	        </p>  
	</form>  
</div>
<?php
	}
?>