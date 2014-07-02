<?php
// Loading files for frontend

// Loading Default values
require_once(dirname(__FILE__) . "/defaults.php");
// Loading function that generates the custom css
require_once(dirname(dirname(__FILE__)) . "/includes/custom-styles.php");

// Loading the admin files

if( is_admin() ) {
// Loading the settings arrays
require_once(dirname(__FILE__) . "/settings.php");
// Loading the callback functions
require_once(dirname(__FILE__) . "/admin-functions.php");
// Loading the sanitize funcions
require_once(dirname(__FILE__) . "/sanitize.php");
// Loading color scheme presets
include(dirname(__FILE__) . "/schemes.php");
}

// Getting the theme options and making sure defaults are used if no values are set
function parabola_get_theme_options() {
	global $parabola_defaults;
	$optionsParabola = get_option( 'parabola_settings', $parabola_defaults );
	$optionsParabola = array_merge((array)$parabola_defaults, (array)$optionsParabola);
return $optionsParabola;
}

$parabolas= parabola_get_theme_options();
foreach ($parabolas as $key => $value) {
     ${"$key"} = $value ;
}


//  Hooks/Filters
add_action('admin_init', 'parabola_init_fn' );
add_action('admin_menu', 'parabola_add_page_fn');
add_action('init', 'parabola_init');


$parabolas= parabola_get_theme_options();

// Registering and enqueuing all scripts and styles for the init hook
function parabola_init() {
//Loading Parabola text domain into the admin section
		load_theme_textdomain( 'parabola', get_template_directory_uri() . '/languages' );
}

// Creating the parabola subpage
function parabola_add_page_fn() {
$page = add_theme_page('Parabola Settings', 'Parabola Settings', 'edit_theme_options', 'parabola-page', 'parabola_page_fn');
	add_action( 'admin_print_styles-'.$page, 'parabola_admin_styles' );
	add_action('admin_print_scripts-'.$page, 'parabola_admin_scripts');

}

// Adding the styles for the Parabola admin page used when parabola_add_page_fn() is launched
function parabola_admin_styles() {
	wp_register_style( 'jquery-ui-style',get_template_directory_uri() . '/js/jqueryui/css/ui-lightness/jquery-ui-1.8.16.custom.css' );
	wp_enqueue_style( 'jquery-ui-style' );
	wp_register_style( 'parabola-admin-style',get_template_directory_uri() . '/admin/css/admin.css' );
	wp_enqueue_style( 'parabola-admin-style' );
     // codemirror css markup
     wp_register_style('cryout-admin-codemirror-style',get_template_directory_uri() . '/admin/css/codemirror.css' );
	wp_enqueue_style('cryout-admin-codemirror-style');
}

// Adding the styles for the Parabola admin page used when parabola_add_page_fn() is launched
function parabola_admin_scripts() {
// The farbtastic color selector already included in WP
	//wp_register_script('farbtastic-wp',get_template_directory_uri() . '/admin/js/accordion-slider.js', array('jquery') );
	//wp_enqueue_script('cryout_accordion');
	wp_enqueue_script('farbtastic');
	wp_enqueue_style( 'farbtastic' );

//Jquery accordion and slider libraries alreay included in WP
    wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-tooltip');
// For backwards compatibility where Parabola is installed on older versions of WP where the ui accordion and slider are not included
	if (!wp_script_is('jquery-ui-accordion',$list='registered')) {
		wp_register_script('cryout_accordion',get_template_directory_uri() . '/admin/js/accordion-slider.js', array('jquery') );
		wp_enqueue_script('cryout_accordion');
		}
// For the WP uploader
    if(function_exists('wp_enqueue_media')) {
         wp_enqueue_media();
      }
      else {
         wp_enqueue_script('media-upload');
         wp_enqueue_script('thickbox');
         wp_enqueue_style('thickbox');
      }
// The js used in the admin
	wp_register_script('cryout-admin-js',get_template_directory_uri() . '/admin/js/admin.js' );
	wp_enqueue_script('cryout-admin-js');
// codemirror css markup
     wp_register_script('cryout-admin-codemirror-js',get_template_directory_uri() . '/admin/js/codemirror.min.js' );
	wp_enqueue_script('cryout-admin-codemirror-js');
}

// The settings sectoions. All the referenced functions are found in admin-functions.php
function parabola_init_fn(){

	register_setting('parabola_settings', 'parabola_settings', 'parabola_settings_validate');

/**************
   sections
**************/

	add_settings_section('layout_section', __('Layout Settings','parabola'), 'cryout_section_layout_fn', __FILE__);
	add_settings_section('header_section', __('Header Settings','parabola'), 'cryout_section_header_fn', __FILE__);
	add_settings_section('presentation_section', __('Presentation Page','parabola'), 'cryout_section_presentation_fn', __FILE__);
	add_settings_section('text_section', __('Text Settings','parabola'), 'cryout_section_text_fn', __FILE__);
	add_settings_section('appereance_section',__('Color Settings','parabola') , 'cryout_section_appereance_fn', __FILE__);
	add_settings_section('graphics_section', __('Graphics Settings','parabola') , 'cryout_section_graphics_fn', __FILE__);
	add_settings_section('post_section', __('Post Information Settings','parabola') , 'cryout_section_post_fn', __FILE__);
	add_settings_section('excerpt_section', __('Post Excerpt Settings','parabola') , 'cryout_section_excerpt_fn', __FILE__);
	add_settings_section('featured_section', __('Featured Image Settings','parabola') , 'cryout_section_featured_fn', __FILE__);
	add_settings_section('socials_section', __('Social Media Settings','parabola') , 'cryout_section_social_fn', __FILE__);
	add_settings_section('misc_section', __('Miscellaneous Settings','parabola') , 'cryout_section_misc_fn', __FILE__);
	
/*** layout ***/
	add_settings_field('parabola_side', __('Main Layout','parabola') , 'cryout_setting_side_fn', __FILE__, 'layout_section');
	add_settings_field('parabola_sidewidth', __('Content / Sidebar Width','parabola') , 'cryout_setting_sidewidth_fn', __FILE__, 'layout_section');
	add_settings_field('parabola_mobile', __('Responsiveness','parabola') , 'cryout_setting_mobile_fn', __FILE__, 'layout_section');
	
/*** presentation ***/
	add_settings_field('parabola_frontpage', __('Enable Presentation Page','parabola') , 'cryout_setting_frontpage_fn', __FILE__, 'presentation_section');
	add_settings_field('parabola_frontposts', __('Show Posts on Presentation Page','parabola') , 'cryout_setting_frontposts_fn', __FILE__, 'presentation_section');
	add_settings_field('parabola_frontslider', __('Slider Settings','parabola') , 'cryout_setting_frontslider_fn', __FILE__, 'presentation_section');
	add_settings_field('parabola_frontslider2', __('Slides','parabola') , 'cryout_setting_frontslider2_fn', __FILE__, 'presentation_section');
	add_settings_field('parabola_frontcolumns', __('Presentation Page Columns','parabola') , 'cryout_setting_frontcolumns_fn', __FILE__, 'presentation_section');
	add_settings_field('parabola_fronttext', __('Extras','parabola') , 'cryout_setting_fronttext_fn', __FILE__, 'presentation_section');
	
/*** header ***/
	add_settings_field('parabola_hheight', __('Header Height','parabola') , 'cryout_setting_hheight_fn', __FILE__, 'header_section');
	add_settings_field('parabola_himage', __('Header Image','parabola') , 'cryout_setting_himage_fn', __FILE__, 'header_section');
	add_settings_field('parabola_siteheader', __('Site Header','parabola') , 'cryout_setting_siteheader_fn', __FILE__, 'header_section');
	add_settings_field('parabola_logoupload', __('Custom Logo Upload','parabola') , 'cryout_setting_logoupload_fn', __FILE__, 'header_section');
	add_settings_field('parabola_headermargin', __('Header Content Spacing','parabola') , 'cryout_setting_headermargin_fn', __FILE__, 'header_section');
	add_settings_field('parabola_favicon', __('FavIcon Upload','parabola') , 'cryout_setting_favicon_fn', __FILE__, 'header_section');
	
/*** text ***/
	add_settings_field('parabola_fontfamily', __('General Font','parabola') , 'cryout_setting_fontfamily_fn', __FILE__, 'text_section');
	add_settings_field('parabola_fonttitle', __('Post Title Font ','parabola') , 'cryout_setting_fonttitle_fn', __FILE__, 'text_section');
	add_settings_field('parabola_fontside', __('Widget Title Font','parabola') , 'cryout_setting_fontside_fn', __FILE__, 'text_section');
	add_settings_field('parabola_sitetitlefont', __('Site Title Font','parabola') , 'cryout_setting_sitetitlefont_fn', __FILE__, 'text_section');
	add_settings_field('parabola_menufont', __('Main Menu Font','parabola') , 'cryout_setting_menufont_fn', __FILE__, 'text_section');
	add_settings_field('parabola_fontheadings', __('Headings Font','parabola') , 'cryout_setting_fontheadings_fn', __FILE__, 'text_section');
	add_settings_field('parabola_textalign', __('Force Text Align','parabola') , 'cryout_setting_textalign_fn', __FILE__, 'text_section');
	add_settings_field('parabola_paragraphspace', __('Paragraph spacing','parabola') , 'cryout_setting_paragraphspace_fn', __FILE__, 'text_section');
	add_settings_field('parabola_parindent', __('Paragraph Indent','parabola') , 'cryout_setting_parindent_fn', __FILE__, 'text_section');
	add_settings_field('parabola_headingsindent', __('Headings Indent','parabola') , 'cryout_setting_headingsindent_fn', __FILE__, 'text_section');
	add_settings_field('parabola_lineheight', __('Line Height','parabola') , 'cryout_setting_lineheight_fn', __FILE__, 'text_section');
	add_settings_field('parabola_wordspace', __('Word Spacing','parabola') , 'cryout_setting_wordspace_fn', __FILE__, 'text_section');
	add_settings_field('parabola_letterspace', __('Letter Spacing','parabola') , 'cryout_setting_letterspace_fn', __FILE__, 'text_section');
	add_settings_field('parabola_letterspace', __('Uppercase Text','parabola') , 'cryout_setting_uppercasetext_fn', __FILE__, 'text_section');

/*** appereance ***/

    add_settings_field('parabola_sitebackground', __('Background Image','parabola') , 'cryout_setting_sitebackground_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_generalcolors', __('General','parabola') , 'cryout_setting_generalcolors_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_accentcolors', __('Accents','parabola') , 'cryout_setting_accentcolors_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_titlecolors', __('Site Title','parabola') , 'cryout_setting_titlecolors_fn', __FILE__, 'appereance_section');

	add_settings_field('parabola_menucolors', __('Main Menu','parabola') , 'cryout_setting_menucolors_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_topmenucolors', __('Top Menu','parabola') , 'cryout_setting_topmenucolors_fn', __FILE__, 'appereance_section');

	add_settings_field('parabola_contentcolors', __('Content','parabola') , 'cryout_setting_contentcolors_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_frontpagecolors', __('Presentation Page','parabola') , 'cryout_setting_frontpagecolors_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_sidecolors', __('Sidebar Widgets','parabola') , 'cryout_setting_sidecolors_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_widgetcolors', __('Footer Widgets','parabola') , 'cryout_setting_widgetcolors_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_linkcolors', __('Links','parabola') , 'cryout_setting_linkcolors_fn', __FILE__, 'appereance_section');

	add_settings_field('parabola_caption', __('Caption Border','parabola') , 'cryout_setting_caption_fn', __FILE__, 'appereance_section');
	add_settings_field('parabola_metaback', __('Meta Area Background','parabola') , 'cryout_setting_metaback_fn', __FILE__, 'appereance_section');

/*** graphics ***/
	
	add_settings_field('parabola_breadcrumbs', __('Breadcrumbs','parabola') , 'cryout_setting_breadcrumbs_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_pagination', __('Pagination','parabola') , 'cryout_setting_pagination_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_menualign', __('Menu Alignment','parabola') , 'cryout_setting_menualign_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_triangles', __('Triangle Accents','parabola') , 'cryout_setting_triangles_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_image', __('Post Images Border','parabola') , 'cryout_setting_image_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_contentlist', __('Content List Bullets','parabola') , 'cryout_setting_contentlist_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_pagetitle', __('Page Titles','parabola') , 'cryout_setting_pagetitle_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_categetitle', __('Category Titles','parabola') , 'cryout_setting_categtitle_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_tables', __('Hide Tables','parabola') , 'cryout_setting_tables_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_backtop', __('Back to Top button','parabola') , 'cryout_setting_backtop_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_comtext', __('Text Under Comments','parabola') , 'cryout_setting_comtext_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_comclosed', __('Comments are closed text','parabola') , 'cryout_setting_comclosed_fn', __FILE__, 'graphics_section');
	add_settings_field('parabola_comoff', __('Comments off','parabola') , 'cryout_setting_comoff_fn', __FILE__, 'graphics_section');
	
/*** post metas***/
	add_settings_field('parabola_postcomlink', __('Post Comments Link','parabola') , 'cryout_setting_postcomlink_fn', __FILE__, 'post_section');
	add_settings_field('parabola_postdatetime', __('Post Date/Time','parabola') , 'cryout_setting_postdatetime_fn', __FILE__, 'post_section');
	add_settings_field('parabola_postauthor', __('Post Author','parabola') , 'cryout_setting_postauthor_fn', __FILE__, 'post_section');
	add_settings_field('parabola_postcateg', __('Post Category','parabola') , 'cryout_setting_postcateg_fn', __FILE__, 'post_section');
	add_settings_field('parabola_postmetas', __('Meta Bar','parabola') , 'cryout_setting_postmetas_fn', __FILE__, 'post_section');
	add_settings_field('parabola_posttag', __('Post Tags','parabola') , 'cryout_setting_posttag_fn', __FILE__, 'post_section');
	add_settings_field('parabola_postbook', __('Post Permalink','parabola') , 'cryout_setting_postbook_fn', __FILE__, 'post_section');
	
/*** post exceprts***/
	add_settings_field('parabola_excerpthome', __('Home Page','parabola') , 'cryout_setting_excerpthome_fn', __FILE__, 'excerpt_section');
	add_settings_field('parabola_excerptsticky', __('Sticky Posts','parabola') , 'cryout_setting_excerptsticky_fn', __FILE__, 'excerpt_section');
	add_settings_field('parabola_excerptarchive', __('Archive and Category Pages','parabola') , 'cryout_setting_excerptarchive_fn', __FILE__, 'excerpt_section');
	add_settings_field('parabola_excerptwords', __('Number of Words for Post Excerpts ','parabola') , 'cryout_setting_excerptwords_fn', __FILE__, 'excerpt_section');
	add_settings_field('parabola_magazinelayout', __('Magazine Layout','parabola') , 'cryout_setting_magazinelayout_fn', __FILE__, 'excerpt_section');
	add_settings_field('parabola_excerptdots', __('Excerpt suffix','parabola') , 'cryout_setting_excerptdots_fn', __FILE__, 'excerpt_section');
	add_settings_field('parabola_excerptcont', __('Continue reading link text ','parabola') , 'cryout_setting_excerptcont_fn', __FILE__, 'excerpt_section');
	add_settings_field('parabola_excerpttags', __('HTML tags in Excerpts','parabola') , 'cryout_setting_excerpttags_fn', __FILE__, 'excerpt_section');
	
/*** featured ***/
	add_settings_field('parabola_fpost', __('Featured Images as POST Thumbnails ','parabola') , 'cryout_setting_fpost_fn', __FILE__, 'featured_section');
	add_settings_field('parabola_fauto', __('Auto Select Images From Posts ','parabola') , 'cryout_setting_fauto_fn', __FILE__, 'featured_section');
	add_settings_field('parabola_falign', __('Thumbnails Alignment ','parabola') , 'cryout_setting_falign_fn', __FILE__, 'featured_section');
	add_settings_field('parabola_fsize', __('Thumbnails Size ','parabola') , 'cryout_setting_fsize_fn', __FILE__, 'featured_section');
	add_settings_field('parabola_fheader', __('Featured Images as HEADER Images ','parabola') , 'cryout_setting_fheader_fn', __FILE__, 'featured_section');
	
/*** socials ***/
	add_settings_field('parabola_socials1', __('Link nr. 1','parabola') , 'cryout_setting_socials1_fn', __FILE__, 'socials_section');
	add_settings_field('parabola_socials2', __('Link nr. 2','parabola') , 'cryout_setting_socials2_fn', __FILE__, 'socials_section');
	add_settings_field('parabola_socials3', __('Link nr. 3','parabola') , 'cryout_setting_socials3_fn', __FILE__, 'socials_section');
	add_settings_field('parabola_socials4', __('Link nr. 4','parabola') , 'cryout_setting_socials4_fn', __FILE__, 'socials_section');
	add_settings_field('parabola_socials5', __('Link nr. 5','parabola') , 'cryout_setting_socials5_fn', __FILE__, 'socials_section');
	add_settings_field('parabola_socialshow', __('Socials display','parabola') , 'cryout_setting_socialsdisplay_fn', __FILE__, 'socials_section');
	
/*** misc ***/
	add_settings_field('parabola_iecompat', __('Internet Explorer Compatibility Tag','parabola') , 'cryout_setting_iecompat_fn', __FILE__, 'misc_section');
	add_settings_field('parabola_copyright', __('Custom Footer Text','parabola') , 'cryout_setting_copyright_fn', __FILE__, 'misc_section');
	add_settings_field('parabola_customcss', __('Custom CSS','parabola') , 'cryout_setting_customcss_fn', __FILE__, 'misc_section');
	add_settings_field('parabola_customjs', __('Custom JavaScript','parabola') , 'cryout_setting_customjs_fn', __FILE__, 'misc_section');

}

 // Display the admin options page
function parabola_page_fn() {
 // Load the import form page if the import button has been pressed
	if (isset($_POST['parabola_import'])) {
		parabola_import_form();
		return;
	}
 // Load the import form  page after upload button has been pressed
	if (isset($_POST['parabola_import_confirmed'])) {
		parabola_import_file();
		return;
	}

 // Load the presets  page after presets button has been pressed
	if (isset($_POST['parabola_presets'])) {
		parabola_init_fn();
		parabola_presets();
		return;
	}


 if (!current_user_can('edit_theme_options'))  {
    wp_die( __('Sorry, but you do not have sufficient permissions to access this page.','parabola') );
  }?>


<div class="wrap"><!-- Admin wrap page -->

<div id="lefty"><!-- Left side of page - the options area -->
<div>
	<div id="admin_header"><img src="<?php echo get_template_directory_uri() . '/admin/images/parabola-logo.png' ?>" /> </div>
	<div id="admin_links">
		<a target="_blank" href="http://www.cryoutcreations.eu/parabola">Parabola Homepage</a>
		<a target="_blank" href="http://www.cryoutcreations.eu/forum">Support</a>
		<a target="_blank" href="http://www.cryoutcreations.eu">Cryout Creations</a>
	</div>
	<div style="clear: both;"></div>
</div>
<?php 
if ( isset( $_GET['settings-updated'] ) ) {
    echo "<div class='updated fade' style='clear:left;'><p>";
	echo _e('Parabola settings updated successfully.','parabola');
	echo "</p></div>";
} 
?>
<div id="jsAlert" class=""><b>Checking jQuery functionality...</b><br/><em>If this message remains visible after the page has loaded then there is a problem with your WordPress jQuery library. This can have several causes, including incompatible plugins.
The Parabola Settings page cannot function without jQuery. </em></div>

	<div id="main-options">
		<form name="parabola_form" action="options.php" method="post" enctype="multipart/form-data">
			<div id="accordion">
				<?php settings_fields('parabola_settings'); ?>
				<?php do_settings_sections(__FILE__); ?>
			</div>
			<div id="submitDiv">
			    <br>
				<input class="button" name="parabola_settings[parabola_submit]" type="submit" style="float:right;"   value="<?php _e('Save Changes','parabola'); ?>" />
				<input class="button" name="parabola_settings[parabola_defaults]" id="parabola_defaults" type="submit" style="float:left;" value="<?php _e('Reset to Defaults','parabola'); ?>" />
				</div>
		</form>
		<?php   $parabola_theme_data = get_transient( 'parabola_theme_info');  ?>
		<span id="version">
		Parabola v<?php echo PARABOLA_VERSION; ?> by <a href="http://www.cryoutcreations.eu" target="_blank">Cryout Creations</a>
		</span>
	</div><!-- main-options -->
</div><!--lefty -->


<div id="righty" ><!-- Right side of page - Coffee, RSS tips and others -->
	<div class="postbox donate">
		<h3 class="hndle"> Coffee Break </h3>
		<div class="inside"><?php echo "<p>Great power comes with great responsibility. We have complete faith that you will only use Parabola for good. You will not use it to destroy worlds, but to create them. You will not use it to control minds, but to expand them. You will not use it to enslave your peers but you will introduce them to Parabola so that they may one day become your equals. We *know* our theme won't be crippled in your hands, you'll nourish it and use it to its full potential.</p>
		<p>But if you feel the dark forces are somehow taking over, if you sense Parabola is not serving its true, original purpose... buy us a coffee and we'll stay up all night to restore the balance. We know the dark forces very well ;)</p> "; ?>
			<div style="display:block;float:none;margin:0 auto;text-align:center;">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="<?php echo get_template_directory_uri() . '/admin/images/coffee.png' ?>" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
			</div>
		</div><!-- inside -->
	</div><!-- donate -->

    <div class="postbox export non-essential-option" style="overflow:hidden;">
        <div class="head-wrap">
            <div title="Click to toggle" class="handlediv"><br /></div>
           	<h3 class="hndle"><?php _e( 'Import/Export Settings', 'parabola' ); ?></h3>
        </div><!-- head-wrap -->
        <div class="panel-wrap inside">
				<form action="" method="post">
                	<?php wp_nonce_field('parabola-export', 'parabola-export'); ?>
                    <input type="hidden" name="parabola_export" value="true" />
                    <input type="submit" class="button" value="<?php _e('Export Theme options', 'parabola'); ?>" />
					<p style="display:block;float:left;clear:left;margin-top:0;"><?php _e("It's that easy: a mouse click away - the ability to export your Parabola settings and save them on your computer. Feeling safer? You should!","parabola"); ?></p>
                </form>
				<br />
                <form action="" method="post">
                    <input type="hidden" name="parabola_import" value="true" />
                    <input type="submit" class="button" value="<?php _e('Import Theme options', 'parabola'); ?>" />
					<p style="display:block;float:left;clear:left;margin-top:0;"><?php _e("Without the import, the export would just be a fool's exercise. Make sure you have the exported file ready and see you after the mouse click.","parabola"); ?></p>
                </form>
				<br />
				<form action="" method="post">
                    <input type="hidden" name="parabola_presets" value="true" />
                    <input type="submit" class="button" id="presets_button" value="<?php _e('Color Schemes', 'parabola'); ?>" />
					<p style="display:block;float:left;clear:left;margin-top:0;"><?php _e("A collection of preset color schemes to use as the starting point for your site. Just load one up and see your blog in a different light.","parabola"); ?></p>
                </form> 

		</div><!-- inside -->
	</div><!-- export -->

    <div class="postbox news" >
            <div>
        		<h3 class="hndle"><?php _e( 'Parabola Latest News', 'parabola' ); ?></h3>
            </div>
            <div class="panel-wrap inside" style="height:200px;overflow:auto;">
                <?php
				$parabola_news = fetch_feed( array( 'http://www.cryoutcreations.eu/cat/parabola/feed/') );
				if ( ! is_wp_error( $parabola_news ) ) {
					$maxitems = $parabola_news->get_item_quantity( 10 );
					$news_items = $parabola_news->get_items( 0, $maxitems );
				}
				?>
                <ul class="news-list">
                	<?php if ( $maxitems == 0 ) : echo '<li>' . __( 'No news items.', 'parabola' ) . '</li>'; else :
                	foreach( $news_items as $news_item ) : ?>
                    	<li>
                        	<a class="news-header" href='<?php echo esc_url( $news_item->get_permalink() ); ?>'><?php echo esc_html( $news_item->get_title() ); ?></a><br />
                   <span class="news-item-date"><?php _e('Posted on','parabola'); echo $news_item->get_date(' j F Y, H:i'); ?></span><br />
                            <?php echo parabola_truncate_words(strip_tags( $news_item->get_description() ),40,'...') ; ?>
					<br><a class="news-read" href='<?php echo esc_url( $news_item->get_permalink() ); ?>'>Read more &raquo;</a><br />
                        </li>
                    <?php endforeach; endif; ?>
                </ul>
            </div><!-- inside -->
    </div><!-- news -->


</div><!--  righty -->
</div><!--  wrap -->

<script type="text/javascript">
var reset_confirmation = '<?php echo esc_html(__('Reset Parabola Settings to Defaults?','parabola')); ?>'; 

function startfarb(a,b) {
	jQuery(b).css('display','none');
	jQuery(b).farbtastic(a).addtitle({id: a});

	jQuery(a).click(function() {
			if(jQuery(b).css('display') == 'none')	{
                                        			jQuery(b).parents('div:eq(0)').addClass('ui-accordion-content-overflow');
                                                       jQuery(b).css('display','inline-block').hide().show(150);
                                                       }
	});

	jQuery(document).mousedown( function() {
			jQuery(b).hide(300, function(){ jQuery(b).parents('div:eq(0)').removeClass('ui-accordion-content-overflow'); });
			// todo: find a better way to remove class after the fade on IEs
	});
}

function tooltip_terain() {
jQuery('#accordion small').parent('div').append('<a class="tooltip"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-tooltip.png" /></a>').
	each(function() {
	//jQuery(this).children('a.tooltip').attr('title',jQuery(this).children('small').html() );
	var tooltip_info = jQuery(this).children('small').html();
	jQuery(this).children('.tooltip').tooltip({content : tooltip_info});
     jQuery(this).children('.tooltip').tooltip( "option", "items", "a" );
	//jQuery(this).children('.tooltip').tooltip( "option", "show", "false");
	jQuery(this).children('.tooltip').tooltip( "option", "hide", "false");
	jQuery(this).children('small').remove();
	if (!jQuery(this).hasClass('slmini') && !jQuery(this).hasClass('slidercontent') && !jQuery(this).hasClass('slideDivs')) jQuery(this).addClass('tooltip_div');
	});
}

function coloursel(el){
	var id = "#"+jQuery(el).attr('id');
	jQuery(id+"2").hide();
	var bgcolor = jQuery(id).val();
	if (bgcolor <= "#666666") { jQuery(id).css('color','#ffffff'); } else { jQuery(id).css('color','#000000'); };
	jQuery(id).css('background-color',jQuery(id).val());
}

function vercomp(ver, req) {
    var v = ver.split('.');
    var q = req.split('.');
    for (var i = 0; i < v.length; ++i) {
        if (q.length == i) { return true; } // v is bigger
        if (parseInt(v[i]) == parseInt(q[i])) { continue; } // nothing to do here, move along
        else if (parseInt(v[i]) > parseInt(q[i])) { return true; } // v is bigger
        else { return false; } // q is bigger
    }
    if (v.length != q.length) { return false; } // q is bigger
    return true; // v = q;
}

// farbtastic title addon function
(function($){
        $.fn.extend({
            addtitle: function(options) {
                var defaults = {
                    id: ''
                }
                var options = $.extend(defaults, options);
            return this.each(function() {
                    var o = options;
					var title = jQuery(o.id).attr('title');
                    if (title===undefined) { } else { jQuery(o.id+'2').children('.farbtastic').append('<span class="mytitle">'+title+'</span>'); }
            });
        }
        });
})(jQuery);


jQuery(document).ready(function(){
	//var _jQueryVer = parseFloat('.'+jQuery().jquery.replace(/\./g, ''));  // jQuery version as float, eg: 0.183
	//var _jQueryUIVer = parseFloat('.'+jQuery.ui.version.replace(/\./g, '')); // jQuery UI version as float, eg: 0.192
	//if (_jQueryUIVer >= 0.190) {
	if (vercomp(jQuery.ui.version,"1.9.0")) {
		tooltip_terain();
		jQuery('.colorthingy').each(function(){
			id = "#"+jQuery(this).attr('id');
			startfarb(id,id+'2');
		});
	} else {
		jQuery("#main-options").addClass('oldwp');
		setTimeout(function(){jQuery('#parabola_slideType').trigger('click')},1000);
		jQuery('.colorthingy').each(function(){
			id = "#"+jQuery(this).attr('id');
			jQuery(this).on('keyup',function(){coloursel(this)});
			coloursel(this);
		});
		// warn about the old partially unsupported version
		jQuery("#jsAlert").after("<div class='updated fade' style='clear:left; font-size: 16px;'><p>Parabola has detected you are running an older version of Wordpress (jQuery) and will be running in compatibility mode. Some features may not work correctly. Consider updating your Wordpress to the latest version.</p></div>");
	}
});
jQuery('#jsAlert').hide();
</script>

<?php } // parabola_page_fn()
?>