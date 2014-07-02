<?php

/*

	global javascript functions for world cup features

*/
add_action('wp_head', 'yohmanjs');

function yohmanjs() {
?>

<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/js/countdown/jquery.countdown.css"> 
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/timeago.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/countdown/jquery.plugin.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/countdown/jquery.countdown.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/moment.js"></script>
<script>
</script>


	<script type="text/javascript">
	// namespace
	var yohman = {};

	jQuery(document).ready(function() {
		jQuery.timeago.settings.allowFuture = true;
		jQuery("abbr.timeago").timeago();
	});

	// update the database on form submit
	jQuery(function() {
		jQuery('#wcPredictionForm').submit(yohman.ajaxSubmit);
	});
	   
	// update the modal form values dynamically
	yohman.launchPredictionModal = function(matchid,team1name, team2name,team1score,team2score)
	{
		console.log('in launch')
		jQuery('#predictionModal').modal('toggle');
		jQuery('#predictionModalLabel').html(team1name+' vs '+team2name);
		jQuery('#MatchID').val(matchid);
		jQuery('#Team1Score').val(team1score);
		jQuery('#Team2Score').val(team2score);
		jQuery('#MatchTitle').val(team1name+' vs '+team2name);
		jQuery('#predict-team1-flag').html('<img src="<?php echo get_stylesheet_directory_uri()?>/images/flags/shiny/64/'+team1name+'.png"> ')
		jQuery('#predict-team1-name').html(team1name)

		jQuery('#predict-team2-flag').html('<img src="<?php echo get_stylesheet_directory_uri()?>/images/flags/shiny/64/'+team2name+'.png"> ')
		jQuery('#predict-team2-name').html(team2name)

	}
	// update the modal form values dynamically
	yohman.launchMatchInfoModal = function(matchid)
	{
		console.log('in launch')
		jQuery('#matchInfoModal').modal('toggle');
		jQuery('#matchInfoModalLabel').html(matchid);
	}

	// on submit, post to admin-ajax (the function is in functions.php)
	yohman.ajaxSubmit = function (){

		// first check to make sure entry is numeric
		var team1score = jQuery('#Team1Score').val();
		var team2score = jQuery('#Team2Score').val();

		var team1scorenumeric = jQuery.isNumeric(team1score);
		var team2scorenumeric = jQuery.isNumeric(team2score);

		var data = jQuery(this).serialize();

		if(team1scorenumeric && team2scorenumeric)
		{
			if(team1score > 15 || team2score > 15)
			{
				alert('Seriously?  You think they will score that many goals?')
			}
			else
			{
				// send data to function in functions.php
				jQuery.post('<?php echo admin_url(); ?>/admin-ajax.php', data, function(response) {
					location.reload(true);
					console.log('Got this from the server: ' + response);
				});		
			}
		}
		else
		{
			alert('Aww come on, you know you must enter a numeric score!')
		}
		return false;
	}

	yohman.sortUserTable = function(){
		var rows = jQuery('#wc_usertable tbody  tr').get();

		rows.sort(function(a, b) {

			var A = jQuery(a).children('td').eq(1).text().toUpperCase();
			var B = jQuery(b).children('td').eq(1).text().toUpperCase();
			if(A < B) {
				return -1;
			}

			if(A > B) {
				return 1;
			}

			return 0;

		});

		jQuery.each(rows, function(index, row) {
			jQuery('#wc_usertable').children('tbody').prepend(row);
		});

		// get rows again to add rank numbers
		var rows = jQuery('#wc_usertable tbody  tr').get();
		var counter = 1;
		var prev = 0;
		jQuery.each(rows,function(i,val){
			
			console.log(i+': '+jQuery(val).children('td').eq(1).text())
			var thispoints = jQuery(val).children('td').eq(1).text();
			if(i>0)
			{
				if(thispoints != prev)
				{
					counter++;
					console.log('next...')
					jQuery(val).children('td').eq(0).html(counter);
				}
				else
				{
					console.log('same...')
					jQuery(val).children('td').eq(0).html(counter);
				}
			}
			// first row
			else
			{
				console.log('first row')
				jQuery(val).children('td').eq(0).html(counter);
				// counter++;
			}
				prev = thispoints;


		})
	}

	// change timezone display
	yohman.setMatchDateTime=function(offset)
	{
		// change the button states
		if(offset == 7) // LA
		{
			jQuery('.timela').removeClass('active btn-default').addClass('active btn-primary');
			jQuery('.timebr').removeClass('active btn-primary').addClass('btn-default');
			jQuery('.timejp').removeClass('active btn-primary').addClass('btn-default');
				jQuery('#debugwin').append('...clicked on LA...<br>')
		}
		else if (offset == 3) //BR
		{
			jQuery('.timela').removeClass('active btn-primary').addClass('btn-default');
			jQuery('.timebr').removeClass('active btn-default').addClass('active btn-primary');
			jQuery('.timejp').removeClass('active btn-primary').addClass('btn-default');
				jQuery('#debugwin').append('...clicked on Brazil...<br>')
		}
		else if (offset == -9) //JP
		{
			jQuery('.timela').removeClass('active btn-primary').addClass('btn-default');
			jQuery('.timebr').removeClass('active btn-primary').addClass('btn-default');
			jQuery('.timejp').removeClass('active btn-default').addClass('active btn-primary');

				jQuery('#debugwin').append('...clicked on Japan...<br>')


		}

		jQuery.each(jQuery('.matchdate'),function(i,val){
			var thismatchdate = jQuery(this).attr('matchdate');
			console.log('functions.php:'+thismatchdate);
			jQuery(this).html(moment(thismatchdate).zone(offset).format('ddd MM/DD'))
			if(i == 0)
			{
				jQuery('#debugwin').append('match date: '+thismatchdate+'<br>')
				jQuery('#debugwin').append('offset date: '+moment(thismatchdate).zone(offset).format()+'<br>')				
			}


		})
		jQuery.each(jQuery('.matchhour'),function(i,val){
			var thismatchdate = jQuery(this).attr('matchhour');
			jQuery(this).html(moment(thismatchdate).zone(offset).format('HH:mm'))
		})
	}

	</script>
<?php
}
//////////////////////////////////////////////////
//												//
// add the prediction form as a bootstrap modal //
//												//
//////////////////////////////////////////////////
add_action('wp_footer', 'yohmanFooter');

function yohmanFooter() {
?>

<!-- Prediction Modal -->
<div class="modal fade" id="predictionModal" tabindex="-1" role="dialog" aria-labelledby="predictionModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background:#555">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h1 class="modal-title" id="predictionModalLabel">Modal title</h1>
			</div>
			<div class="modal-body" style="background:#333">
				<form action="" method="POST" id="wcPredictionForm">
					<input type="Hidden" id="MatchID" name="MatchID">
					<input type="Hidden" id="MatchTitle" name="MatchTitle">
					<input type="hidden" name="action" value="wcPredict"/>
					Enter your prediction for the match below:
					<br>
					<br>
					<table bgcolor="dimgray" class="table ">
						<tr>
							<td style="vertical-align: middle;text-align: center;">
								<span id="predict-team1-flag"></span><br>
								<span id="predict-team1-name" ></span>
							</td>
<!-- 							<td style="vertical-align: middle;text-align: right;" id="predict-team1-name"></td> -->
							<td style="vertical-align: middle;" width="70">
								<input class="form-control input-lg col-xs-1" type="number" id="Team1Score" name="Team1Score" required="Yes" style="min-width:40px;">
							</td>
							<td style="vertical-align: middle;text-align: center;">-</td>
							<td style="vertical-align: middle;" width="70">
								<input class="form-control input-lg col-xs-1" type="number" id="Team2Score" name="Team2Score" required="Yes" style="min-width:40px;"></td>
							<td style="vertical-align: middle;text-align: center;">
								<span id="predict-team2-flag"></span><br>
								<span id="predict-team2-name" ></span>
							</td>
						</tr>
						<tr>
							<td colspan="7" align="center">
								<button type="submit" class="btn btn-warning btn-lg" value="predict">predict</button>
								<br>
							</td>
						</tr>
					</table>
					<font color="pink">note:  you will be able to edit your prediction up until the match actually begins</font>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Match Info Modal -->
<div class="modal fade" id="matchInfoModal" tabindex="-1" role="dialog" aria-labelledby="matchInfoModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background:#555">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h1 class="modal-title" id="matchInfoModalLabel">Modal title</h1>
			</div>
			<div class="modal-body" style="background:#333">
				Hello modal!
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php
}

/*
	
	add prediction data to database

*/

add_action('wp_ajax_wcPredict', 'wcPredict');
add_action('wp_ajax_nopriv_wcPredict', 'wcPredict');

function wcPredict(){

	/*
		to do:

		- add the update query if prediction exists
		- update template_match_byday2.php to check if prediction exists, and if so, to 
		  pre-populate those input boxes
		- update the match page when prediction is entered (any way to avoid a refresh?)
	*/

    // echo 'in wcPredict: '.$_POST['match_id'];

    // get date now
	date_default_timezone_set('America/Los_Angeles');
	$now = date('Y-m-d H:i:s');

    // check to see if date has expired or not
	$thispostargs = array(
		'post_type' 	=> 'match',
		'page_id'		=> $_POST['MatchID'],
	);


	$thispost = new WP_Query( $thispostargs );
	if( $thispost->have_posts() )
	{
		$thismatchdate = $thispost->post->match_date;
		$thismatchdate2=strtotime($thismatchdate);
		$now2 = strtotime($now);

		if ($thismatchdate2>$now2)
		{

		    // check to see if prediction exists
			$args = array(
				'post_type' => 'prediction',
				'meta_query' => array(
					array(
						'key' => 'match_id',
						'value' => $_POST['MatchID']
					),
					array(
						'key' => 'user_id',
						'value' => get_current_user_id()
					)
				)
			);
			$query = new WP_Query( $args );

			// differentiate action depending on whether user has predicted in the past or not
			if( $query->have_posts() )
			{
				echo ' ...this prediction already exists...';
				echo 'post id is '.$query->post->ID;
				// echo '<script>alert("hey")</script>';
				date_default_timezone_set('America/Los_Angeles');
				$now = new DateTime();
				echo $now->format('Y-m-d H:i:s');
				$post_id = $query->post->ID;

				// add the prediction custom data fields
				update_post_meta($post_id, 'match_id', $_POST['MatchID']); 
				update_post_meta($post_id, 'team_1_score', $_POST['Team1Score']); 
				update_post_meta($post_id, 'team_2_score', $_POST['Team2Score']); 
				update_post_meta($post_id, 'user_id', get_current_user_id()); 

			} 
			else 
			{
				echo ' ...no posts found';

				// Create post object
				$my_post = array(
					'post_title'    => $_POST['MatchTitle'],
					'post_type'		=> 'prediction',
					'post_content'  => 'This is my post.',
					'post_status'   => 'publish',
					'post_author'   => get_current_user_id()
				);

				// Insert the post into the database and get the post id
				$post_id = wp_insert_post( $my_post, $wp_error );

				// add the prediction custom data fields
				update_post_meta($post_id, 'match_id', $_POST['MatchID']); 
				update_post_meta($post_id, 'team_1_score', $_POST['Team1Score']); 
				update_post_meta($post_id, 'team_2_score', $_POST['Team2Score']); 
				update_post_meta($post_id, 'user_id', get_current_user_id()); 

				echo ' ...added to custom post type: '.$post_id;
				echo ' ...error: '.$wp_error;
			}        

		}
		else
		{
			echo 'this match already ended...';
		}
	}
	else
	{
		echo 'this match has already happened...';
	}
	die();
}


/*

	Custom post types: country, match and predictions

*/

add_action( 'init', 'wc_country_post_type' );
add_action( 'init', 'wc_match_post_type' );
add_action( 'init', 'wc_prediction_post_type' );

// function to create the country post type
function wc_country_post_type() {

	$labels = array(
		'name'               => _x( 'countries', 'post type general name' ),
		'singular_name'      => _x( 'country', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New country' ),
		'edit_item'          => __( 'Edit country' ),
		'new_item'           => __( 'New country' ),
		'all_items'          => __( 'All countries' ),
		'view_item'          => __( 'View country' ),
		'search_items'       => __( 'Search countries' ),
		'not_found'          => __( 'No countries found' ),
		'not_found_in_trash' => __( 'No countries found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Wolrd Cup Countries'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our countries and country specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'has_archive'   => true,
		'taxonomies' 	=> array( 'post_tag', 'category'),
		'query_var' 	=> true,
		'hierarchical' 	=> true,
	);
	register_post_type( 'country', $args );	
	
} 

// function to create the match post type
function wc_match_post_type() {

	$labels = array(
		'name'               => _x( 'matches', 'post type general name' ),
		'singular_name'      => _x( 'match', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New match' ),
		'edit_item'          => __( 'Edit match' ),
		'new_item'           => __( 'New match' ),
		'all_items'          => __( 'All matches' ),
		'view_item'          => __( 'View match' ),
		'search_items'       => __( 'Search matches' ),
		'not_found'          => __( 'No matches found' ),
		'not_found_in_trash' => __( 'No matches found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Wolrd Cup matches'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our matches and match specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'has_archive'   => true,
		'taxonomies' 	=> array( 'post_tag', 'category'),
		'query_var' 	=> true,
		'hierarchical' 	=> true,
	);
	register_post_type( 'match', $args );	
	
}

// Add to admin_init function

// add_filter('manage_match_posts_columns' , 'add_match_columns');

// function add_match_columns($columns) {
//     return array_merge($columns, 
//               array('team1' => __('Team 1'),
//                     'match_date' =>__( 'Team 2')));
// }

// add_action( 'manage_match_posts_custom_column' , 'custom_match_column' );

// function custom_match_column( $column, $post_id ) {
//     switch ( $column ) {
//       case 'team1':
//         echo get_post_meta( $post_id , 'team1' , true );
//         break;

//       case 'match_date':
//         echo get_post_meta( $post_id , 'match_date' , true ); 
//         break;
//     }
// }



// function to create the predict post type
function wc_prediction_post_type() {

	$labels = array(
		'name'               => _x( 'predictions', 'post type general name' ),
		'singular_name'      => _x( 'prediction', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New prediction' ),
		'edit_item'          => __( 'Edit prediction' ),
		'new_item'           => __( 'New prediction' ),
		'all_items'          => __( 'All predictions' ),
		'view_item'          => __( 'View prediction' ),
		'search_items'       => __( 'Search predictions' ),
		'not_found'          => __( 'No predictions found' ),
		'not_found_in_trash' => __( 'No predictions found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Wolrd Cup predictions'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our predictions and prediction specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'has_archive'   => true,
		'taxonomies' 	=> array( 'post_tag', 'category'),
		'query_var' 	=> true,
		'hierarchical' 	=> true,
	);
	register_post_type( 'prediction', $args );	
	
}


?>
