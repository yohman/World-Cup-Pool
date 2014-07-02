<?php
/*
Template Name: wc 2014 Country Page
*/

get_header();
if ($parabola_frontpage=="Enable" && is_front_page() && !is_page()): get_template_part( 'frontpage' );
else :
?>
	<section id="container" class="<?php echo parabola_get_layout_class(); ?>">
		<div id="content" role="main">
			<?php cryout_before_content_hook(); ?>
			<?php
			$options= parabola_get_theme_options();
			foreach ($options as $key => $value) {
			     ${"$key"} = $value ;
			} 
			?>
			<?php cryout_before_article_hook(); ?>

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
				<?php cryout_post_before_content_hook(); ?>
				<?php if ( is_archive() || is_search() ) : // Display excerpts for archives and search. ?>
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
							<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'parabola' ) . '</span>', 'after' => '</div>' ) ); ?>
						</div><!-- .entry-content --> 
				<?php }  
				endif; 
				?>


				<!--
					world cup content 
				-->

				<!-- This is the list of matches sorted by date -->
				<?php 
					global $wpdb;

					// query to find custom sort type for matches
					$loop = new WP_Query( array( 'post_type' => 'country', 'posts_per_page' => -1) ); 

					echo '<table class="table">';

					while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<tr><td class="col-md-2" title="<?php echo get_the_title();?>">
							<a href="<?php the_permalink();?>" data-toggle="tooltip" title="<?php echo get_the_title()?>"><img src="<?php echo get_stylesheet_directory_uri();?>/images/flags/shiny/64/<?php echo get_the_title()?>.png" style='vertical-align:middle'><span style="font-size:2em"><?php echo get_the_title()?></span></a>
						</td></tr>
						<!-- <div class="clearfix visible-xs"></div> -->
					<?php 
					endwhile;

					echo "</table>";
					// $wpdb->show_errors(); 
					// $wpdb->print_error(); 
				?> 
				<script type="text/javascript">
					jQuery('[data-toggle="tooltip"]').tooltip({
						'placement': 'top'
					});
				</script>
				<!-- end world cup content -->
				
				<?php cryout_post_after_content_hook();  ?>
			</article><!-- #post-<?php the_ID(); ?> -->
			<?php cryout_after_article_hook(); ?>

		<?php cryout_after_content_hook(); ?>
		</div><!-- #content -->
		<?php parabola_get_sidebar(); ?>
	</section><!-- #container -->
<?php
endif;
get_footer();
?>

