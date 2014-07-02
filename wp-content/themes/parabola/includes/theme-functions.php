<?php
/**
 * Misc functions breadcrumbs / pagination / transient data /back to top button
 *
 * @package parabola
 * @subpackage Functions
 */


 /**
 * Loads necessary scripts
 * Adds HTML5 tags for IE8
 * Used in header.php
*/
function parabola_header_scripts() {
	$parabolas= parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ; }
?>
<!--[if lt IE 9]>
<script>
document.createElement('header');
document.createElement('nav');
document.createElement('section');
document.createElement('article');
document.createElement('aside');
document.createElement('footer');
</script>
<![endif]-->
<script type="text/javascript">
function makeDoubleDelegate(function1, function2) {
	// concatenate functions
    return function() { if (function1) function1(); if (function2) function2(); }
}

function parabola_onload() {
<?php if ($parabola_mobile=="Enable") { // If mobile view is enabled ?>
    // Add responsive videos
     jQuery(".entry-content").fitVids();
	<?php } ?>
}; // parabola_onload

jQuery(document).ready(function(){
<?php if ($parabola_mobile=="Enable") { // If mobile view is enabled ?>
	// Add select navigation to small screens
     jQuery("#access > .menu > ul").tinyNav({
          	header: ' = <?php _e('Menu','parabola'); ?> = ' // Show header instead of the active item
			});
<?php } ?>
});

// make sure not to lose previous onload events
window.onload = makeDoubleDelegate(window.onload, parabola_onload );
</script>
<?php
} // parabola_header_scripts()

add_action('wp_head','parabola_header_scripts',100);


 /**
 * Adds title and description to heaer
 * Used in header.php
*/
function parabola_title_and_description() {
	$parabolas = parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ; }
	// Header styling and image loading
	// Check if this is a post or page, if it has a thumbnail, and if it's a big one
	global $post;

	if (get_header_image() != '') { $himgsrc=get_header_image(); }
	if ( is_singular() && has_post_thumbnail( $post->ID ) && $parabola_fheader == "Enable" &&
		( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'header' ) ) &&
		$image[1] >= HEADER_IMAGE_WIDTH ) : $himgsrc= $image[0];
	endif;


	if (isset($himgsrc) && ($himgsrc != '')) : echo '<img id="bg_image" alt="" title="" src="'.$himgsrc.'"  />';  endif;
?>
<div id="header-container">
<?php

	switch ($parabola_siteheader) {
		case 'Site Title and Description':
			echo '<div>';
			$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
			echo '<'.$heading_tag.' id="site-title">';
			echo '<span> <a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'" rel="home">'.get_bloginfo( 'name' ).'</a> </span>';
			echo '</'.$heading_tag.'>';
			echo '<div id="site-description" >'.get_bloginfo( 'description' ).'</div></div>';
		break;

		case 'Clickable header image' :
			echo '<a href="'.esc_url( home_url( '/' ) ).'" id="linky"></a>' ;
		break;

		case 'Custom Logo' :
			if (isset($parabola_logoupload) && ($parabola_logoupload != '')) :
				echo '<div><a id="logo" href="'.esc_url( home_url( '/' ) ).'" ><img title="" alt="" src="'.$parabola_logoupload.'" /></a></div>';
			endif;
		break;

		case 'Empty' :
		break;
	}

	if($parabola_socialsdisplay0) parabola_header_socials();
	echo '</div>';
} // parabola_title_and_description()

add_action ('cryout_branding_hook','parabola_title_and_description');


 /**
 * Add social icons in header / undermneu left / undermenu right / footer / left browser side / right browser side
 * Used in header.php and footer.php
*/
function parabola_header_socials() {
	parabola_set_social_icons('sheader');
}

function parabola_smenul_socials() {
	parabola_set_social_icons('smenul');
}

function parabola_smenur_socials() {
	parabola_set_social_icons('smenur');
}

function parabola_footer_socials() {
	parabola_set_social_icons('sfooter');
}

function parabola_slefts_socials() {
	parabola_set_social_icons('slefts');
}

function parabola_srights_socials() {
	parabola_set_social_icons('srights');
}

// Adding socials to the footers
if($parabola_socialsdisplay3) add_action('cryout_footer_hook', 'parabola_footer_socials',13);
// Adding socials to the left and right browser sides
if($parabola_socialsdisplay4) add_action('cryout_wrapper_hook', 'parabola_slefts_socials',13);
if($parabola_socialsdisplay5) add_action('cryout_wrapper_hook', 'parabola_srights_socials',13);


if ( ! function_exists( 'parabola_set_social_icons' ) ) :
/**
 * Social icons function
 */
function parabola_set_social_icons($id) {
	$cryout_special_keys = array('Mail', 'Skype');
	global $parabolas;
	foreach ($parabolas as $key => $value) {
		${"$key"} = $value ;
	}
	echo '<div class="socials" id="'.$id.'">';
	for ($i=1; $i<=9; $i+=2) {
		$j=$i+1;
		if ( ${"parabola_social$j"} ) {
			if (in_array(${"parabola_social$i"},$cryout_special_keys)) :
				$cryout_current_social = esc_html( ${"parabola_social$j"} );
			else :
				$cryout_current_social = esc_url( ${"parabola_social$j"} );
			endif;	?>

			<a <?php if ($parabolas['parabola_social_target'.$i]) {echo ' target="_blank" ';} ?> rel="nofollow" href="<?php echo $cryout_current_social; ?>"
			class="socialicons social-<?php echo esc_attr(${"parabola_social$i"}); ?>" title="<?php echo ${"parabola_social_title$i"} !="" ? esc_attr(${"parabola_social_title$i"}) : esc_attr(${"parabola_social$i"}); ?>">
				<img alt="<?php echo esc_attr(${"parabola_social$i"}); ?>" src="<?php echo get_template_directory_uri().'/images/socials/'.${"parabola_social$i"}.'.png'; ?>" />
			</a><?php
		}
	}
	echo '</div>';
} // parabola_set_social_icons()
endif;


/**
 * Parabola back to top button
 * Creates div for js
*/
function parabola_back_top() {
	echo '<div id="toTop"> </div>';
} // parabola_back_top()

if ($parabola_backtop=="Enable") add_action ('cryout_body_hook','parabola_back_top');


 /**
 * Creates breadcrumns with page sublevels and category sublevels.
 */
function parabola_breadcrumbs() {
	$parabolas= parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ; }
	global $post;
	if (is_page() && !is_front_page() || is_single() || is_category() || is_archive()) {
		echo '<div class="breadcrumbs">';
        echo '<a href="'.get_bloginfo('url').'">'.get_bloginfo('name').' &raquo; </a>';

        if (is_page()) {

			$ancestors = get_post_ancestors($post);
            if ($ancestors) {

				$ancestors = array_reverse($ancestors);
                foreach ($ancestors as $crumb) {
                    echo '<a href="'.get_permalink($crumb).'">'.get_the_title($crumb).' &raquo; </a>';
                }
            }
        }

        if (is_single()) {
			if (has_category()) {
				$category = get_the_category();
				echo '<a href="'.get_category_link($category[0]->cat_ID).'">'.$category[0]->cat_name.' &raquo; </a>';
			}
        }

        if (is_category()) {
            $category = get_the_category();
            echo ''.$category[0]->cat_name.'';
        }

        // Current page
        if (is_page() || is_single()) {
            echo ''.get_the_title().'';
        }
       echo '</div>';
    }
	elseif (is_home() && $parabola_frontpage!="Enable" ) {
        // Front page
        echo '<div class="breadcrumbs">';
        echo '<a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a> '."&raquo; ";
        _e('Home Page','parabola');
        echo '</div>';
    }

} // parabola_breadcrumbs()


if($parabola_breadcrumbs=="Enable")  add_action ('cryout_breadcrumbs_hook','parabola_breadcrumbs');


if ( ! function_exists( 'parabola_pagination' ) ) :
/**
 * Creates pagination for blog pages.
 */
function parabola_pagination($pages = '', $range = 2, $prefix ='')
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {
		echo "<div class='pagination_container'><nav class='pagination'>";
         if ($prefix) {echo "<span id='paginationPrefix'>$prefix </span>";}
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</nav></div>\n";
     }
} // parabola_pagination()
endif;

function parabola_nextpage_links($defaults) {
$args = array(
'link_before'      => '<em>',
'link_after'       => '</em>',
);
$r = wp_parse_args($args, $defaults);
return $r;
}
add_filter('wp_link_pages_args','parabola_nextpage_links');


/**
 * Site info
 */
function parabola_site_info() {
	$parabolas = parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ; }	?>
	<div style="text-align:center;padding:5px 0 2px;text-transform:uppercase;font-size:11px;">
	<?php _e('Powered by','parabola')?> <a target="_blank" href="<?php echo 'http://www.cryoutcreations.eu';?>" title="<?php echo 'Parabola Theme by '.
			'Cryout Creations';?>"><?php echo 'Pa&#1103;abola' ?></a> &amp; <a target="_blank" href="<?php echo esc_url('http://wordpress.org/' ); ?>"
			title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'parabola'); ?>"> <?php printf(' %s.', 'WordPress' ); ?>
		</a>
	</div><!-- #site-info -->
	<?php
} // parabola_site_info()

add_action('cryout_footer_hook','parabola_site_info',12);


/**
 * Copyright text
 */
function parabola_copyright() {
	$parabolas = parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ; }
	echo '<div id="site-copyright">'.$parabola_copyright.'</div>';
} // parabola_copyright()


if ($parabola_copyright != '') add_action('cryout_footer_hook','parabola_copyright',11);

add_action('wp_ajax_nopriv_do_ajax', 'parabola_ajax_function');
add_action('wp_ajax_do_ajax', 'parabola_ajax_function');

if ( ! function_exists( 'parabola_ajax_function' ) ) :
function parabola_ajax_function(){
	ob_clean();

   // the first part is a SWTICHBOARD that fires specific functions
   // according to the value of Query Var 'fn'

	switch($_REQUEST['fn']){
		case 'get_latest_posts':
			$output = parabola_ajax_get_latest_posts($_REQUEST['count'],$_REQUEST['categName']);
		break;
		default:
			$output = 'No function specified, check your jQuery.ajax() call';
		break;
	}

	// at this point, $output contains some sort of valuable data!
	// Now, convert $output to JSON and echo it to the browser
	// That way, we can recapture it with jQuery and run our success function

	$output=json_encode($output);
	if(is_array($output)) { print_r($output); }
	                 else { echo $output; }
	die;
} // parabola_ajax_function()
endif;

if ( ! function_exists( 'parabola_ajax_get_latest_posts' ) ) :
function parabola_ajax_get_latest_posts($count,$categName){
	$testVar='';
	// The Query
	query_posts( 'category_name='.$categName);
	// The Loop
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			$testVar .=the_title("<option>","</option>",0);
		endwhile;
	else:
	endif;
	// Reset Query
	wp_reset_query();
	return $testVar;
} // parabola_ajax_get_latest_posts()
endif;


function parabola_get_sidebar() {
	$parabolas = parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ; }
	switch($parabola_side) {

		case '2cSl':
			get_sidebar('left');
		break;

		case '2cSr':
			get_sidebar('right');
		break;

		case '3cSl' : case '3cSr' : case '3cSs' :
			get_sidebar('left');
			get_sidebar('right');
		break;

		default:
		break;
	}
} // parabola_get_sidebar()

function parabola_get_layout_class() {
	$parabolas = parabola_get_theme_options();
	foreach ($parabolas as $key => $value) { ${"$key"} = $value ; }
	switch($parabola_side) {
		case '2cSl': return "two-columns-left"; break;
		case '2cSr': return "two-columns-right"; break;
		case '3cSl': return "three-columns-left"; break;
		case '3cSr' : return "three-columns-right"; break;
		case '3cSs' : return "three-columns-sided"; break;
		case '1c':
		default: return "one-column"; break;
	}
} // parabola_get_layout_class()


/**
* Retrieves the IDs for images in a gallery.
* @since parabola 1.0.3
* @return array List of image IDs from the post gallery.
*/
function parabola_get_gallery_images() {
       $images = array();

       if ( function_exists( 'get_post_galleries' ) ) {
               $galleries = get_post_galleries( get_the_ID(), false );
               if ( isset( $galleries[0]['ids'] ) )
                       $images = explode( ',', $galleries[0]['ids'] );
       } else {
               $pattern = get_shortcode_regex();
               preg_match( "/$pattern/s", get_the_content(), $match );
               $atts = shortcode_parse_atts( $match[3] );
               if ( isset( $atts['ids'] ) )
                       $images = explode( ',', $atts['ids'] );
       }

       if ( ! $images ) {
               $images = get_posts( array(
                       'fields'         => 'ids',
                       'numberposts'    => 999,
                       'order'          => 'ASC',
                       'orderby'        => 'none',
                       'post_mime_type' => 'image',
                       'post_parent'    => get_the_ID(),
                       'post_type'      => 'attachment',
               ) );
       }

       return $images;
} // parabola_get_gallery_images()


/**
* Checks the browser agent string for mobile ids and adds "mobile" class to body if true
* @since parabola 1.2.3
* @return array list of classes.
*/
function parabola_mobile_body_class($classes){
$parabolas = parabola_get_theme_options();
     if ($parabolas['parabola_mobile']=="Enable"):
          $browser = $_SERVER['HTTP_USER_AGENT'];
          $keys = 'mobile|android|mobi|tablet|ipad|opera mini|series 60|s60|blackberry';
          if (preg_match("/($keys)/i",$browser)): $classes[] = 'mobile'; endif; // mobile browser detected
     endif;
     return $classes;
}

add_filter('body_class', 'parabola_mobile_body_class');


////////// HELPER FUNCTIONS //////////

function cryout_optset($var,$val1,$val2='',$val3='',$val4=''){
	$vals = array($val1,$val2,$val3,$val4);
	if (in_array($var,$vals)): return false; else: return true; endif;
} // cryout_optset()

function cryout_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);
   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
        if(strlen($hex) == 3) {
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return implode(",", $rgb); // returns the rgb values separated by commas
   else: return "";  // input string is not a valid hex color code
   endif;
} // cryout_cryout_hex2rgb()

?>