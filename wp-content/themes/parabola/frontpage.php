<?php
/**
 * Frontpage generation functions
 * Creates the slider, the columns, the titles and the extra text
 *
 * @package parabola
 * @subpackage Functions
 */

function parabola_excerpt_length_slider( $length ) {
	return 50;
}

function parabola_excerpt_more_slider( $more ) {
	return '...';
}


// if ( ! function_exists( 'parabola_frontpage_generator' ) ) :
// Front page generator
//function parabola_frontpage_generator() {
     $parabolas= parabola_get_theme_options();
     foreach ($parabolas as $key => $value) { ${"$key"} = $value; } ?>

<script type="text/javascript">
     jQuery(document).ready(function() {
	// Slider creation
     jQuery('#slider').nivoSlider({
		effect: '<?php  echo $parabola_fpslideranim; ?>',
       	animSpeed: <?php echo $parabola_fpslidertime; ?>,
		<?php if($parabola_fpsliderarrows=="Hidden"): ?>directionNav: false,<?php endif;
   		      if($parabola_fpsliderarrows=="Always Visible"): ?>directionNavHide: false,<?php endif; ?>
		pauseTime: <?php echo $parabola_fpsliderpause; ?>
     });
     // Flash animation for columns
     jQuery('.column-image > img').stop().css({"opacity":"0.999"});
     jQuery('#front-columns > div > a ').hover(
          function() {
            jQuery(this).find('.column-header-image').stop().css('display','block').animate({bottom:'30px',opacity:1},{queue:false,duration:200});
     		jQuery(this).find('img').stop().animate({opacity:'0.7'},{queue:false,duration:600});
          }, function () {
     		jQuery(this).find('.column-header-image').stop().animate({bottom:'-50px',opacity:0},{queue:false,duration:200});
     		jQuery(this).find('img').stop().animate({opacity:'0.999'},{queue:false,duration:200});
     	})
	});
</script> 

<div id="frontpage">
<?php

// First FrontPage Title
if($parabola_fronttext1) {?><div id="front-text1"> <h1><?php echo esc_attr($parabola_fronttext1) ?> </h1></div><?php }

// When a post query has been selected from the Slider type in the admin area
     global $post;
     // Initiating query
     $custom_query = new WP_query();
     $slides = array();
	 
	 if($parabola_slideNumber>0):

     // Switch for Query type
     switch ($parabola_slideType) {
          case 'Latest Posts' :
               $custom_query->query('showposts='.$parabola_slideNumber.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts' :
               $custom_query->query('showposts='.$parabola_slideNumber.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Latest Posts from Category' :
               $custom_query->query('showposts='.$parabola_slideNumber.'&category_name='.$parabola_slideCateg.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts from Category' :
               $custom_query->query('showposts='.$parabola_slideNumber.'&category_name='.$parabola_slideCateg.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Sticky Posts' :
               $custom_query->query(array('post__in'  => get_option( 'sticky_posts' ), 'showposts' =>$parabola_slideNumber,'ignore_sticky_posts' => 1));
          break;
          case 'Specific Posts' :
               // Transofm string separated by commas into array
               $pieces_array = explode(",", $parabola_slideSpecific);
               $custom_query->query(array( 'post_type' => 'any', 'post__in' => $pieces_array, 'ignore_sticky_posts' => 1,'orderby' => 'post__in' ));
               break;
          case 'Custom Slides':

               break;
     }//switch
	 
	 endif; // slidenumber>0

	 add_filter( 'excerpt_length', 'parabola_excerpt_length_slider', 999 );
	 add_filter( 'excerpt_more', 'parabola_excerpt_more_slider', 999 );
     // switch for reading/creating the slides
     switch ($parabola_slideType) {
          case 'Custom Slides':
               for ($i=1;$i<=5;$i++):
                    if(${"parabola_sliderimg$i"}):
                         $slide['image'] = esc_url(${"parabola_sliderimg$i"});
                         $slide['link'] = esc_url(${"parabola_sliderlink$i"});
                         $slide['title'] = ${"parabola_slidertitle$i"};
                         $slide['text'] = ${"parabola_slidertext$i"};
                         $slides[] = $slide;
                    endif;
               endfor;
               break;
          default:
			   if($parabola_slideNumber>0):	
               if ( $custom_query->have_posts() ) while ($custom_query->have_posts()) :
                    $custom_query->the_post();
                         $img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'slider');
                	$slide['image'] = $img[0];
                	$slide['link'] = get_permalink();
                	$slide['title'] = get_the_title();
                	$slide['text'] = get_the_excerpt();
                	$slides[] = $slide;
               endwhile;
			   endif; // slidenumber>0
               break;
     }; // switch


if (count($slides)>0):
     ?>
<div class="slider-wrapper theme-default <?php if($parabola_fpsliderarrows=="Visible on Hover"): ?>slider-navhover<?php endif; ?> slider-<?php echo  preg_replace("/[^a-z0-9]/i","",strtolower($parabola_fpslidernav)); ?>">
     <div class="ribbon"></div>
     <div id="slider" class="nivoSlider">
	<?php foreach($slides as $id=>$slide):
            if($slide['image']): ?>
            <a href='<?php echo ($slide['link']?$slide['link']:'#'); ?>'>
                 <img src='<?php echo $slide['image']; ?>' alt="" <?php if ($slide['title'] || $slide['text']): ?>title="#caption<?php echo $id;?>" <?php endif; ?> />
            </a><?php endif; ?>
     <?php endforeach; ?>
     </div>
     <?php foreach($slides as $id=>$slide): ?>
            <div id="caption<?php echo $id;?>" class="nivo-html-caption">
                <?php echo '<h2>'.$slide['title'].'</h2>'.$slide['text']; ?>
            </div>
	<?php endforeach; ?>
     </div>
<?php
endif; 
// Second FrontPage title
if($parabola_fronttext2) {?><div id="front-text2"> <h1><?php echo esc_attr($parabola_fronttext2) ?> </h1></div><?php }

// Frontpage columns
if($parabola_nrcolumns) { ?>
<div id="front-columns">
<?php for ($i=1;$i<=$parabola_nrcolumns;$i++) { ?>
     <div id="column<?php echo $i; ?>">
     	<a  href="<?php echo esc_url(${"parabola_columnlink$i"}) ?>">
			<?php if (${"parabola_columnimg$i"}) {	?>
					<div class="column-image">
						<img  src="<?php echo esc_url(${"parabola_columnimg$i"}) ?>" id="columnImage<?php echo $i; ?>" alt="" />
						<?php if (${"parabola_columntitle$i"}) { echo "<h3 class='column-header-image'>${'parabola_columntitle'.$i}</h3>"; }
					echo '</div>';
					}
					else {
						if (${"parabola_columntitle$i"}) { 
							echo "<h3 class='column-header-noimage'>${'parabola_columntitle'.$i}</h3>"; 
							} 
					} ?>
		</a>

		<?php if (${"parabola_columntext$i"}) { ?>		
			<div class="column-text">
				<?php echo do_shortcode(${"parabola_columntext$i"});
				if($parabola_columnreadmore && ${"parabola_columnlink$i"} ): ?>
					<div class="columnmore">
						<a href="<?php echo esc_url(${"parabola_columnlink$i"}) ?>"><?php echo esc_attr($parabola_columnreadmore) ?> &raquo;</a>
					</div>
               <?php endif; ?>
			</div>
		<?php } ?>
	
	</div>	
<?php } //for ?>
</div>
<?php } // columns

// Frontpage text areas
if($parabola_fronttext3) {?><div id="front-text3"> <blockquote><?php echo do_shortcode($parabola_fronttext3) ?> </blockquote></div><?php }
if($parabola_fronttext4) {?><div id="front-text4"> <blockquote><?php echo do_shortcode($parabola_fronttext4) ?> </blockquote></div><?php }
?>
</div> <!-- frontpage -->
<?php	
 
remove_filter( 'excerpt_length', 'parabola_excerpt_length_slider', 999 );
remove_filter( 'excerpt_more', 'parabola_excerpt_more_slider', 999 );

 if ($parabola_frontposts=="Enable"): get_template_part('content/content', 'frontpage'); endif; ?>
<?php // End of parabola_frontpage_generator
?>
