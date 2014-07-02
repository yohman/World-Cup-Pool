<?php
// in order to group results by day...
$day_check = '';

$counter = 1;


// get userid if requested, otherwise, use current user

if (isset($_GET['userid'])) 
{
	$thisuserid = $_GET['userid'];
}
else
{
	$thisuserid = get_current_user_id();
}

// add link to user predictions
$user_link =  get_page_link(2432); //live
echo '<a href="'.$user_link.'?userid='.$thisuserid.'"><span class="btn btn-info">See all my predictions</span></a>';

echo '<div id="wc_cal" class="" style=""></div>';
while ( $loop->have_posts() ) : $loop->the_post();  


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
	
	// default label style
	$labelcss = 'label-success';
	
	// change label style if user already has predition
	if($prediction_team1score == ''){ $prediction_team1score = '?';$labelcss = 'label-default';}
	if($prediction_team2score == ''){ $prediction_team2score = '?';$labelcss = 'label-default';}
	
	// custom fields
	$custom_fields = get_post_custom($post->ID);
	$team1_customfield = $custom_fields['team1'][0];
	$team1_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team1_customfield'" );
	$team2_customfield = $custom_fields['team2'][0];
	$team2_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team2_customfield'" );
	$team1score_customfield = $custom_fields['team1_score'][0];
	$team2score_customfield = $custom_fields['team2_score'][0];

	// comments
	$args = array(
		'post_id' => $post->ID, // use post_id, not post_ID
		'count' => true //return only the count
	);
	$comments = get_comments($args);
	// echo $comments.' comments';


	/* -----------------------------------

		Date Time Stuff

	 -------------------------------------*/

	// get the datetime for this match from the database (it's UTC time)
	$thismatchdate = $custom_fields['match_date'][0];
	// make it into a PHP date
	$daydate = new DateTime($thismatchdate);
	// readable format
	$thismatchdate = $daydate->format('Y-m-d H:i:sP');	

	// get datetime NOW, and set it to LA timezone
	// $now = date('Y-m-d H:i:sP');
	$now = new DateTime(null, new DateTimeZone('America/Los_Angeles'));

	// for debug, what's NOW in Japan?
	$japannow = new DateTime(null, new DateTimeZone('Asia/Tokyo'));

	$now = $now->format('Y-m-d H:i:sP');
	$japannow = $japannow->format('Y-m-d H:i:sP');
	// echo $now;
	// convert to strtotime format so that we can compare
	$thismatchdate2=strtotime($thismatchdate);
	$now2 = strtotime($now);

	// the following to group display by DAY
	$day = $daydate->format('F d, Y');
	$shortday = $daydate->format('m/d');
	$idday = $daydate->format('m_d');
	$hour = $daydate->format('H:i');
	if ($day != $day_check) 
	{
		if ($day_check != '') 
		{
			echo '</table>'; // close the list here
		}
		?>

		<!-- add buttons to scroll to specific dates -->
		<script>
		jQuery('#wc_cal').append('<span style="cursor:pointer;" onclick="jQuery(\'html, body\').animate({scrollTop: jQuery(\'#<?php echo $idday; ?>\').offset().top}, 2000);" class="label label-danger"><?php echo $shortday; ?></span> ');
		// $('html, body').animate({scrollTop: $(target).offset().top}, 2000);
		</script>

		<?php
		echo '<div style="text-align:center;" id="'.$idday.'"><h1>'.$day.'</h1></div>';
		echo '<div style="text-align:right; padding:4px;" class="small">Timezone: 
			<span id="timela" class="timela btn btn-default btn-xs" onclick="yohman.setMatchDateTime(7)">LA</span> 
			<span id="timebr" class="timebr btn btn-default btn-xs" onclick="yohman.setMatchDateTime(3)">Brazil</span>  
			<span id="timejp" class="timejp btn btn-default btn-xs" onclick="yohman.setMatchDateTime(-9)">Japan</span></div>';
		echo "<table class='table table-condensed' width='100%'>";
	}
	$day_check = $day;

	echo '<tr class="well" style="border:12px solid #555;background:#444;"><td>';
	
	/*
	///////////////////////	// debug
	*/
	// echo '<div class="bg-primary">Debug:<br>match: '.$thismatchdate.'<br>LA now: '.$now.'<br>JP now: '.$japannow.'</div>';

	// when did this match happen (timeago)

	echo '<div class="row success" style="background:#333;"><div class="col-md-12" style="text-align:center;" id="matchdate-'.$post->ID.'"></div></div>';

	/* -----------------------------------

		Show Match Results
		
	 -------------------------------------*/

	if ($thismatchdate2<$now2)
	{
		if($team1score_customfield == '' || $team2score_customfield == '')
		{
			$resultcolor = '#343';
			$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/what.png"></td>';
			$resultsmsg = 'shhhhh... yohda is busy (probably watching this match on tape delay... Don\'t tell him the score!)';
		}
		else
		{

			if($prediction_team1score == $team1score_customfield && $prediction_team2score == $team2score_customfield)
			{
				$resultcolor = '#555';
				$resulticon = '<td><img width="40" src="https://cdn1.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/money.png" width="80"></td><td><button class="btn btn-info" style="font-size:2em;">3</button></td>';
				$resultsmsg = ' Dompisha!<br>';
			}
			// if win is guessed correctly
			elseif(($prediction_team1score > $prediction_team2score && $team1score_customfield > $team2score_customfield) || ($prediction_team1score < $prediction_team2score && $team1score_customfield < $team2score_customfield))
			{
				$resultcolor = '#555';
				$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/victory.png" width="80"></td><td><button class="btn btn-info" style="font-size:2em;">1</button></td>';
				$resultsmsg = ' Right winner!<br>';				
			}
			elseif($prediction_team1score == $prediction_team2score && $team1score_customfield != '' &&  $team2score_customfield !='' && $team1score_customfield == $team2score_customfield)
			{
				$resultcolor = '#555';
				$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/victory.png" width="80"></td><td><button class="btn btn-info" style="font-size:2em;">1</button></td>';
				$resultsmsg = ' You guessed a tie!<br>';				
			}
			else
			{
				$resultcolor = '#555';
				$resulticon = '<td><img width="40" src="https://cdn0.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/cry.png" width="80"></td><td><button class="btn btn-info" style="font-size:2em;">0</button></td>';
				$resultsmsg = ' You guessed wrong!<br>';				
			}

			$results = 'Final score: '.$team1_name.' '.$team1score_customfield.'</span> - '.$team2_name.' '.$team2score_customfield.'</span>';
		}
		// echo '<div class="row"><div class="" style="background:'.$resultcolor.';padding:10px;">'.$resultsmsg.'</div></div>';
		echo '<table class="" width="50%" style="background:'.$resultcolor.';"><tr>'.$resulticon.'<td>'.$results.'<br>'.$resultsmsg.'</td></tr></table>';						
	}

	//				 //
	// display match //
	//				 //

	// time

	//				 		//
	// User predictions 	//
	//				 		//
	$query_allusers_predict = array(
		'post_type' 	=> 'prediction',
		'posts_per_page' => -1, 
		'meta_query' 	=> array(
			array(
				'key' 	=> 'match_id',
				'value' => $post->ID
			)
		)
	);
	$allusers_prediction_query = new WP_Query( $query_allusers_predict );

	$ave_team1 = 0;
	$ave_team2 = 0;
	$allusers_prediction4team1 = 0;
	$allusers_prediction4team2 = 0;
	$allusers_prediction4tie = 0;
	
	// get averages
	foreach ($allusers_prediction_query->posts as $allusers_prediction) 
	{
		$allusers_prediction_custom_fields = get_post_custom($allusers_prediction->ID);
		$allusers_prediction_team1score = $allusers_prediction_custom_fields['team_1_score'][0];
		$allusers_prediction_team2score = $allusers_prediction_custom_fields['team_2_score'][0];
		$ave_team1 = $ave_team1 + $allusers_prediction_team1score;
		$ave_team2 = $ave_team2 + $allusers_prediction_team2score;
		if($allusers_prediction_team1score>$allusers_prediction_team2score)
		{
			$allusers_prediction4team1 = $allusers_prediction4team1 + 1;
		}
		else if ($allusers_prediction_team1score<$allusers_prediction_team2score)
		{
			$allusers_prediction4team2 = $allusers_prediction4team2 + 1;
		}
		else if ($allusers_prediction_team1score==$allusers_prediction_team2score)
		{
			$allusers_prediction4tie = $allusers_prediction4tie + 1;
		}
	}

	$total_allusers_predictions = count($allusers_prediction_query->posts);
	$ave_team1 = round($ave_team1 / $total_allusers_predictions,1);
	$ave_team2 = round($ave_team2 / $total_allusers_predictions,1);



	echo "<div class='row'>";

	// show day
	echo "<div class='col-md-8 col-sm-8' style='line-height:6em;text-align:center;' ><span class='label label-info' style='font-size:1em;'><span style='font-size:1em' id='match_day-".$post->ID."'></span> <span class='glyphicon glyphicon-calendar'></span> <span id='match_hour-".$post->ID."'></span></span></div>";

	// predict button
	// if user is not logged in, add a login button 
	if (get_current_user_id() == 0)
	{
		?>
			<a href="<?php echo get_page_link(323); ?>">
		<?php
		echo "<div class='col-md-4 col-sm-4' style='line-height:6em;text-align:center;'><button class='btn btn-danger disabled'>login</button></div></a>";

	}
	else
	{
		if ($thismatchdate2>$now2)
		{
			echo "<div class='col-md-4 col-sm-4' style='line-height:6em;text-align:center;'><button class='btn btn-warning' onclick=\"yohman.launchPredictionModal('".$post->ID."','".$team1_name."','".$team2_name."','".$prediction_team1score."','".$prediction_team2score."')\"><span class='glyphicon glyphicon-pencil'></span> predict</button></div>";
		}
		else
		{
			echo "<div class='col-md-4 col-sm-4' style='line-height:6em;text-align:center;'><button class='btn btn-danger disabled'>prediction closed</button></div>";
		}
	}
	echo "</div>"; // end row


	// flag team 1

	echo "<div class='row'>";
	echo "<div class='col-md-4 col-sm-4' style='text-align:center;'><img data-toggle='tooltip' title='".$team1_name."' src='".get_stylesheet_directory_uri()."/images/flags/shiny/64/".$team1_name.".png' style='vertical-align:middle'><br>".$team1_name."</div>";
	// prediction scores
	echo "<div class='col-md-4 col-sm-4' style='line-height:6em;text-align:center;'>
			<span class='label ".$labelcss."' style='font-size:2em;'>".$prediction_team1score."</span>
			<span class='label ".$labelcss."' style='font-size:2em;'>".$prediction_team2score."</span>
		</div>";

	// flag team 2
	echo "<div class='col-md-4 col-sm-4' style='text-align:center;'><img data-toggle='tooltip' title='".$team2_name."' src='".get_stylesheet_directory_uri()."/images/flags/shiny/64/".$team2_name.".png' style='vertical-align:middle'><br>".$team2_name."</div>";
	echo "</div>";

	?>
	<div class="row">
		<div class="col-md-12" style="text-align:center;">
			
				<button type="button" class="btn btn-default btn-xs" style="text-align:center;background:#ccc;padding:5px;">
					<span class="btn btn-default"><?php echo $ave_team1; ?></span>
					-
					<span class="btn btn-default"><?php echo $ave_team2; ?></span><br>
					average predictions from <?php echo $total_allusers_predictions; ?> users<br>
					<?php echo $allusers_prediction4team1; ?> predict <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/flags/shiny/64/<?php echo $team1_name;?>.png" style="vertical-align:middle;width:20px;"> <?php echo $team1_name; ?> to win<br>
					<?php echo $allusers_prediction4team2; ?> predict <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/flags/shiny/64/<?php echo $team2_name;?>.png" style="vertical-align:middle;width:20px;"> <?php echo $team2_name; ?> to win<br>
					<?php echo $allusers_prediction4tie; ?> predict a tie<br>

					<a href="<?php the_permalink();?>">See predictions<span class="glyphicon glyphicon-arrow-right"></span></a>
				</button>
			
		</div>
	</div>
	<?php


	echo "</td></tr>";



?>
		<script type="text/javascript">
			// how much time left
			var startdate = '<?php echo $thismatchdate; ?>';
			startdate2 = moment(startdate).zone();

			// startdate2 = moment(startdate).tz('Asia/Tokyo').format();
			
			startdate = startdate.replace(/-/g,",");
			startdate = startdate.replace(/:/g,",");
			startdate = startdate.replace(/ /g,",");

			startdate = startdate.split(',');
			utcstartdate = startdate;
			// console.log(startdate)
			startdate = new Date(Date.UTC(startdate[0],startdate[1]-1,startdate[2],startdate[3],startdate[4]))
			utcstartdate = new Date(Date.UTC(utcstartdate[0],utcstartdate[1]-1,utcstartdate[2],utcstartdate[3],utcstartdate[4]))
			counter = <?php echo $counter; ?>;

			console.log(startdate)
			console.log(utcstartdate.getTimezoneOffset())

			// incorrect in Japan.  calculates the time from japan time to start time of the match...
			// should compare LA time to start time instead?
			jQuery('#matchdate-<?php echo $post->ID; ?>').append(moment(startdate).fromNow());


			// show date and time of match
			jQuery('#match_day-<?php echo $post->ID; ?>').addClass('matchdate');
			jQuery('#match_day-<?php echo $post->ID; ?>').attr('matchdate','<?php echo $thismatchdate; ?>');
			jQuery('#match_day-<?php echo $post->ID; ?>').html(moment(startdate).format('ddd MM/DD'))
			jQuery('#match_hour-<?php echo $post->ID; ?>').addClass('matchhour');
			jQuery('#match_hour-<?php echo $post->ID; ?>').attr('matchhour','<?php echo $thismatchdate; ?>');
			jQuery('#match_hour-<?php echo $post->ID; ?>').html(moment(startdate).format('HH:mm'))

			
			/*

				DEBUG



			if(counter == 1)
			{
				jQuery('#debugwin').show().html('DEBUG FOR YOH<br>Brazil vs Croatia<br>')

				jQuery('#debugwin').append('LA datetime: <?php echo $now; ?><br>')
				jQuery('#debugwin').append('JP datetime: <?php echo $japannow; ?><br>')
				jQuery('#debugwin').append('Time offset from UTC: '+utcstartdate.getTimezoneOffset()+' mins<br>')
				
				// jQuery('#debugwin').append('php startdate: <?php echo $thismatchdate; ?><br>')
				// jQuery('#debugwin').append('utc startdate: '+utcstartdate+'<br>')
				// jQuery('#debugwin').append('js startdate: '+startdate+'<br>')
				// jQuery('#debugwin').append('moment:'+moment(startdate).format()+'<br>')
			}
			*/

			// jQuery('.matchdate').html(moment(jQuery('.matchdate').attr('matchdate')).format('MMM Do'))
		</script>
<?php

$counter++;

endwhile; 

echo "</table>";

// $wpdb->show_errors(); 
// $wpdb->print_error(); 
?> 
<script type="text/javascript">

yohman.setMatchDateTime(utcstartdate.getTimezoneOffset()/60);

jQuery('[data-toggle="tooltip"]').tooltip({
	'placement': 'top'
});
</script>
