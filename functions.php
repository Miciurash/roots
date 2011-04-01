<?php

include_once('includes/roots-activation.php');	// activation
include_once('includes/roots-admin.php');		// admin additions/mods
include_once('includes/roots-options.php');		// theme options menu
include_once('includes/roots-ob.php');			// output buffer
include_once('includes/roots-cleanup.php');		// code cleanup/removal
include_once('includes/roots-htaccess.php');	// h5bp htaccess

// set the value of the main container class depending on the selected grid framework
$roots_selected_css_framework = get_option('roots_css_framework');
if (!defined('CONTAINER_CLASS')){
	if ($roots_selected_css_framework === 'blueprint'){
		define('CONTAINER_CLASS', 'span-24');
		define('IS_960_GS',False);
	}
	elseif ($roots_selected_css_framework === '960gs_12'){
		define('CONTAINER_CLASS', 'container_12');
		define('IS_960_GS',True);
	}	
	elseif ($roots_selected_css_framework === '960gs_16'){
		define('CONTAINER_CLASS', 'container_16');	
		define('IS_960_GS',True);
	}
	elseif ($roots_selected_css_framework === '960gs_24'){
		define('CONTAINER_CLASS', 'container_24');
		define('IS_960_GS',True);
	}
	else {
		define('CONTAINER_CLASS', '');
		define('IS_960_GS',False);
	}

}

function get_roots_css_framework_stylesheets(){
	$roots_selected_css_framework = get_option('roots_css_framework');
	if ($roots_selected_css_framework === 'blueprint'){
		return '<link rel="stylesheet" href="/css/blueprint/screen.css">';
	}
	elseif ($roots_selected_css_framework === '960gs_12' || $roots_selected_css_framework === '960gs_16'){
		return <<<EOD
		<link rel="stylesheet" href="/css/960gs/reset.css">
		<link rel="stylesheet" href="/css/960gs/text.css">
		<link rel="stylesheet" href="/css/960gs/960.css">		
EOD;
	}
	elseif ($roots_selected_css_framework === '960gs_24'){
		return <<<EOD
		<link rel="stylesheet" href=""$roots_style_sheet_uri"/css/960gs/reset.css">
		<link rel="stylesheet" href=""$roots_style_sheet_uri"/css/960gs/text.css">
		<link rel="stylesheet" href=""$roots_style_sheet_uri"/css/960gs/960_24_col.css">		
EOD;
	}
	else {
		return '';
	}
}

function get_roots_960gs_cleardiv() {
	if (IS_960_GS) return "<div class=\"clear\" ></div>";
	else return "";
}
	
// set the maximum 'Large' image width to the Blueprint grid maximum width
if (!isset($content_width)) $roots_selected_css_framework === 'blueprint' ? $content_width = 950 : $content_width = 940;

// tell the TinyMCE editor to use editor-style.css
// if you have issues with getting the editor to show your changes then use the following line:
// add_editor_style('editor-style.css?' . time());
add_editor_style('editor-style.css');

add_theme_support('post-thumbnails');

// http://codex.wordpress.org/Post_Formats
// add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

add_theme_support('menus');
register_nav_menus(
	array(
	  'primary_navigation' => 'Primary Navigation',
	  'utility_navigation' => 'Utility Navigation'
	)
);

// make sure the menu fallback (wp_list_pages) adds the home link
function roots_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}

add_filter('wp_page_menu_args', 'roots_page_menu_args');

// remove container from menus
function roots_nav_menu_args($args = ''){
	$args['container'] = false;
	return $args;
}

add_filter('wp_nav_menu_args', 'roots_nav_menu_args');

// create widget areas: sidebar, footer
$sidebars = array('Sidebar', 'Footer');
foreach ($sidebars as $sidebar) {
	register_sidebar(array('name'=> $sidebar,
		'before_widget' => '<article id="%1$s" class="widget %2$s"><div class="container">',
		'after_widget' => '</div></article>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}

// add to robots.txt
// http://codex.wordpress.org/Search_Engine_Optimization_for_WordPress#Robots.txt_Optimization
add_action('do_robots', 'roots_robots');

function roots_robots() {
	echo "Disallow: /cgi-bin\n";
	echo "Disallow: /wp-admin\n";
	echo "Disallow: /wp-includes\n";
	echo "Disallow: /wp-content/plugins\n";
	echo "Disallow: /plugins\n";
	echo "Disallow: /wp-content/cache\n";
	echo "Disallow: /wp-content/themes\n";
	echo "Disallow: /trackback\n";
	echo "Disallow: /feed\n";
	echo "Disallow: /comments\n";
	echo "Disallow: /category/*/*\n";
	echo "Disallow: */trackback\n";
	echo "Disallow: */feed\n";
	echo "Disallow: */comments\n";
	echo "Disallow: /*?*\n";
	echo "Disallow: /*?\n";
	echo "Allow: /wp-content/uploads\n";
	echo "Allow: /assets";
}

?>
