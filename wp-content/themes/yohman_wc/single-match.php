<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Cryout Creations
 * @subpackage parabola
 * @since parabola 0.5
 */

get_header(); ?>

		<section id="container" class="one-column">
	
			<div id="content" role="main">



			<?php

			$options= parabola_get_theme_options();
			foreach ($options as $key => $value) {
			     ${"$key"} = $value ;
			} 

			?><?php cryout_before_article_hook(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php parabola_comments_on(); ?>
					<header class="entry-header">			
						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'parabola' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
						</h2>
						<?php cryout_post_title_hook(); 
						?><?php if ( 'post' == get_post_type() ) : ?>
						<div class="entry-meta">
							<?php parabola_posted_on(); 
								cryout_post_meta_hook();  ?>
						</div><!-- .entry-meta -->
						<?php endif; ?>

					
					</header><!-- .entry-header -->
						<?php cryout_post_before_content_hook();  
						?><?php if ( is_archive() || is_search() ) : // Display excerpts for archives and search. ?>
						
									<?php if ($parabola_excerptarchive != "Full Post" ){ ?>
									<div class="entry-summary">
									<?php parabola_set_featured_thumb(); ?>
									<?php the_excerpt(); ?>
									</div><!-- .entry-summary -->
									<?php } else { ?>
									<div class="entry-content">
									<?php the_content(); ?>
									<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'parabola' ) . '</span>', 'after' => '</div>' ) ); ?>
									</div><!-- .entry-content --> 
									<?php }   ?>
						
					<?php else : 
							if (is_sticky() && $parabola_excerptsticky == "Full Post")  $sticky_test=1; else $sticky_test=0;
							if ($parabola_excerpthome != "Full Post" && $sticky_test==0){ ?>
								
								
									<div class="entry-summary">
									<?php parabola_set_featured_thumb(); ?>
									<?php the_excerpt(); ?>
									</div><!-- .entry-summary --> 
									<?php } else { ?>
									<div class="entry-content">
									<?php the_content(); ?>



				<!--
					world cup content 
				-->

				<!-- This is the list of matches sorted by date -->
				<?php 
					global $wpdb;

					// get country iso
					$meta = get_post_meta( get_the_ID(),'iso'); 

					// query to find custom sort type for matches
					$loop = new WP_Query( array( 
						'p' => $post->ID,
						'post_type' => 'match', 
						'posts_per_page' => -1, 
						'meta_value' => $meta[0],
						// 'orderby' => 'meta_value',
					 //    'meta_key' => 'match_date' 
					)); 
					$match_query = new WP_Query( $loop );

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

					// echo '<h2>Match Schedule</h2>';
					// echo '<table class="table">';
					include 'template_predict_include.php';


					$query_predict = array(
						'post_type' 	=> 'prediction',
						'posts_per_page' => -1, 
						'meta_query' 	=> array(
							array(
								'key' 	=> 'match_id',
								'value' => $post->ID
							)
						)
					);
					$prediction_query = new WP_Query( $query_predict );

					$ave_team1 = 0;
					$ave_team2 = 0;
					// get averages
						// echo count($prediction_query->posts);
					foreach ($prediction_query->posts as $prediction) 
					{
						// echo count($prediction_query);
						$prediction_custom_fields = get_post_custom($prediction->ID);
						$prediction_team1score = $prediction_custom_fields['team_1_score'][0];
						$prediction_team2score = $prediction_custom_fields['team_2_score'][0];
						$ave_team1 = $ave_team1 + $prediction_team1score;
						$ave_team2 = $ave_team2 + $prediction_team2score;
					}

					// echo round($ave_team1 / count($prediction_query->posts),1);
					// echo ' vs ';
					// echo round($ave_team2 / count($prediction_query->posts),1);

					$prediction_custom_fields = get_post_custom($prediction_query->post->ID);
					$prediction_team1score = $prediction_custom_fields['team_1_score'][0];
					$prediction_team2score = $prediction_custom_fields['team_2_score'][0];
					if($prediction_team1score == ''){$prediction_team1score='-'; $added_prediction_class='btn-default';}else{ $added_prediction_class='btn-success';}
					if($prediction_team2score == ''){$prediction_team2score='-'; $added_prediction_class='btn-default';}else{ $added_prediction_class='btn-success';}


					echo '<h1>User Predictions</h1>';
					echo '<div id="dompisha"></div>';
					echo '<div style="padding:10px;text-align:center;"><button class="btn btn-info">actual</button> <button class="btn btn-success">prediction</button></div>';

					// echo $total_predictions . ' users have predicted:';
					echo '<table class="table table-bordered" style="background:#444;">';
					foreach ($prediction_query->posts as $prediction) 
					{
						$prediction_custom_fields = get_post_custom($prediction->ID);
						$prediction_team1score = $prediction_custom_fields['team_1_score'][0];
						$prediction_team2score = $prediction_custom_fields['team_2_score'][0];
						$prediction_userid = $prediction_custom_fields['user_id'][0];

						$user_info = get_userdata($prediction_userid);
						$prediction_username = $user_info->user_login;

						/* -----------------------------------

							Dompisha or not
							
						 -------------------------------------*/
						 	$dompishas = 0;


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
									$dompishas = $dompishas+1;
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
						echo '<td align="center">'.get_avatar( $prediction_userid,48 ).'</td>';
						echo '<td>'.$prediction_username.'</td>';
						echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team1_name.'.png" style="vertical-align:middle"></td>';
						echo '<td style="vertical-align:middle" align="center"><button class="btn '.$added_class.'">'.$team1score.'</button> <button class="btn '.$added_prediction_class.'">'.$prediction_team1score.'</button></td>';
						echo '<td style="vertical-align:middle" align="center"><button class="btn '.$added_class.'">'.$team2score.'</button> <button class="btn '.$added_prediction_class.'">'.$prediction_team2score.'</button></td>';
						echo '<td style="vertical-align:middle" align="center"><img width="40" src="'.get_stylesheet_directory_uri().'/images/flags/shiny/64/'.$team2_name.'.png" style="vertical-align:middle"></td>';
						echo $resulticon;
						echo '</tr>';
					}
					echo '</table>';
				?>
				<script>
					jQuery('#dompisha').html('<div class="bg-primary" style="text-align:center;"><img width="40" src="https://cdn1.iconfinder.com/data/icons/popo_emotions_the_blacy_png/128/money.png" width="80" style="vertical-align:middle;"> <?php echo $dompishas; ?> dompishas!</div>');
				</script>

				<!-- end world cup content -->





									<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'parabola' ) . '</span>', 'after' => '</div>' ) ); ?>
									</div><!-- .entry-content --> 
									<?php }  

						endif; 
					 cryout_post_after_content_hook();  ?>

				</article><!-- #post-<?php the_ID(); ?> -->
				
				
			<?php cryout_after_article_hook(); ?>






			</div><!-- #content -->
			
		</section><!-- #container -->

<?php get_footer(); ?>
