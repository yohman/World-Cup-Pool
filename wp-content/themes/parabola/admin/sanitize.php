<?php

/*
 *
 * Settings arrays
 *
 */

/* Font family arrays */
$parabola_colorschemes_array = array(
// color scheme presets are defined via schemes.php
);

$fonts = array(

	'Theme Fonts' => array(
					 "Open Sans",
					 "Open Sans Light",
					 "Bebas Neue",
					 "Oswald",
					 "Oswald Light",
					 "Oswald Stencil",
					 "Yanone Kaffeesatz Regular",
					 "Yanone Kaffeesatz Light",
					 "SquareFont",
					 "PROPAGANDA"),

	'Sans-Serif' => array("Segoe UI, Arial, sans-serif",
					 "Verdana, Geneva, sans-serif " ,
					 "Geneva, sans-serif ",
					 "Helvetica Neue, Arial, Helvetica, sans-serif",
					 "Helvetica, sans-serif" ,
					 "Century Gothic, AppleGothic, sans-serif",
				      "Futura, Century Gothic, AppleGothic, sans-serif",
					 "Calibri, Arian, sans-serif",
				      "Myriad Pro, Myriad,Arial, sans-serif",
					 "Trebuchet MS, Arial, Helvetica, sans-serif" ,
					 "Gill Sans, Calibri, Trebuchet MS, sans-serif",
					 "Impact, Haettenschweiler, Arial Narrow Bold, sans-serif ",
					 "Tahoma, Geneva, sans-serif" ,
					 "Arial, Helvetica, sans-serif" ,
					 "Arial Black, Gadget, sans-serif",
					 "Lucida Sans Unicode, Lucida Grande, sans-serif "),

	'Serif' => array("Georgia, Times New Roman, Times, serif" ,
					  "Times New Roman, Times, serif",
					  "Cambria, Georgia, Times, Times New Roman, serif",
					  "Palatino Linotype, Book Antiqua, Palatino, serif",
					  "Book Antiqua, Palatino, serif",
					  "Palatino, serif",
				       "Baskerville, Times New Roman, Times, serif",
 					  "Bodoni MT, serif",
					  "Copperplate Light, Copperplate Gothic Light, serif",
					  "Garamond, Times New Roman, Times, serif"),

	'MonoSpace' => array( "Courier New, Courier, monospace" ,
					  "Lucida Console, Monaco, monospace",
					  "Consolas, Lucida Console, Monaco, monospace",
					  "Monaco, monospace"),

	'Cursive' => array(  "Lucida Casual, Comic Sans MS , cursive ",
				      "Brush Script MT,Phyllis,Lucida Handwriting,cursive",
					 "Phyllis,Lucida Handwriting,cursive",
					 "Lucida Handwriting,cursive",
					 "Comic Sans MS, cursive")
); // fonts


/* Social media links */

$socialNetworks = array (
		"AboutMe", "AIM", "Amazon", "Delicious", "DeviantArt", 
		"Digg", "Dribbble", "Etsy", "Facebook", "Flickr",
		"FriendFeed", "GoodReads", "GooglePlus", "IMDb", "Instagram",
		"LastFM", "LinkedIn", "Mail", "MySpace", "Newsvine", 
		"Picasa", "Pinterest", "Reddit", "RSS", "ShareThis",  
		"Skype", "Steam", "SoundCloud", "StumbleUpon", "Technorati", 
		"Tumblr",  "Twitch", "Twitter", "Vimeo", "VK",
		"WordPress", "Yahoo", "Yelp", "YouTube", "Xing" );

if (!function_exists ('parabola_options_validate') ) :
/*
 *
 * Validate user data
 *
 */
function parabola_settings_validate($input) {
global $parabola_defaults;
global $parabolas;
global $parabola_colorschemes_array ;

$colorSchemes = ( ! empty( $input['parabola_schemessubmit']) ? true : false );
if ($colorSchemes) : $input = array_merge($parabolas,json_decode("{".$parabola_colorschemes_array[$input['parabola_colorschemes']]."}",true));
else :
/*** 1 ***/
	if(isset($input['parabola_sidewidth']) && is_numeric($input['parabola_sidewidth']) && $input['parabola_sidewidth']>=500 && $input['parabola_sidewidth'] <=1760) {} else {$input['parabola_sidewidth']=$parabola_defaults['parabola_sidewidth']; }
	if(isset($input['parabola_sidebar']) && is_numeric($input['parabola_sidebar']) && $input['parabola_sidebar']>=220 && $input['parabola_sidebar'] <=800) {} else {$input['parabola_sidebar']=$parabola_defaults['parabola_sidebar']; }

	$input['parabola_hheight'] =  intval(wp_kses_data($input['parabola_hheight']));
	$input['parabola_copyright'] = trim(wp_kses_post($input['parabola_copyright']));

	$input["parabola_backcolorheader"] = trim(wp_kses_data($input['parabola_backcolorheader']));
	$input["parabola_backcolormain"] = trim(wp_kses_data($input['parabola_backcolormain']));
	$input["parabola_backcolorfooterw"] = trim(wp_kses_data($input['parabola_backcolorfooterw']));
	$input["parabola_backcolorfooter"] = trim(wp_kses_data($input['parabola_backcolorfooter']));

	$input["parabola_contentcolortxt"] = trim(wp_kses_data($input['parabola_contentcolortxt']));
	$input["parabola_contentcolortxtlight"] = trim(wp_kses_data($input['parabola_contentcolortxtlight']));
	$input["parabola_footercolortxt"] = trim(wp_kses_data($input['parabola_footercolortxt']));

	$input["parabola_titlecolor"] = trim(wp_kses_data($input['parabola_titlecolor']));
	$input["parabola_descriptioncolor"] = trim(wp_kses_data($input['parabola_descriptioncolor']));
	$input["parabola_descriptionbg"] = trim(wp_kses_data($input['parabola_descriptionbg']));

	$input["parabola_menucolorbgdefault"] = trim(wp_kses_data($input['parabola_menucolorbgdefault']));
	$input["parabola_menucolorbghover"] = trim(wp_kses_data($input['parabola_menucolorbghover']));
	$input["parabola_menucolorbgactive"] = trim(wp_kses_data($input['parabola_menucolorbgactive']));
	$input["parabola_menucolorshadow"] = trim(wp_kses_data($input['parabola_menucolorshadow']));
	$input["parabola_menucolortxtdefault"] = trim(wp_kses_data($input['parabola_menucolortxtdefault']));
	$input["parabola_menucolortxthover"] = trim(wp_kses_data($input['parabola_menucolortxthover']));
	$input["parabola_menucolortxtactive"] = trim(wp_kses_data($input['parabola_menucolortxtactive']));

	$input["parabola_topmenucolortxt"] = trim(wp_kses_data($input['parabola_topmenucolortxt']));
	$input["parabola_topmenucolortxthover"] = trim(wp_kses_data($input['parabola_topmenucolortxthover']));
	$input["parabola_topmenucolorbg"] = trim(wp_kses_data($input['parabola_topmenucolorbg']));
	$input["parabola_topmenucolorbghover"] = trim(wp_kses_data($input['parabola_topmenucolorbghover']));

	$input["parabola_contentcolorbg"] = trim(wp_kses_data($input['parabola_contentcolorbg']));
	$input["parabola_contentcolortxttitle"] = trim(wp_kses_data($input['parabola_contentcolortxttitle']));
	$input["parabola_contentcolortxttitlehover"] = trim(wp_kses_data($input['parabola_contentcolortxttitlehover']));
	$input["parabola_contentcolortxtheadings"] = trim(wp_kses_data($input['parabola_contentcolortxtheadings']));

	$input["parabola_sidebg"] = trim(wp_kses_data($input['parabola_sidebg']));
	$input["parabola_sidetxt"] = trim(wp_kses_data($input['parabola_sidetxt']));
	$input["parabola_sidetitlebg"] = trim(wp_kses_data($input['parabola_sidetitlebg']));
	$input["parabola_sidetitletxt"] = trim(wp_kses_data($input['parabola_sidetitletxt']));

	$input["parabola_widgetbg"] = trim(wp_kses_data($input['parabola_widgetbg']));
	$input["parabola_widgettxt"] = trim(wp_kses_data($input['parabola_widgettxt']));
	$input["parabola_widgettitlebg"] = trim(wp_kses_data($input['parabola_widgettitlebg']));
	$input["parabola_widgettitletxt"] = trim(wp_kses_data($input['parabola_widgettitletxt']));

	$input["parabola_linkcolortext"] = trim(wp_kses_data($input['parabola_linkcolortext']));
	$input["parabola_linkcolorhover"] = trim(wp_kses_data($input['parabola_linkcolorhover']));
	$input["parabola_linkcolorside"] = trim(wp_kses_data($input['parabola_linkcolorside']));
	$input["parabola_linkcolorsidehover"] = trim(wp_kses_data($input['parabola_linkcolorsidehover']));
	$input["parabola_linkcolorwooter"] = trim(wp_kses_data($input['parabola_linkcolorwooter']));
	$input["parabola_linkcolorwooterhover"] = trim(wp_kses_data($input['parabola_linkcolorwooterhover']));
	$input["parabola_linkcolorfooter"] = trim(wp_kses_data($input['parabola_linkcolorfooter']));
	$input["parabola_linkcolorfooterhover"] = trim(wp_kses_data($input['parabola_linkcolorfooterhover']));

	$input["parabola_accentcolora"] = trim(wp_kses_data($input['parabola_accentcolora']));
	$input["parabola_accentcolorb"] = trim(wp_kses_data($input['parabola_accentcolorb']));
	$input["parabola_accentcolorc"] = trim(wp_kses_data($input['parabola_accentcolorc']));
	$input["parabola_accentcolord"] = trim(wp_kses_data($input['parabola_accentcolord']));
	$input["parabola_accentcolore"] = trim(wp_kses_data($input['parabola_accentcolore']));
	
	$input['parabola_frontpostscount'] =  intval(wp_kses_data($input['parabola_frontpostscount']));

	$input['parabola_fronttitlecolor'] =  wp_kses_data($input['parabola_fronttitlecolor']);
	$input['parabola_fpsliderbordercolor'] =  wp_kses_data($input['parabola_fpsliderbordercolor']);
	$input['parabola_fpslidercaptioncolor'] =  wp_kses_data($input['parabola_fpslidercaptioncolor']);
	$input['parabola_fpslidercaptionbg'] =  wp_kses_data($input['parabola_fpslidercaptionbg']);

	$input['parabola_excerptwords'] =  intval(wp_kses_data($input['parabola_excerptwords']));
	$input['parabola_excerptdots'] =  wp_kses_data($input['parabola_excerptdots']);
	$input['parabola_excerptcont'] =  wp_kses_data($input['parabola_excerptcont']);

	$input['parabola_fwidth'] =  intval(wp_kses_data($input['parabola_fwidth']));
	$input['parabola_fheight'] =  intval(wp_kses_data($input['parabola_fheight']));

/*** 2 ***/

	$cryout_special_terms = array('mailto:','callto://');
	$cryout_special_keys = array('Mail', 'Skype');
	for ($i=1;$i<10;$i+=2) {
		if (!isset($input['parabola_social_target'.$i])) {$input['parabola_social_target'.$i] = "0";}
		$input['parabola_social_title'.$i] = wp_kses_data(trim($input['parabola_social_title'.$i]));
		$j=$i+1;
		if (in_array($input['parabola_social'.$i],$cryout_special_keys)) :
			$input['parabola_social'.$j]	= wp_kses_data(str_replace($cryout_special_terms,'',$input['parabola_social'.$j]));
			if ($input['parabola_social'.$i]=='Mail') {$input['parabola_social'.$j]='mailto:'.$input['parabola_social'.$j];};
			if ($input['parabola_social'.$i]=='Skype') {$input['parabola_social'.$j]='callto://'.$input['parabola_social'.$j];};
		else :
			$input['parabola_social'.$j] = esc_url_raw($input['parabola_social'.$j]);
		endif;
	}
	for ($i=0;$i<=5;$i++) {
		if (!isset($input['parabola_socialsdisplay'.$i])) {$input['parabola_socialsdisplay'.$i] = "0";}
		}


	$input['parabola_favicon'] =  esc_url_raw($input['parabola_favicon']);
	$input['parabola_logoupload'] =  esc_url_raw($input['parabola_logoupload']);
	$input['parabola_headermargintop'] =  intval(wp_kses_data($input['parabola_headermargintop']));
	$input['parabola_headermarginleft'] =  intval(wp_kses_data($input['parabola_headermarginleft']));

	$input['parabola_customcss'] =  wp_kses_post(trim($input['parabola_customcss']));
	$input['parabola_customjs'] =  wp_kses_post(trim($input['parabola_customjs']));

	$input['parabola_googlefont'] = 	trim(wp_kses_data($input['parabola_googlefont']));
	$input['parabola_googlefonttitle'] = 	trim(wp_kses_data($input['parabola_googlefonttitle']));
	$input['parabola_googlefontside'] = 	trim(wp_kses_data($input['parabola_googlefontside']));
	$input['parabola_headingsgooglefont'] = 	trim(wp_kses_data($input['parabola_headingsgooglefont']));
	$input['parabola_sitetitlegooglefont'] = 	trim(wp_kses_data($input['parabola_sitetitlegooglefont']));
	$input['parabola_menugooglefont'] = 	trim(wp_kses_data($input['parabola_menugooglefont']));

	$input['parabola_slideNumber'] =  intval(wp_kses_data($input['parabola_slideNumber']));
	$input['parabola_slideSpecific'] = wp_kses_data($input['parabola_slideSpecific']);

	$input['parabola_fpsliderwidth'] =  intval(wp_kses_data($input['parabola_fpsliderwidth']));
	$input['parabola_fpsliderheight'] = intval(wp_kses_data($input['parabola_fpsliderheight']));
	
/** 3 ***/
	$input['parabola_sliderimg1'] =  wp_kses_data($input['parabola_sliderimg1']);
	$input['parabola_slidertitle1'] =  wp_kses_data($input['parabola_slidertitle1']);
	$input['parabola_slidertext1'] =  wp_kses_post($input['parabola_slidertext1']);
	$input['parabola_sliderlink1'] =  esc_url_raw($input['parabola_sliderlink1']);
	$input['parabola_sliderimg2'] =  wp_kses_data($input['parabola_sliderimg2']);
	$input['parabola_slidertitle2'] =  wp_kses_data($input['parabola_slidertitle2']);
	$input['parabola_slidertext2'] =  wp_kses_post($input['parabola_slidertext2']);
	$input['parabola_sliderlink2'] =  esc_url_raw($input['parabola_sliderlink2']);
	$input['parabola_sliderimg3'] =  wp_kses_data($input['parabola_sliderimg3']);
	$input['parabola_slidertitle3'] =  wp_kses_data($input['parabola_slidertitle3']);
	$input['parabola_slidertext3'] =  wp_kses_post($input['parabola_slidertext3']);
	$input['parabola_sliderlink3'] =  esc_url_raw($input['parabola_sliderlink3']);
	$input['parabola_sliderimg4'] =  wp_kses_data($input['parabola_sliderimg4']);
	$input['parabola_slidertitle4'] =  wp_kses_data($input['parabola_slidertitle4']);
	$input['parabola_slidertext4'] =  wp_kses_post($input['parabola_slidertext4']);
	$input['parabola_sliderlink4'] =  esc_url_raw($input['parabola_sliderlink4']);
	$input['parabola_sliderimg5'] =  wp_kses_data($input['parabola_sliderimg5']);
	$input['parabola_slidertitle5'] =  wp_kses_data($input['parabola_slidertitle5']);
	$input['parabola_slidertext5'] =  wp_kses_post($input['parabola_slidertext5']);
	$input['parabola_sliderlink5'] =  esc_url_raw($input['parabola_sliderlink5']);
	
	$input['parabola_colimageheight'] = intval(wp_kses_data($input['parabola_colimageheight']));
	
/** 4 **/
	$input['parabola_columnimg1'] =  wp_kses_data($input['parabola_columnimg1']);
	$input['parabola_columntitle1'] =  wp_kses_data($input['parabola_columntitle1']);
	$input['parabola_columntext1'] =  wp_kses_post($input['parabola_columntext1']);
	$input['parabola_columnlink1'] =  esc_url_raw($input['parabola_columnlink1']);
	$input['parabola_columnimg2'] =  wp_kses_data($input['parabola_columnimg2']);
	$input['parabola_columntitle2'] =  wp_kses_data($input['parabola_columntitle2']);
	$input['parabola_columntext2'] =  wp_kses_post($input['parabola_columntext2']);
	$input['parabola_columnlink2'] =  esc_url_raw($input['parabola_columnlink2']);
	$input['parabola_columnimg3'] =  wp_kses_data($input['parabola_columnimg3']);
	$input['parabola_columntitle3'] =  wp_kses_data($input['parabola_columntitle3']);
	$input['parabola_columntext3'] =  wp_kses_post($input['parabola_columntext3']);
	$input['parabola_columnlink3'] =  esc_url_raw($input['parabola_columnlink3']);
	$input['parabola_columnimg4'] =  wp_kses_data($input['parabola_columnimg4']);
	$input['parabola_columntitle4'] =  wp_kses_data($input['parabola_columntitle4']);
	$input['parabola_columntext4'] =  wp_kses_post($input['parabola_columntext4']);
	$input['parabola_columnlink4'] =  esc_url_raw($input['parabola_columnlink4']);

	$input['parabola_columnreadmore'] =  wp_kses($input['parabola_columnreadmore'],'');

	$input['parabola_fronttext1'] =  wp_kses_data($input['parabola_fronttext1']);
	$input['parabola_fronttext2'] =  wp_kses_data($input['parabola_fronttext2']);
	$input['parabola_fronttext3'] = trim( wp_kses_post($input['parabola_fronttext3']));
	$input['parabola_fronttext4'] = trim (wp_kses_post($input['parabola_fronttext4']));

	$resetDefault = ( ! empty( $input['parabola_defaults']) ? true : false );


	if ($resetDefault) { $input = $parabola_defaults; }
endif;

	return $input; // return validated input

}

endif;
?>