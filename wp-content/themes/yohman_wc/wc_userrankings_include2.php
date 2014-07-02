<!--
	world cup content 
-->


<ul>
	<?php
	$blogusers = get_users('blog_id=1');
	global $wpdb;
	echo count($blogusers) . ' total users';
	echo '<table class="table table-bordered table-condensed small table-responsive" id="wc_usertable">';
	$rank = 1;

	foreach ($blogusers as $user) 
	{

		$key = 'wc_points';
		$single = true;
		$user_score = get_user_meta( $user->ID, $key, $single ); 
		// $user_link =  get_page_link(343); //dev
		$user_link =  get_page_link(2432); //live

		echo '<tr>';
		echo '<td>'.$rank;
		echo '</td>';
		echo '<td><button class="btn btn-danger" style="font-size:2em;">'.$user_score.'</button></td>';
		echo '<td align="center">'.get_avatar( $user->ID,32 ).'</td>';
		// echo '<td align="center">'.$user->display_name . '</td>';
		echo '<td style="vertical-align:middle;"><a href="'.$user_link.'?userid='.$user->ID.'">'.$user->display_name . '</a></td>';

		echo '<td style="vertical-align:middle;"><a href="'.$user_link.'?userid='.$user->ID.'">'.$user->user_firstname.' '.$user->user_lastname.'</a></td>';
		echo '<td><img width="" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$user->who_will_win_the_2014_world_cup.'.png" style="vertical-align:middle"></td>';

		//payment stuff
		if($user->payment == '')
		{
			$thislink = get_page_link(323);
			$payment = 'Go to the <a href="'.$thislink.'">login page</a> and let me know how you will pay';
		}
		else
		{
			$payment = $user->payment;
		}
		echo '<td>Payment: ' . $payment . '</td></tr>';

		// print '<td><pre>';
		// print_r($prediction_query);
		// print '</pre></td></tr>';
		$rank++;

	}
	echo '</tbody>';
	echo '</table>';
	?>
</ul>
<!-- sort table -->
<script>
	yohman.sortUserTable()
</script>
