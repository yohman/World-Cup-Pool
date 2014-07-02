							<!--
								world cup content 
							-->


							<ul>
								<?php
								$blogusers = get_users('blog_id=1&orderby=who_will_win_the_2014_world_cup');
								global $wpdb;
								echo count($blogusers) . ' total users';
								echo '<table class="table table-bordered table-condensed small" id="wc_usertable">';
								echo '<tbody>';
								$rank = 1;

								foreach ($blogusers as $user) 
								{
									echo '<tr>';
									$now = date('Y-m-d H:i:s');

									// get games that have already happened
									$args_gamespassed = array(
										'post_type'		=> 'match',
										'posts_per_page' 	=> -1,
										'meta_query'	=> array(
											array(
												'key' 	=> 'match_date',
												'value' => $now,
												'compare' => '<'
											)
										)
									);

									$query_gamespassed = new WP_Query($args_gamespassed);
									echo '<td>'.$rank;
									$user_score = 0;
									// loop through each game passed
									foreach ($query_gamespassed->posts as $post) 
									{
										// query this user's prediction for this match
										// get user predictions
										$args_thisuserprediction = array(
											'post_type' 	=> 'prediction',
											'meta_query' 	=> array(
												array(
													'key' 	=> 'match_id',
													'value' => $post->ID
												),
												array(
													'key' 	=> 'user_id',
													'value' => $user->ID
												)
											)
										);
										$prediction_query = new WP_Query( $args_thisuserprediction );
										$prediction_custom_fields = get_post_custom($prediction_query->post->ID);
										$prediction_team1score = $prediction_custom_fields['team_1_score'][0];
										$prediction_team2score = $prediction_custom_fields['team_2_score'][0];
										// custom fields
										$custom_fields = get_post_custom($post->ID);

										$team1_customfield = $custom_fields['team1'][0];
										$team1_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team1_customfield'" );
										$team2_customfield = $custom_fields['team2'][0];
										$team2_name = $wpdb->get_var( "SELECT name FROM wc_country WHERE iso='$team2_customfield'" );
										$team1score_customfield = $custom_fields['team1_score'][0];
										$team2score_customfield = $custom_fields['team2_score'][0];
										if($prediction_team1score != '' && $prediction_team2score != '')
										{
											if($prediction_team1score == $team1score_customfield && $prediction_team2score == $team2score_customfield)
											{
												echo '+3 Dompisha!<br>';
												$user_score = $user_score + 3;
											}
											// if win is guessed correctly
											elseif(($prediction_team1score > $prediction_team2score && $team1score_customfield > $team2score_customfield) || ($prediction_team1score < $prediction_team2score && $team1score_customfield < $team2score_customfield))
											{
												echo '+1 Right winner!<br>';
												$user_score = $user_score + 1;
											}
											elseif($prediction_team1score == $prediction_team2score && $team1score_customfield != '' &&  $team2score_customfield !='' && $team1score_customfield == $team2score_customfield)
											{
												echo '+1 You guessed a tie!<br>';
												$user_score = $user_score + 1;
											}

											echo '<li style="margin-left:10px;">'.$team1_name.'['.$prediction_team1score.']'.$team1score_customfield.' - '.$team2_name.'['.$prediction_team2score.']'.$team2score_customfield.'</li>';
										}
										// print '<td><pre>';
										// print_r($team1_customfield);
										// print '</pre></td>';
										
									} //end for loop
									echo '</td>';
									echo '<td><button class="btn btn-info" style="font-size:2em;">'.$user_score.'</button></td>';
									echo '<td align="center">'.get_avatar( $user->ID,32 ).'<br>';
									echo $user->display_name . ' (' . $user->who_will_win_the_2014_world_cup . ')</td>';

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
