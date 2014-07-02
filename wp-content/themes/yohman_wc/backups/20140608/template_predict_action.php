<?php

/*
Template Name: predict action
*/
get_header();

$wpdb->show_errors = TRUE;
$wpdb->suppress_errors = FALSE;

// check if prediction already exists

$predictioncheck = $wpdb->get_results("SELECT * FROM wc_prediction WHERE wc_match_id = ".get_query_var('MatchID')." AND wp_users_ID = ". get_current_user_id());

print_r($predictioncheck);

if ($predictioncheck != null) 
{

// $wpdb->update( 
// 	'table', 
// 	array( 
// 		'column1' => 'value1',	// string
// 		'column2' => 'value2'	// integer (number) 
// 	), 
// 	array( 'ID' => 1 ), 
// 	array( 
// 		'%s',	// value1
// 		'%d'	// value2
// 	), 
// 	array( '%d' ) 
// );
echo '<h1>prediction exists already...</h1>';
	$wpdb->update(
		'wc_prediction', 
		array( 
			'wp_users_ID' => get_current_user_id(), 
			'wc_match_id' => get_query_var('MatchID'),
			'Team1Score'	=> get_query_var('Team1Score'),
			'Team2Score'	=> get_query_var('Team2Score')
		), 
		array( 'id' => $predictioncheck[0]->id),
		array(
			'%d', 
			'%d',
			'%d',
			'%d'
		),
		array( '%d')
	);

} 
else 
{
	echo '<h1>new prediction!</h1>';
	$wpdb->insert(
		'wc_prediction', 
		array( 
			'wp_users_ID' => get_current_user_id(), 
			'wc_match_id' => get_query_var('MatchID'),
			'Team1Score'	=> get_query_var('Team1Score'),
			'Team2Score'	=> get_query_var('Team2Score')
		), 
		array(
			'%d', 
			'%d',
			'%d',
			'%d'
		) 
	);
}



$thiserror = $wpdb->show_errors();
// var_dump($wpdb);
// echo $thiserror;

// echo 'Data action success...';

?>
<!-- 
<?php $wpdb->show_errors(); ?> 
<?php $wpdb->print_error(); ?> 
 -->
<?php get_footer(); ?>