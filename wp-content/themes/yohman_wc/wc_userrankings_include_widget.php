<!--
	world cup content 
-->


<ul>
	<?php
	$blogusers = get_users('blog_id=1');
	global $wpdb;
	echo count($blogusers) . ' total users';
	echo '<table width="100%" id="wc_usertable" class="table table-responsive">';
	$rank = 1;

	foreach ($blogusers as $user) 
	{

		$key = 'wc_points';
		$single = true;
		$user_score = get_user_meta( $user->ID, $key, $single ); 
		$user_link =  get_page_link(343); //dev
		// $user_link =  get_page_link(2432); //live
		echo '<tr>';
		echo '<td style="vertical-align:middle;">'.$rank;
		echo '</td>';
		echo '<td style="vertical-align:middle;"><button class="btn btn-danger" style="font-size:1.1em;">'.$user_score.'</button></td>';
		echo '<td style="vertical-align:middle;" align="center">'.get_avatar( $user->ID,38 ).'</td>';
		echo '<td style="vertical-align:middle;"><a href="'.$user_link.'?userid='.$user->ID.'">'.$user->display_name . '</a></td>';
		echo '</tr>';

		
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
