<?php

// get games that have already happened
$args_gamespassed = array(
	'post_type'		=> 'match',
	'posts_per_page' 	=> -1,
	'meta_key' => 'match_date',
    'orderby' => 'meta_value',
	'order' => DESC,
	'meta_query'	=> array(
		array(
			'key' 	=> 'team1_score',
			'value' => '',
			'compare' => '!='
		)
	)
);

$query_gamespassed = new WP_Query($args_gamespassed);

// get next games
$args_nextgames = array(
	'post_type'		=> 'match',
	'posts_per_page' 	=> -1,
	'meta_key' => 'match_date',
    'orderby' => 'meta_value',
	'order' => ASC,
	'meta_query'	=> array(
		array(
			'key' 	=> 'team1_score',
			'value' => '',
			'compare' => '='
		)
	)
);

$query_nextgames = new WP_Query($args_nextgames);

echo '<table width="100%">';

$counter = 1;
// loop through each game passed
foreach ($query_nextgames->posts as $post) 
{
	if($counter>5){break;}

	global $wpdb;
	$custom_fields = get_post_custom($post->ID);
	$team1 = $custom_fields['team1'][0];
	$team1_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team1'" );
	$team2 = $custom_fields['team2'][0];
	$team2_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team2'" );

	// date
	$match_date = $custom_fields['match_date'][0];
	$daydate = new DateTime($match_date);
	$day = $daydate->format('F d, Y');

	/* -----------------------------------

		User Predictions Stuff
		
	 -------------------------------------*/
 	$thisuserid = get_current_user_id();

	// get user predictions
	$args = array(
		'post_type' 	=> 'prediction',
		'meta_query' 	=> array(
			array(
				'key' 	=> 'match_id',
				'value' => $post->ID
			),
			array(
				'key' 	=> 'user_id',
				'value' => $thisuserid
			)
		)
	);
	$prediction_query = new WP_Query( $args );

	$prediction_custom_fields = get_post_custom($prediction_query->post->ID);
	$prediction_team1score = $prediction_custom_fields['team_1_score'][0];
	$prediction_team2score = $prediction_custom_fields['team_2_score'][0];
	if($prediction_team1score == ''){$prediction_team1score='-'; $added_prediction_class='btn-default';}else{ $added_prediction_class='btn-success';}
	if($prediction_team2score == ''){$prediction_team2score='-'; $added_prediction_class='btn-default';}else{ $added_prediction_class='btn-success';}

	// print_r($post->ID);
	echo '<tr>';
	echo '<td colspan="4">'.$day.'</td></tr><tr>';
	echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team1_name.'.png" style="vertical-align:middle"></td>';
	echo '<td style="vertical-align:middle" align="center"><button class="btn btn-success">'.$prediction_team1score.'</button></td>';
	echo '<td style="vertical-align:middle" align="center"><button class="btn btn-success">'.$prediction_team2score.'</button></td>';
	echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team2_name.'.png" style="vertical-align:middle"></td>';
	echo "<td><button class='btn btn-warning' onclick=\"yohman.launchPredictionModal('".$post->ID."','".$team1_name."','".$team2_name."','".$prediction_team1score."','".$prediction_team2score."')\"><span class='glyphicon glyphicon-pencil'></span></button></td>";
	echo '</tr>';
	$counter++;
}
echo '</table>';


// loop through each game passed
echo '<table width="100%">';
$counter = 1;

foreach ($query_gamespassed->posts as $post) 
{
	if($counter>3){break;}
	global $wpdb;
	$custom_fields = get_post_custom($post->ID);
	$team1 = $custom_fields['team1'][0];
	$team1_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team1'" );
	$team2 = $custom_fields['team2'][0];
	$team2_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team2'" );
	$team1score = $custom_fields['team1_score'][0];
	$team2score = $custom_fields['team2_score'][0];

	// date
	$match_date = $custom_fields['match_date'][0];
	$daydate = new DateTime($match_date);
	$day = $daydate->format('F d, Y');


	// print_r($post->ID);
	echo '<tr>';
	echo '<td colspan="4">'.$day.'</td></tr><tr>';
	echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team1_name.'.png" style="vertical-align:middle"></td>';
	echo '<td style="vertical-align:middle" align="center"><button class="btn btn-info">'.$team1score.'</button></td>';
	echo '<td style="vertical-align:middle" align="center"><button class="btn btn-info">'.$team2score.'</button></td>';
	echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team2_name.'.png" style="vertical-align:middle"></td>';


	echo '</tr>';
	$counter++;
}
echo '</table>';






?>