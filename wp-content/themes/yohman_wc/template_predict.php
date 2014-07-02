<?php

/*
Template Name: predict
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

get_header(); ?>

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

						<!-- show world cup match as title -->
						<?php
							$match = $wpdb->get_row("SELECT * FROM wc_match WHERE id = ".get_query_var('id').";");
						?>
						<h1 class="entry-title"><?php echo $match->date; ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>

						<!--
							world cup content 
						-->
						<?php
						$team1 = $wpdb->get_row("SELECT * FROM wc_country WHERE group_id = '$match->team1_id';");
						$team2 = $wpdb->get_row("SELECT * FROM wc_country WHERE group_id = '$match->team2_id';");
						
							echo "<table>";
							echo "<tr>";
							echo "<td>".$match->id."</td>";
							echo "<td> <a href='".add_query_arg( 'id', $match->id, get_page_link(13))."'>".$match->date."</a></td>";
							echo "<td style='text-align:right;'>".$team1->name." <img src='http://gis.ats.ucla.edu/testroom/flags/shiny/32/".$team1->name.".png' style='vertical-align:middle'></td>";
							echo "<td><button>-</button></td>";
							echo "<td><button>-</button></td>";
							echo "<td><img src='http://gis.ats.ucla.edu/testroom/flags/shiny/32/".$team2->name.".png' style='vertical-align:middle'> ".$team2->name."</td>";
							echo "</tr>";
							echo "</table>";
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