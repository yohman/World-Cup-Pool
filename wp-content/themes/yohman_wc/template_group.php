<?php
/*
Template Name: wc 2014 Group Page
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



				<?php
				// get all the categories from the database
				$cats = get_categories(); 

				// loop through the categries
				foreach ($cats as $cat) {
					// setup the cateogory ID
					$cat_id= $cat->term_id;

					// Make a header for the cateogry
					echo "<h1>Group ".$cat->name."</h1>";
					// create a custom wordpress query

					// $query = new WP_Query( 'cat=$cat_id' );
					$loop = new WP_Query( array( 'cat' => $cat_id, 'post_type' => 'country', 'posts_per_page' => -1 ) ); 
					echo '<table class="table">';
					while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<!-- <a href="<?php the_permalink();?>"><?php the_title(); ?></a> -->
						<!-- <hr> -->
						<tr>
							<td class="col-md-2" title="<?php echo get_the_title();?>">
								<a href="<?php the_permalink();?>"><img src="<?php echo get_stylesheet_directory_uri();?>/images/flags/shiny/64/<?php echo get_the_title()?>.png" style='vertical-align:middle'></a>
							</td>
							<td class="col-md-8" title="<?php echo get_the_title();?>">
								<h2><?php the_title(); ?></h2>
							</td>
						</tr>
					<?php endwhile; ?>
					</table>
				<?php } // done the foreach statement ?>

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

