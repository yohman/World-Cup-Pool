<?php

// get userid if requested, otherwise, use current user
if (isset($_GET['userid'])) 
{
	$thisuserid = $_GET['userid'];
}
else
{
	$thisuserid = get_current_user_id();
}

/* -----------------------------------

	Match Query
	
 -------------------------------------*/
// get games that have already happened
$args_matches = array(
	'post_type'		=> 'match',
	'posts_per_page' 	=> -1,
	'meta_key' => 'match_date',
    'orderby' => 'meta_value',
	'order' => ASC
);

$query_matches = new WP_Query($args_matches);

/* -----------------------------------

	User Info
	
 -------------------------------------*/
$user_info = get_userdata($thisuserid);
$key = 'wc_points';
$single = true;
$user_score = get_user_meta( $thisuserid, $key, $single ); 

echo '<table class="table-responsive">';
echo '<td align="center">'.get_avatar( $thisuserid ).'<br>';
echo $user_info->display_name . '<br>'.$user_info->user_firstname.' '.$user_info->user_lastname.'</td>';
echo '<td><button class="btn btn-danger" style="font-size:4em;">'.$user_score.'</button></td>';
// echo '<td>' . $user_info->who_will_win_the_2014_world_cup . '</td>';
echo '<td><img width="" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$user_info->who_will_win_the_2014_world_cup.'.png" style="vertical-align:middle"></td>';


echo '</tr></table>';

echo '<div style="padding:10px;text-align:center;"><button class="btn btn-info">actual</button> <button class="btn btn-success">prediction</button></div>';
echo '<table width="100%" class="table-responsive">';

$counter = 1;
// loop through each game passed
foreach ($query_matches->posts as $post) 
{
	global $wpdb;
	$custom_fields = get_post_custom($post->ID);
	$team1 = $custom_fields['team1'][0];
	$team1_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team1'" );
	$team2 = $custom_fields['team2'][0];
	$team2_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team2'" );
	$team1score = $custom_fields['team1_score'][0];
	$team2score = $custom_fields['team2_score'][0];

	$added_class = '';
	$added_prediction_class = '';
	if($team1score == ''){$team1score='-'; $added_class='btn-default';}else{ $added_class='btn-info';}
	if($team2score == ''){$team2score='-'; $added_class='btn-default';}else{ $added_class='btn-info';}

	// date
	$match_date = $custom_fields['match_date'][0];
	$daydate = new DateTime($match_date);
	$day = $daydate->format('F d, Y');


	/* -----------------------------------

		User Predictions Stuff
		
	 -------------------------------------*/
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

	/* -----------------------------------

		Dompisha or not
		
	 -------------------------------------*/


		if($team1score == '-' || $team2score == '-')
		{
			$score = '-';
			$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/what.png"></td>';
			$resultsmsg = 'shhhhh... yohda is busy (probably watching this match on tape delay... Don\'t tell him the score!)';
		}
		else
		{

			if($prediction_team1score == $team1score && $prediction_team2score == $team2score)
			{
				$score = '-';
				$resulticon = '<td><img width="40" src="https://cdn1.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/money.png" width="80"></td><td><button class="btn btn-danger" style="font-size:2em;">3</button></td>';
				$resultsmsg = ' Dompisha!<br>';
			}
			// if win is guessed correctly
			elseif(($prediction_team1score > $prediction_team2score && $team1score > $team2score) || ($prediction_team1score < $prediction_team2score && $team1score < $team2score))
			{
				$score = '-';
				$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/victory.png" width="80"></td><td><button class="btn btn-danger" style="font-size:2em;">1</button></td>';
				$resultsmsg = ' Right winner!<br>';				
			}
			elseif($prediction_team1score == $prediction_team2score && $team1score != '' &&  $team2score !='' && $team1score == $team2score)
			{
				$score = '-';
				$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/victory.png" width="80"></td><td><button class="btn btn-danger" style="font-size:2em;">1</button></td>';
				$resultsmsg = ' You guessed a tie!<br>';				
			}
			else
			{
				$score = '-';
				$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/cry.png" width="80"></td><td><button class="btn btn-danger" style="font-size:2em;">0</button></td>';
				$resultsmsg = ' You guessed wrong!<br>';				
			}

			$results = 'Final score: '.$team1_name.' '.$team1score.'</span> - '.$team2_name.' '.$team2score.'</span>';
		}

	// print_r($post->ID);
	echo '<tr>';
	echo '<td>Match #'.$counter.'</td>';
	echo '<td>'.$day.'</td>';
	echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team1_name.'.png" style="vertical-align:middle"></td>';
	echo '<td style="vertical-align:middle" align="center"><button class="btn '.$added_class.'">'.$team1score.'</button> <button class="btn '.$added_prediction_class.'">'.$prediction_team1score.'</button></td>';
	echo '<td style="vertical-align:middle" align="center"><button class="btn '.$added_class.'">'.$team2score.'</button> <button class="btn '.$added_prediction_class.'">'.$prediction_team2score.'</button></td>';
	echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team2_name.'.png" style="vertical-align:middle"></td>';
	echo $resulticon;
	echo '</tr>';
	$counter++;
}
echo '</table>';





?>