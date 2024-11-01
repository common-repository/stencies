<?php
/*
Plugin Name: Stencies
Plugin URI: http://stencies.com
Description:  Insert pre-formatted text and design bits into your posts and pages.
Version: 0.58
Author: Hendrik Minnie
Author URI: http://stencies.com/
*/

/*
add_action( 'admin_enqueue_scripts', 'devpress_admin_enqueue_scripts' );


function devpress_admin_enqueue_scripts( $hook_suffix ) {
  // wp_register_script('stencies_img', "/stencies/js/xxxxxxxxxxxxxxxrm_script.js",false,false,false);
   
   wp_enqueue_script(  'my_custom_script', plugins_url('/js/stencies_btn_img.js', __FILE__), array( 'jquery' ), true );
   //plugins_url('/foo.js', __FILE__)
   
   // wp_enqueue_script('stencies_img');


}
*/


//Add Stencies menue in admin section
add_action('admin_menu', 'stencies_admin_actions');

//Add menu item on post and page edit
add_action('submitpost_box', 'stencies_add_box');  
add_action('submitpage_box', 'stencies_add_box');

// add stencies id to document html tag - maybe not the best way but it works
add_filter('language_attributes', 'stencies_id_insert');
function stencies_id_insert($output) {
    return 'id="st_html"';
}

// add stencies body class would prefer to add ID to body but cannot find solid way to do this might add javascript  option to add this on non standard WP thmes
add_filter('body_class', 'my_body_class');
function my_body_class($classes) {
        $classes[] ='st_body';

    return $classes;
}

function stencies_css_feed_template($input) {
    load_template( WP_PLUGIN_DIR    .'/stencies/css_feed.php' );
}

function getstencies_template($input) {
    load_template( WP_PLUGIN_DIR    .'/stencies/stencies_feed.php' );
}


/* create stencies  specific feeds */

function stencies_css_feed_setup() {
    add_feed('stenciescssfeed', 'stencies_css_feed_template'); 		// add the template and css for single posts in editor window
	add_feed('getstencies', 'getstencies_template'); // do the main stencies page as a feed to get access to WP functions
}

add_action('init','stencies_css_feed_setup');


/* end feed setup */


// Add post specific css feed
if ( ! function_exists('tdav_css') ) {
	function tdav_css($wp) {
		global $post;
$thePostID = $post->ID;
		$wp .= ",/?feed=stenciescssfeed&id=$thePostID";
		return jQuerywp;
	}
}
add_filter( 'mce_css', 'tdav_css' );
/* End create stencies  specific feeds */

/**********   Ajax call to insert CSS code of selected stencie  into client style   ******************/

add_action('wp_ajax_stencies_insert_css', 'insert_css_callback');

function insert_css_callback() {
	global $wpdb; // this is how you get access to the database
	$theStyle = trim($_POST['theStyle']); // get the style to be inserted
	$theId = trim($_POST['theId']); // get the id to be inserted
	$stencies_custom_css = get_option("stencies_custom_css"); // get the current stylesheet
	// check if the new style is already in the stylesheet	
	
	// ==============

$start = "/* @@st_$theId@@ */";
$end =  "/* @@st_$theId@@END */";




echo $str;


	// ================
	
	$pos=strpos($stencies_custom_css,$start);
	if  ($pos !== false){
	  //  if it exisit in string replace the string with the new values
	  	$stencies_custom_css = replace_content_inside_delimiters($start, $end, "\r\n".$theStyle."\r\n", $stencies_custom_css);
	}else{
	 // if not append
	 
	 	$stencies_custom_css  .= "\r\n".$start."\r\n".$theStyle."\r\n".$end;
	}
	update_option('stencies_custom_css',$stencies_custom_css);
    echo "style successfylly updated";
	die(); // this is required to return a proper result
}

function replace_content_inside_delimiters($start, $end, $new, $source) {
return preg_replace('#('.preg_quote($start).')(.*)('.preg_quote($end).')#si', '$1'.$new.'$3', $source);
}

/***************** END ajax call *****************************/

/**
 * Returns current plugin version.
 *
 * @return string Plugin version
 */
function plugin_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}


//die($stencies_version);



//Admin
function stencies_menu()
{
	include 'stencies-admin.php';
}

function stencies_admin_actions()
{
    add_options_page("Stencies", "Stencies", 10, "Stencies", "stencies_menu");
}


function stencies_add_box()
{
	$stencies_version=plugin_get_version();
		echo "<div class=\"postbox\" id=\"sptdiv\"><div title=\"Click to toggle\" class=\"handlediv\"></div><h3 class=\"hndle\"><span>Stencies - beta (Version $stencies_version) </span></h3><p><div class=\"inside\" style=\"text-align:center;\"><input type=\"button\" id=\"stencies_btn\" name=\"stencies_btn\" value=\"Insert Stencie\" class=\"button button-highlighted\" onClick=\"
		var ed = tinyMCE.activeEditor;ed.execCommand('mceInsertContent', false, '[!--insertstencies--!]');//alert(tinyMCE.activeEditor.getContent());
		this.form.stencies_val.value=tinyMCE.activeEditor.getContent();
		tinyMCE.activeEditor.undoManager.undo();
		;tb_show('Stencies','/?feed=getstencies&slug=/api/get_recent_posts/&TB_iframe=1&modal=false&width=800&height=800',false); return false;
\" /><br /><span class=\"howto\">
Click to insert a Stencie from <a href=\"http://stencies.com\">Stencies.com</a>.  <a href=\"http://www.stencies.com/wp-login.php?action=register\">Register</a> to create your own stencies.</span></p><textarea style=\"display:none\" id=\"stencies_val\" name=\"stencies_val\">".get_option("stencies_current_content")."</textarea><br /><input type=\"checkbox\" id=\"chk\" onCheck=\"tinyMCE.activeEditor.dom.addClass('tinymce', 'myclass');\" onUnCheck=\"tinyMCE.activeEditor.dom.removeClass('tinymce', 'myclass');\" onclick=\"if(this.checked){eval(this.getAttribute('onCheck'));}else if(!this.checked){eval(this.getAttribute('onUnCheck'));};
\"/> Show wireframe (makes editing easier)<br /><br /> <a target=\"_blank\" href=\"http://www.stencies.com/wordpress-plugin/beta-testing-feedback/\">Click here</a> to report a bug or give feedback</div></div>";
   

//  Wordpress thickbox  wont allow us to set width so  we have to do this bit of crappy code
echo" <script>
function stencies_resize_thickbox() {
        var StenciesWidth  = 900;
        jQuery('#TB_window').css({'marginLeft':-(StenciesWidth/2)});
        jQuery('#TB_window, #TB_iframeContent').width(StenciesWidth);
		// show iframe before all content is loaded FF
		tb_showIframe();
}</script>";
}

// adds the stylesheet to TinyMCE editor

//tinyMCE.activeEditor.undoManager.undo();

function tdav_css2($wp) {
		$wp .= ',' . get_bloginfo('stylesheet_url');
		$wp .= ',' .  WP_PLUGIN_URL .'/stencies/css/stencies_style.css';
		$wp .= ',' .  WP_PLUGIN_URL .'/stencies/stencies_site_css.php';
		return trim($wp, ' ,');
}

add_filter( 'mce_css', 'tdav_css2' );

// add plugin stylesheet to theme 
add_action('wp_head', 'stencies_register_head');
function stencies_register_head() {
	$siteurl = get_option('siteurl');
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/stencies_style.css';
	$site_style_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/stencies_site_css.php';
//	$ie6_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/stencies_style_ie6.css';
//	$ie7_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/stencies_style_ie7.css';
	
	echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
	echo "<link rel='stylesheet' type='text/css' href='$site_style_url' />\n";
//	echo "<!--[if IE 6]>";
//	echo "<link rel='stylesheet' type='text/css' href='$ie6_url' />\n";
//	echo "<![endif]-->";
//	echo "<!--[if IE 7]>";
//	echo "<link rel='stylesheet' type='text/css' href='$ie7_url' />\n";
//	echo "<![endif]-->";
}


// saves the content  & cursor position 
add_action('save_post', 'stencies_current_content');
function stencies_current_content()
{
    global $_POST;
    if($_POST['stencies_val'] != '')
    {
		update_option('stencies_current_content', $_POST['stencies_val']);
		return true; // You would more likely set an option or define a constant here instead so it can be more global
    }
    return false;
}
// end saves the contetn  & cursor position 


add_filter('mce_external_plugins', "stencies_register");
add_filter('mce_buttons', 'stenciess_add_button', 0);
//add_filter('wp_fullscreen_buttons', 'stenciess_add_button', 0);

//$buttons = apply_filters( 'wp_fullscreen_buttons', $buttons );

function stenciess_add_button($buttons){
    array_push($buttons, "separator", "stenciesplugin");
    return $buttons;
}

function stencies_register($plugin_array){
    $url = trim(get_bloginfo('url'), "/")."/wp-content/plugins/stencies/js/stencies_tiny_btn.js";

    $plugin_array['stenciesplugin'] = $url;
    return $plugin_array;
}



/****************
********************** Full screen mode editor button *************************
***************888 still manythings to resolve before including this in stencies*/
if ( !function_exists('stencies_fullscreen_buttons') )
{
function stencies_fullscreen_buttons($buttons)
{

// add a separator
$buttons[] = 'separator';
// format: title, onclick, show in both editors
$buttons['stencies'] = array(
// Title of the button
'title' => __('Stencies'),
// Command to execute
'onclick' => "document.forms.post.stencies_btn.click();",
// Show on visual AND html mode
'both' => false
);
$buttons['grid'] = array(
// Title of the button
'title' => __('Stencies show/hide grid'),
// Command to execute
'onclick' => "if (typeof check === 'undefined'){check=1;tinyMCE.activeEditor.dom.addClass('tinymce', 'myclass')}else if(check==1){check=0;tinyMCE.activeEditor.dom.removeClass('tinymce', 'myclass')}else if(check==0){check=1;tinyMCE.activeEditor.dom.addClass('tinymce', 'myclass')}",
// Show on visual AND html mode
'both' => false
);

return $buttons;
}

add_filter( 'wp_fullscreen_buttons', 'stencies_fullscreen_buttons' );
}

/**
 * Add CSS button to fullscreen editor
 */
function stencies_btn_css() {
    /**
     * Register <span class="IL_AD" id="IL_AD5">the style</span> handle
     */
    wp_register_style($handle = 'stencies_btn_css_style', $src = plugins_url('css/stencies_fullscr_btn.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
 
    /**
     * Now enqueue it
     */
    wp_enqueue_style('stencies_btn_css_style');
}
 
add_action('admin_print_styles-post.php', 'stencies_btn_css'); //add button css to post edit page
add_action('admin_print_styles-post-new.php', 'stencies_btn_css'); //add button css to new  post page

/***************************************************************************
*******************************************************************************/


/**
 * Addded to extended_valid_elements for TinyMCE
 *
 * @param $init assoc. array of TinyMCE options
 * @return $init the changed assoc. array
 */
function stencies_change_mce_options( $init ) {
    // Command separated string of extended elements
    $ext = 'div[id|class|style],h1,h2,h3,h4,h5,h6,p,style[id|class|style]';

    // Add to extended_valid_elements if it alreay exists
    if ( isset( $init['extended_valid_elements'] ) ) {
        $init['extended_valid_elements'] .= ',' . $ext;
    } else {
        $init['extended_valid_elements'] = $ext;
    }

    // Super important: return $init!
    return $init;
}

add_filter('tiny_mce_before_init', 'stencies_change_mce_options')

?>