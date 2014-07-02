<?php

/*
Template Name: match
*/

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); 

?>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/timeago.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/jquery.countdown.js"></script>
<script>
jQuery(document).ready(function() {
  jQuery.timeago.settings.allowFuture = true;
  jQuery("abbr.timeago").timeago();
});
</script>
<?php echo get_current_user_id(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
						<?php endif; ?>

						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>

						<!--
							world cup content 
						-->

						<script>
						function launchModal(matchid,team1name, team2name)
						{
							jQuery('#myModal').modal('toggle');
							jQuery('#myModalLabel').html(team1name+' vs '+team2name);
							jQuery('#MatchID').val(matchid);
							jQuery('#predict-team1-flag').html('<img src="".get_stylesheet_directory_uri()."/images/flags/shiny/64/'+team1name+'.png"> '+team1name)
							jQuery('#predict-team2-flag').html('<img src="".get_stylesheet_directory_uri()."/images/flags/shiny/64/'+team2name+'.png"> '+team2name)
						}

						</script>

						<!-- Prediction Modal -->
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header" style="background:#555">
						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
						      </div>
						      <div class="modal-body" style="background:#333">

								<form action="<?php echo get_page_link(23); ?>" method="POST">
								
								<input type="Hidden" id="MatchID" name="MatchID">
								
								<font color="Silver">Enter your prediction for the match below:</font>
								<br>
								<br>
								
								<table bgcolor="dimgray">
								<tr>
									<td style="vertical-align: middle;" id="predict-team1-flag"></td>
									<td style="vertical-align: middle;" id="predict-team1-name"></td>
									<td style="vertical-align: middle;">
										<input class="form-control input-lg col-xs-1" type="Text" id="Team1Score" name="Team1Score" required="Yes"></td>
									
									<td style="vertical-align: middle;">-</td>
									
									<td style="vertical-align: middle;">
										<input class="form-control input-lg col-xs-1" type="Text" id="Team2Score" name="Team2Score" required="Yes"></td>
									<td style="vertical-align: middle;" id="predict-team2-name"></td>
									<td style="vertical-align: middle;" id="predict-team2-flag"></td>
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
						      <div class="modal-footer" style="background:#444;margin-top:0px;">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary">Save changes</button>
						      </div>
						    </div><!-- /.modal-content -->
						  </div><!-- /.modal-dialog -->
						</div><!-- /.modal -->


						<?php
						global $wpdb;

						$groups = $wpdb->get_results("SELECT * FROM wc_group;");

						foreach($groups as $group)
						{
							echo "<h1>Group $group->name</h1>";
							echo "<table class='table'>";

							$thisgroup = $group->name;
							$matches = $wpdb->get_results("SELECT * FROM wc_match WHERE group_name = '$thisgroup';");

							foreach($matches as $match)
							{
								// get user prediction for this match
								$thisprediction = $wpdb->get_results("SELECT * FROM wc_prediction WHERE wc_match_id = $match->id AND wp_users_ID = ". get_current_user_id());
								if ($thisprediction != null) 
								{
									$Team1Score = $thisprediction[0]->Team1Score;
									$Team2Score = $thisprediction[0]->Team2Score;
									$labelclass = 'label-primary';
								} 
								else 
								{
									$Team1Score = '?';
									$Team2Score = '?';
									$labelclass = 'label-default';
								}

								$team1 = $wpdb->get_row("SELECT * FROM wc_country WHERE group_id = '$match->team1_id';");
								$team2 = $wpdb->get_row("SELECT * FROM wc_country WHERE group_id = '$match->team2_id';");
							
								echo "<tr>";
								echo "<td style='vertical-align:middle'>".$match->id."</td>";
								echo "<td style='vertical-align:middle' id='matchdate-".$match->id."'> <a href='".add_query_arg( 'id', $match->id, get_page_link(13))."'>".$match->date."<br><div id='getting-started'>Match in <span>%m</span> months <span>%d</span> days <span>%H</span> hours <span>%M</span> minutes <span>%S</span> seconds</div></a></td>";
								echo "<td style='text-align:right;vertical-align:middle'><img src='".get_stylesheet_directory_uri()."/images/flags/shiny/64/".$team1->name.".png' style='vertical-align:middle'><br>".$team1->name."</td>";
								echo "<td style='vertical-align:middle'><h1 class='label ".$labelclass."'>".$Team1Score."</h1></td>";
								echo "<td style='vertical-align:middle'><h1 class='label ".$labelclass."'>".$Team2Score."</h1></td>";
								echo "<td style='vertical-align:middle'><img src='".get_stylesheet_directory_uri()."/images/flags/shiny/64/".$team2->name.".png' style='vertical-align:middle'><br>".$team2->name."</td>";
								echo "<td style='vertical-align:middle'><button class='btn btn-warning' onclick=\"launchModal('".$match->id."','".$team1->name."','".$team2->name."')\">predict</button></td>";
								echo "</tr>";
								?>

									<script type="text/javascript">
										var startdate = '<?php echo $match->date; ?>';
										startdate = startdate.replace(/-/g,"/");
										jQuery('#matchdate-<?php echo $match->id; ?>').countdown(startdate, function(event) {
											jQuery(this).html(event.strftime('<span class="badge label-primary" style="font-size:16px;">%m<br><span style="color:black;font-size:10px;">months</span></span> <span class="badge label-primary" style="font-size:16px;">%d<br><span style="color:black;font-size:10px;">days</span></span> <span class="badge label-primary" style="font-size:16px;">%H<br><span style="color:black;font-size:10px;">hours</span></span> <span class="badge label-primary" style="font-size:16px;">%M<br><span style="color:black;font-size:10px;">minutes</span></span> <span class="badge label-primary" style="font-size:16px;">%S<br><span style="color:black;font-size:10px;">seconds</span></span>'));
										});
									</script>
								<?php 


									

							}

							echo "</table>";
						}

						?>
						<?php $wpdb->show_errors(); ?> 
						<?php $wpdb->print_error(); ?> 
						<!-- end world cup content -->

					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post -->

				<?php comments_template(); ?>
			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>