<?PHP

/*

PLUGIN META INFO FOR WORDPRESS LISTINGS

Plugin Name: iCafe Library

Description: Wordpress plugin to organize and deploy large scale staff/professional development materials

Version: 1.8.3

Author: Chris Nilsson

*/

register_activation_hook( __FILE__, 'gw_activate' );

register_deactivation_hook( __FILE__, 'gw_deactivate' );



add_action('plugins_loaded', 'gw_update');

add_action('admin_menu', 'gw_admin_menu');

add_action( 'admin_enqueue_scripts', 'gw_admin_styles_scripts' );

add_action( 'wp_enqueue_scripts', 'gw_styles_scripts' );

add_action('wp_ajax_store_sort', 'store_sort');

add_action('wp_ajax_load_tile_sort', 'load_tile_sort');

add_action('wp_ajax_store_resource_sort', 'store_resource_sort');

add_action('wp_ajax_gw_resource_output', 'gw_resource_output');

add_action('wp_ajax_nopriv_gw_resource_output', 'gw_resource_output');

add_action('wp_ajax_gw_avalible_tile_list_ajax', 'gw_avalible_tile_list_ajax');

add_action('wp_ajax_gw_set_default_tile_ajax', 'gw_set_default_tile_ajax');

add_action( 'admin_init', 'gw_setup' );

add_action('template_redirect','gw_is_restricted');

//used to add dynamic CSS to the head

add_action( 'init', 'build_stylesheet_content' );

//used to add dynamic CSS to the head

add_action( 'wp_head', 'build_stylesheet_url' );

add_image_size( 'iCafe-Library-book', 180, 110 ); 

add_shortcode( 'iCafe-Library', 'gw_groundwork' );

add_shortcode( 'icafe-library', 'gw_groundwork' );

add_shortcode( 'groundwork', 'gw_groundwork' );

add_shortcode( 'GroundWork', 'gw_groundwork' );



//Activate the plugin

function gw_activate() {

	

	update_option("icafe_library", "1.8.3");

	

	

	update_option("active_chapter_icon", "");

	update_option("default_chapter_icon", "");

	update_option("default_chapter_text", "");

	update_option("active_chapter_text", "");

	update_option("active_chapter_color", "");

	update_option("default_chapter_color", "");

	update_option("default_section_text", "");

	update_option("active_section_text", "");

	update_option("active_section_color", "");

	update_option("default_section_color", "");

	update_option("resource_list_border", "");

	update_option("resource_list_text", "");

	update_option("resource_list_highlight", "");

	update_option("active_section_icon", "");

	update_option("default_section_icon", "");

	update_option("hide_circle", "");

	

	global $wpdb;

  

	//create tables

	$table_name = $wpdb->prefix . "gw_sections";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

	$sql = "CREATE TABLE " . $table_name . " (

				`sid` int(11) NOT NULL AUTO_INCREMENT,

				`title` text COLLATE utf8_unicode_ci,

				`detail` text COLLATE utf8_unicode_ci,

				`restricted` tinyint(4) DEFAULT '0',

				`access_type` text COLLATE utf8_unicode_ci,

				`hide` tinyint(4) DEFAULT '0',

				`logo` int(11) DEFAULT NULL,

				`parent_sid` int(11) DEFAULT '0',

				`section_order` int(11) DEFAULT NULL,

				`default_tile` text COLLATE utf8_unicode_ci,

				PRIMARY KEY (`sid`)

			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	  dbDelta($sql);

	}

	

	$table_name = $wpdb->prefix . "gw_tiles";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

	$sql = "CREATE TABLE " . $table_name . " (

			     	`tid` int(11) NOT NULL AUTO_INCREMENT,

					`title` text COLLATE utf8_unicode_ci,

					`description` text COLLATE utf8_unicode_ci,

					`media_type` text COLLATE utf8_unicode_ci,

					`picture` int(11) DEFAULT NULL,

					`youtube_url` text COLLATE utf8_unicode_ci,

					`embed_code` text COLLATE utf8_unicode_ci,

					`raw_code` text COLLATE utf8_unicode_ci,

					`links` text COLLATE utf8_unicode_ci,

					PRIMARY KEY (`tid`)

			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	  dbDelta($sql);

	}

	

	$table_name = $wpdb->prefix . "gw_lookup";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

	$sql = "CREATE TABLE " . $table_name . " (

					`resource_order` int(11) NOT NULL AUTO_INCREMENT,

 							`sid` int(11) DEFAULT NULL,

 							`tid` int(11) DEFAULT NULL,

 							 PRIMARY KEY (`resource_order`)

							 

					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	  dbDelta($sql);

	}

}



//deactivate the plugin

function gw_deactivate() {

//none at this time

}



//check Database Structure

function gw_update() {

	global $wpdb;

	$new_version = '1.8.3';

	$current_version = get_option('icafe_library');

	

	//Check if upgrade is needed
	//1.8.2 Fix "None" embed error on legacy YouTube Embeds

		$table_name = $wpdb->prefix . "gw_tiles";

		$wpdb->query("UPDATE $table_name SET media_type = 'youtube' WHERE youtube_url<>''");

	if ($new_version != $current_version) {

		

		$table_name = $wpdb->prefix . "gw_sections";

	

		$sql = "CREATE TABLE " . $table_name . " (

							`sid` int(11) NOT NULL AUTO_INCREMENT,

							`title` text COLLATE utf8_unicode_ci,

							`detail` text COLLATE utf8_unicode_ci,

							`restricted` tinyint(4) DEFAULT '0',

							`access_type` text COLLATE utf8_unicode_ci,

							`hide` tinyint(4) DEFAULT '0',

							`logo` int(11) DEFAULT NULL,

							`parent_sid` int(11) DEFAULT '0',

							`section_order` int(11) DEFAULT NULL,

							`default_tile` text COLLATE utf8_unicode_ci,

							PRIMARY KEY (`sid`)

				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		  dbDelta($sql);

		

		$wpdb->query("UPDATE $table_name SET access_type = 'all' WHERE restricted = 1");	

			

		

			

		update_option("icafe_library", $new_version);



	}

	

}

//include css and scripts on admin page

function gw_admin_styles_scripts() {

	

	wp_register_style( 'gw_admin_style',						//handle 

				plugins_url( 'css/gw_admin.css', __FILE__ ), 	//source 

				array(),  										//dependencies

				'1.6', 											//version 

				'all' ); 										

	wp_enqueue_style( 'gw_admin_style' ); 

	

	wp_register_style( 'gw_style', plugins_url( 'css/gw.css', __FILE__ ), array(), '', 'all' ); 

	wp_enqueue_style( 'gw_style' );



	wp_register_script(	'gw_nestedSortable', 						//handle

				plugins_url( 'js/nestedSortable.js', __FILE__ ), 	//source

				array('jquery-ui-sortable'), 						//dependencies

				'1.8', 												//version

				false 												//in footer

			);  

			

	wp_register_script(	'gw_admin_js', 						//handle

				plugins_url( 'js/gw_admin.js', __FILE__ ), 	//source

				array('gw_nestedSortable'), 				//dependencies

				'1.8.3', 										//version

				false 										//in footer

			);  



	wp_enqueue_script('gw_admin_js'); 



	wp_enqueue_media();

	$params = array(  

				'processing_file' => plugins_url( 'Processing.php', __FILE__ )

				);

	wp_localize_script( 'gw_admin_js', 'gw_admin_js_params', $params );

	}

	

//include css and scripts on front end

function gw_styles_scripts() {



	wp_register_style( 'gw_ui_style', plugins_url( 'css/gw-jquery-ui-blue.css', __FILE__ ), array(), '', 'all' ); 

	wp_enqueue_style( 'gw_ui_style' ); 

	

				

	wp_register_style( 'gw_style', plugins_url( 'css/gw.css', __FILE__ ), array(), '', 'all' ); 

	wp_enqueue_style( 'gw_style' ); 

	wp_register_script(	'gw_js', 								//handle

					plugins_url( 'js/gw.js', __FILE__ ), 	//source

					array('jquery-ui-accordion'), 						//dependencies

					'1.8.1', 										//version

					false 										//in footer

				);  

	wp_enqueue_script('gw_js');

	wp_localize_script( 'gw_js', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	wp_register_script(	'gw_hashtag', 								//handle

					plugins_url( 'js/hashtag_event.js', __FILE__ ), 	//source

					array('jquery'), 						//dependencies

					'1.8.1', 										//version

					false 										//in footer

				);  

	wp_enqueue_script('gw_hashtag');

	wp_register_script(	'gw_scrollTo', 								//handle

					plugins_url( 'js/scrollTo.js', __FILE__ ), 	//source

					array('jquery'), 						//dependencies

					'', 										//version

					false 										//in footer

				);  

	wp_enqueue_script('gw_scrollTo');

	wp_register_script(	'gw_localScroll', 								//handle

					plugins_url( 'js/localScroll.js', __FILE__ ), 	//source

					array('jquery'), 						//dependencies

					'', 										//version

					false 										//in footer

				);  

	wp_enqueue_script('gw_localScroll');



}

//setup options

function gw_setup() {

global $pagenow;

	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {

// Now we'll replace the 'Insert into Post Button' inside Thickbox

add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );

}

}

function replace_thickbox_text($translated_text, $text, $domain) {

if ('Insert into Post' == $text) {

$referer = strpos( wp_get_referer(), 'GroundWork-Sections' );

if ( $referer != '' ) {

	return __('Set as Section Logo', 'wptuts' );

}

}

return $translated_text;

}



//build the admin menu locations

function gw_admin_menu() {

$icon_url = plugins_url('images/groundwork.png', __FILE__);

	add_menu_page(		'iCafe Library', 		//page title

				'iCafe Library', 		//menu title

				'edit_posts',		//capibility required to access 

				'iCafe-Library-admin', //page slug

				'gw_admin_welcome'	//menu icon

				

			);

//appearance	

add_submenu_page( 	'iCafe-Library-admin', 	//parent slug

				'Appearance', 		//page title

				'Appearance',		//menue title

				'edit_posts',			//capibility required to access

				'iCafe-Library-Appearance', 	//page slug

				'gw_admin_appearance'		//function to run

				);

//sections	

add_submenu_page( 	'iCafe-Library-admin', 	//parent slug

				'Manage Books', 		//page title

				'Manage Books',		//menue title

				'edit_posts',			//capibility required to access

				'iCafe-Library-Sections', 	//page slug

				'gw_admin_sections'		//function to run

				);

//resources					

add_submenu_page( 	'iCafe-Library-admin', 	//parent slug

				'Manage Resources', 	//page title

				'Manage Resources',		//menue title

				'edit_posts',			//capibility required to access

				'iCafe-Library-Resources', //page slug

				'gw_admin_resources'	//function to run

				);

				

				//Custom Libraries				

add_submenu_page( 	'iCafe-Library-admin', 	//parent slug

				'Custom Bookshelves', 	//page title

				'Custom Bookshelves',		//menue title

				'edit_posts',			//capibility required to access

				'iCafe-Library-Custom-Bookshelves', //page slug

				'gw_admin_custom_bookshelves'	//function to run

				);



}

//Inject Custom CSS to <head> 

function build_stylesheet_url() {

    echo '<link rel="stylesheet" href="' . $url . 'icafe-library-custom.css?build=' . date( "Ymd", strtotime( '-24 days' ) ) . '" type="text/css" media="screen" />';

}



//Build Custom CSS

function build_stylesheet_content() {

    if( isset( $_GET['build'] ) && addslashes( $_GET['build'] ) == date( "Ymd", strtotime( '-24 days' ) ) ) {

        header("Content-type: text/css");

		

		//site url

		$site_url = network_site_url( '/' ).'wp-content/plugins/icafe-library/css/images/';	

		

		//Grab the current stored CSS values

		$active_chapter_icon_file = get_option( 'active_chapter_icon');

		$active_chapter_icon = $site_url.'ui-icons_'.$active_chapter_icon_file.'_0.png';

		$hover_chapter_icon_file = get_option( 'hover_chapter_icon');

		$hover_chapter_icon = $site_url.'ui-icons_'.$hover_chapter_icon_file.'_0.png';

		$default_chapter_icon_file = get_option( 'default_chapter_icon');

		$default_chapter_icon = $site_url.'ui-icons_'.$default_chapter_icon_file.'_0.png';

		$default_chapter_text = get_option('default_chapter_text');

		$active_chapter_text = get_option('active_chapter_text');

		$active_chapter_color = get_option('active_chapter_color');

		$default_chapter_color = get_option('default_chapter_color');

		$default_section_text = get_option('default_section_text');

		$active_section_text = get_option('active_section_text');

		$active_section_color = get_option('active_section_color');

		$default_section_color = get_option('default_section_color');

		$resource_list_border = get_option('resource_list_border');

		$resource_list_text = get_option('resource_list_text');

		$resource_list_highlight = get_option('resource_list_highlight');

		$active_section_icon_file = get_option( 'active_section_icon');

		$active_section_icon = $site_url.'ui-icons_'.$active_section_icon_file.'_0.png';

		$hover_section_icon_file = get_option( 'hover_section_icon');

		$hover_section_icon = $site_url.'ui-icons_'.$hover_section_icon_file.'_0.png';

		$default_section_icon_file = get_option( 'default_section_icon');

		$default_section_icon = $site_url.'ui-icons_'.$default_section_icon_file.'_0.png';

		$hide_circle = get_option( 'hide_circle');



	

	echo '

		/* Accordion */

		.ui-accordion-content{border-color:#66b3ff;background:#ffffff}

		.ui-accordion-header.ui-state-active, .ui-accordion-header.ui-state-active.ui-state-hover{background-color:#66b3ff !important;color:#ffffff;border-color:#66b3ff !important;background-image:url('.$site_url.'ui-icons_FFFFFF_0.png) !important;background-position: -230px -213px !important;}

		.ui-accordion-header.ui-state-active a{ color: #ffffff !important;}

		.ui-accordion-header.ui-state-default{border-color:#3399ff;background:#3399ff;}

		.ui-accordion-header.ui-state-default a{ color: #ffffff;}

		.ui-accordion-header.ui-state-hover{background:#66B3FF;border-color:#66B3FF;}

		.ui-accordion-header.ui-state-hover a{color:#ffffff;}

		.ui-accordion-header.ui-state-active .ui-icon{background-image:url('.$site_url.'ui-icons_FFFFFF_0.png) !important;}

		.ui-accordion-header.ui-state-default .ui-icon{background-image:url('.$site_url.'ui-icons_FFFFFF_0.png);}

		.ui-accordion-header.ui-state-hover .ui-icon{background-image:url('.$site_url.'ui-icons_66B3FF_0.png);}

		.ui-accordion-header{background-image:url('.$site_url.'ui-icons_FFFFFF_0.png) !important;background-position: -230px -214px !important;background-repeat:no-repeat !important;}

		.ui-accordion-header.ui-state-hover{background-image:url('.$site_url.'ui-icons_FFFFFF_0.png) !important;background-position: -230px -166px !important;}

		.ui-accordion .ui-accordion .ui-accordion-header.ui-state-active, .ui-accordion .ui-accordion .ui-accordion-header.ui-state-active.ui-state-hover {

			background-image: none !important;

		}

			

		/* Default Chapter Text*/

		.ui-accordion-header.ui-state-default a {

			color: #'.$default_chapter_text.';

		}

		

		/*Default Chapter Color*/

		.ui-accordion-header.ui-state-default {

			background: none repeat scroll 0 0 #'.$default_chapter_color.';

			border-color: #'.$default_chapter_color.';

		}

		

		/*Hover Over Chapter Color*/

		.ui-accordion-header.ui-state-hover {

    		background: none repeat scroll 0 0 #'.$active_chapter_color.';

    		border-color: #'.$active_chapter_color.';

		}

		

		/*Hover Over Chapter Text Color*/

		.ui-accordion-header.ui-state-default.ui-state-hover a {

			color: #'.$active_chapter_text.' !important;

		}

		

		/*Active Chapter Color*/

		.ui-accordion-header.ui-state-active, .ui-accordion-header.ui-state-active.ui-state-hover {

			background-color: #'.$active_chapter_color.' !important;

			border-color: #'.$active_chapter_color.' !important;			

		}		

		

		/*Active Chapter Text*/

		.ui-accordion-header.ui-state-active a {

			color: #'.$active_chapter_text.' !important;

		}

		

		/*Active Chapter Content Area Border*/

		.ui-accordion-content {   

    		border-color: #'.$active_chapter_color.';

		}

		

		/*Default Section Color*/

		.ui-accordion .ui-accordion .ui-accordion-header.ui-state-default {

   			background: none repeat scroll 0 0 #'.$default_section_color.';

    		border-color: #'.$default_section_color.';

		}

		

		/*Active Section Color*/

		.ui-accordion .ui-accordion .ui-accordion-header.ui-state-active {

    		background-color: #'.$active_section_color.' !important;

    		border-color: #'.$active_section_color.' !important;

		}

		

		/*Hover Over Section Color*/

		.ui-accordion .ui-accordion .ui-accordion-header.ui-state-default.ui-state-hover {

    		background-color: #'.$active_section_color.' !important;

    		border-color: #'.$active_section_color.' !important;

		}

		

		/*Hover Over Section Text Color*/

		.ui-accordion .ui-accordion .ui-accordion-header.ui-state-default.ui-state-hover a {

			color:#'.$active_section_text.' !important;

		}

		

		/*Active Section Text*/

		.ui-accordion .ui-accordion .ui-accordion-header.ui-state-active a {

			color:#'.$active_section_text.' !important;

		}

		

		/*Default Section Text*/

		.ui-accordion .ui-accordion .ui-accordion-header.ui-state-default a {

			color:#'.$default_section_text.';

		}

		

		/*Resource List Seperator*/

		.gw_menu_list li {

		  	border-color: #'.$resource_list_border.';

		}

		

		/*Resource List Text*/

		.gw_menu_list li a {

			  color: #'.$resource_list_text.';

		}

		 

		 /*Resource List Highlight*/

		.gw_menu_list li a:hover {

		   	background: #'.$resource_list_highlight.';

		}

		

		';

		

		

		if ($default_chapter_icon_file != 'default' && $default_chapter_icon_file != '') {

			echo '						

				/*Default Chapter Icon*/

				.ui-accordion-header.ui-state-default .ui-icon {

					background-image: url("'.$default_chapter_icon.'") !important;

				}		

			';		

		}

		

		if ($hover_chapter_icon_file != 'default' && $hover_chapter_icon_file != '') {

			echo '						

				/*Hover Chapter Icon*/

				.ui-accordion-header.ui-state-hover .ui-icon {

					background-image:url("'.$hover_chapter_icon.'") !important;

				}			

			';		

		}

		

		if ($active_chapter_icon_file != 'default' && $active_chapter_icon_file != '') {

			echo '						

				/*Active Chapter Icon*/

				.ui-accordion-header.ui-state-active .ui-icon {

					background-image: url("'.$active_chapter_icon.'") !important;

				}		

			';		

		}

		

		if ($default_section_icon_file != 'default' && $default_section_icon_file != '') {

			echo '						

				/*Default Section Icon*/

				.ui-accordion .ui-accordion .ui-accordion-header.ui-state-default .ui-icon {

					background-image: url("'.$default_section_icon.'") !important;

				}		

			';		

		}

		

		if ($hover_section_icon_file != 'default' && $hover_section_icon_file != '') {

			echo '						

				/*Hover Section Icon*/

				.ui-accordion .ui-accordion .ui-accordion-header.ui-state-hover .ui-icon {

					background-image:url("'.$hover_section_icon.'") !important;

				}			

			';		

		}

		

		if ($active_section_icon_file != 'default' && $active_section_icon_file != '') {

			echo '						

				/*Active Section Icon*/

				.ui-accordion .ui-accordion .ui-accordion-header.ui-state-active .ui-icon {

					background-image: url("'.$active_section_icon.'") !important;

				}		

			';		

		}

		

		if ($default_section_icon_file == 'none') {

			echo '

			.ui-accordion .ui-accordion .ui-accordion-header a {

				padding-left: 0.6em;

			}

			';

		}

		

		if ($hide_circle == 'checked') {

			echo '

				.ui-accordion .ui-accordion-header a {

					padding-left: 0.5em;

				}

				.ui-accordion-header {

					background-image: none !important;

				}

				.ui-accordion-header.ui-state-active, .ui-accordion-header.ui-state-active.ui-state-hover {

					background-image: none !important;

				}

				.ui-accordion-header.ui-state-hover {

					 background-image: none !important;

				}	

			';

	}

		



        define( 'DONOTCACHEPAGE', 1 ); // don't let wp-super-cache cache this page.

        die();

    }

}



//build the main landing page

function gw_admin_welcome() {

	echo '<h2>iCafe Library</h2>

		<h3>Simple Elegant Resource Management</h3>

		The iCafe Library plugin allows you to create Resource "Books" and maintain them quickly and easily. Your users will love how easy it is to locate just the right resource using the beautiful "Bookshelf" and simple Chapter-Section navigation.<br /><br />

	Visit a live iCafe Library at the <a href="http://icafe.lcisd.org/resources" target="_new">iCafe web site</a><br/><br />

	The four videos below will get you started or you can simply begin adding resources and creating books. 

	Add the shortcode <strong>[icafe-library]</strong> to a page to view your bookshelf!<br />

	<h3>1 - Creating your first Book</h3>

	<iframe width="420" height="315" src="http://www.youtube.com/embed/7N9eJdQiPIs" frameborder="0" allowfullscreen></iframe>

	<h3>2 - Creating your first Resource Tile</h3>

	<iframe width="420" height="315" src="http://www.youtube.com/embed/F3uLCYytQao" frameborder="0" allowfullscreen></iframe>

	<h3>3 - Adding Resource Tiles to a Book</h3>

	<iframe width="420" height="315" src="http://www.youtube.com/embed/phMJfvPXrig" frameborder="0" allowfullscreen></iframe>

	<h3>4 - Adding your library to a Page</h3>

	<iframe width="420" height="315" src="http://www.youtube.com/embed/fp8IatJUJDA" frameborder="0" allowfullscreen></iframe>

	<br /><br />

	<strong>Please send any questions, comments, or suggestions to chrisdnilsson@gmail.com</strong>

	 ';

	 

}



//build the appearance manager

function gw_admin_appearance() {

	

	

	if ($_POST) {//update options

			echo '<div id="message" class="updated fade"><p>Your new settings were saved successfully.</p></div>';

			if ($_POST['appearance'] == 'update') {

				

				$active_chapter_icon = $_POST['active_chapter_icon'];

				update_option("active_chapter_icon", $active_chapter_icon);

				$hover_chapter_icon = $_POST['hover_chapter_icon'];

				update_option("hover_chapter_icon", $hover_chapter_icon);

				$default_chapter_icon = $_POST['default_chapter_icon'];

				update_option("default_chapter_icon", $default_chapter_icon);

				$default_chapter_text = $_POST['default_chapter_text'];

				update_option("default_chapter_text", $default_chapter_text);

				$active_chapter_text = $_POST['active_chapter_text'];

				update_option("active_chapter_text", $active_chapter_text);

				$active_chapter_color = $_POST['active_chapter_color'];

				update_option("active_chapter_color", $active_chapter_color);

				$default_chapter_color = $_POST['default_chapter_color'];

				update_option("default_chapter_color", $default_chapter_color);

				$default_section_text = $_POST['default_section_text'];

				update_option("default_section_text", $default_section_text);

				$active_section_text = $_POST['active_section_text'];

				update_option("active_section_text", $active_section_text);

				$active_section_color = $_POST['active_section_color'];

				update_option("active_section_color", $active_section_color);

				$default_section_color = $_POST['default_section_color'];

				update_option("default_section_color", $default_section_color);

				$resource_list_border = $_POST['resource_list_border'];

				update_option("resource_list_border", $resource_list_border);

				$resource_list_text = $_POST['resource_list_text'];

				update_option("resource_list_text", $resource_list_text);

				$resource_list_highlight = $_POST['resource_list_highlight'];

				update_option("resource_list_highlight", $resource_list_highlight);

				$active_section_icon = $_POST['active_section_icon'];

				update_option("active_section_icon", $active_section_icon);

				$hover_section_icon = $_POST['hover_section_icon'];

				update_option("hover_section_icon", $hover_section_icon);

				$default_section_icon = $_POST['default_section_icon'];

				update_option("default_section_icon", $default_section_icon);

				$hide_circle = $_POST['hide_circle'];

				update_option("hide_circle", $hide_circle);

							

				

			}

			

	}

	

	//Grab the current stored CSS values

	$active_chapter_icon = get_option('active_chapter_icon');

	$hover_chapter_icon = get_option('hover_chapter_icon');

	$default_chapter_icon = get_option('default_chapter_icon');

	$default_chapter_text = get_option('default_chapter_text');

	$active_chapter_text = get_option('active_chapter_text');

	$active_chapter_color = get_option('active_chapter_color');

	$default_chapter_color = get_option('default_chapter_color');

	$default_section_text = get_option('default_section_text');

	$active_section_text = get_option('active_section_text');

	$active_section_color = get_option('active_section_color');

	$default_section_color = get_option('default_section_color');

	$resource_list_border = get_option('resource_list_border');

	$resource_list_text = get_option('resource_list_text');

	$resource_list_highlight = get_option('resource_list_highlight');

	$active_section_icon = get_option('active_section_icon');

	$hover_section_icon = get_option('hover_section_icon');

	$default_section_icon = get_option('default_section_icon');

	$hide_circle = get_option('hide_circle');

	$images_dir = network_site_url( '/' ).'wp-content/plugins/icafe-library/css/images/';	

	

	

	echo '

			<div class="wrap">

				<h2>iCafe Library Appearance</h2>

			<table width="100%" border="0" cellspacing="5" cellpadding="0">

			  <tr>

				<td width="350px">

				<form method="post" id="gw_appearance">

					Enter the hexadecimal color values (without the #) below to override the default plugin colors. A blank value will display the default color. The icons are only avalible in eight color options. Select "None" in the dropdown to turn them off.

					<fieldset class="options">

						<ul>

						  	<li>

								<label for="active_chapter_color"><strong>1. Selected Chapter Background Color</strong></label>

								<br />

								#<input type="text" name="active_chapter_color" value="'.$active_chapter_color.'" size="10"/>

							</li>

							<li>

								<label for="active_chapter_text"><strong>2. Selected Chapter Font Color</strong></label>

								<br />

								#<input type="text" name="active_chapter_text" value="'.$active_chapter_text.'" size="10"/>

							</li>

							<li>

								<label for="active_chapter_icon"><strong>3a. Selected Chapter Triangle Icon Color</strong></label>

								<br />

								#'.gw_icon_color_select($active_chapter_icon, "active_chapter_icon").'

							</li>

							<li>

								<label for="hide_circle"><strong>3b. Circle Graphic</strong></label>

								<br />

								&nbsp;&nbsp;&nbsp;<input name="hide_circle" type="checkbox" value="checked" '.$hide_circle.' />&nbsp;<i>Hide Circle</i>

							</li>

							

							

							<li>

								<label for="default_chapter_color"><strong>4. Non-Selected Chapter Background Color</strong></label>

								<br />

								#<input type="text" name="default_chapter_color" value="'.$default_chapter_color.'" size="10"/>

							</li>

							<li>

								<label for="default_chapter_text"><strong>5. Non-Selected Chapter Font Color</strong></label>

								<br />

								#<input type="text" name="default_chapter_text" value="'.$default_chapter_text.'" size="10"/>

							</li>

							<li>

								<label for="default_chapter_icon"><strong>6a. Non-Selected Chapter Triangle Icon Color</strong></label>

								<br />

								#'.gw_icon_color_select($default_chapter_icon, "default_chapter_icon").'

							</li>

							<li>

								<label for="hover_chapter_icon"><strong>6b. Hover Over Chapter Triangle Icon Color</strong></label>

								<br />

								#'.gw_icon_color_select($hover_chapter_icon, "hover_chapter_icon").'

								

							</li>

							

							

							<li>

								<label for="active_section_color"><strong>7. Selected Section Background Color</strong></label>

								<br />

								#<input type="text" name="active_section_color" value="'.$active_section_color.'" size="10"/>

							</li>

							<li>

								<label for="active_section_text"><strong>8. Selected Section Font Color</strong></label>

								<br />

								#<input type="text" name="active_section_text" value="'.$active_section_text.'" size="10"/>

							</li>

							<li>

								<label for="active_section_icon"><strong>9. Selected Section Triangle Icon Color</strong></label>

								<br />

								#'.gw_icon_color_select($active_section_icon, "active_section_icon").'

							</li>

							

							

							<li>

								<label for="default_section_color"><strong>10. Non-Selected Section Background Color</strong></label>

								<br />

								#<input type="text" name="default_section_color" value="'.$default_section_color.'" size="10"/>

							</li>

							<li>

								<label for="default_section_text"><strong>11. Non-Selected Section Font Color</strong></label>

								<br />

								#<input type="text" name="default_section_text" value="'.$default_section_text.'" size="10"/>

							</li>

							<li>

								<label for="default_section_icon"><strong>12a. Non-Selected Section Triangle Icon Color</strong></label>

								<br />

								#'.gw_icon_color_select($default_section_icon, "default_section_icon").'

							</li>

							<li>

								<label for="hover_section_icon"><strong>12b. Hover Over Section Triangle Icon Color</strong></label>

								<br />

								#'.gw_icon_color_select($hover_section_icon, "hover_section_icon").'

							</li>

							

							

							<li>

								<label for="resource_list_highlight"><strong>13. Resource List Highlight Color</strong></label>

								<br />

								#<input type="text" name="resource_list_highlight" value="'.$resource_list_highlight.'" size="10"/>

							</li>

							<li>

								<label for="resource_list_text"><strong>14. Resource List Font Color</strong></label>

								<br />

								#<input type="text" name="resource_list_text" value="'.$resource_list_text.'" size="10"/>

							</li>

							

						</ul>

					</fieldset>

					<input name="appearance" type="hidden" value="update" />

					 <p class="submit"><input type="submit" name="gw_CSS_update" value="Update Options &raquo;" /></p>

				</form>

			</td>

			<td>

			<img src="'.$images_dir.'css_control_key.png" width="306" height="281" />

			</td>

		  </tr>

		</table>

			</div>

			

';

	

}



//build the icon color dropdown

function gw_icon_color_select($current_color, $name) {

	//array of currently avalible icon colors

	$options_arr = array('default'=>array('000000','Default'), 'none'=>array('000000','None'), '0E6D38'=>array('0E6D38','0E6D38'), '19A053'=>array('19A053','19A053'), '3399FF'=>array('3399FF','3399FF'), '66B3FF'=>array('66B3FF','66B3FF'), '333333'=>array('333333','333333'), '525252'=>array('525252','525252'), 'D4D4D4'=>array('D4D4D4','D4D4D4'), 'FFFFFF'=>array('000000','White'));

	 $options = '';



        foreach($options_arr as $k => $v){

            $s = ($current_color == $k)? ' selected="selected"' : '';

            $options .= '<option  style="color: #'.$v[0].'" value="'.$k.'"'.$s.'>'.$v[1].'</option>'."\n";

        }



	$output = '	

		<select name='.$name.'>

		 '.$options.'

		</select>

		';	

		

	return $output;

}



//build the sections manager

function gw_admin_sections() {

global $wpdb;

global $wp_roles;

add_thickbox();

if ($_POST) {//update options



if (isset($_POST['delete'])) {//delete section

	$sid = $_POST['sid']; 

      		$table_name = $wpdb->prefix . "gw_sections";

	$wpdb->query( 

		$wpdb->prepare( 

			"

			 DELETE FROM $table_name

			 WHERE sid = %d", $sid

			)

	);

	

	$table_name = $wpdb->prefix . "gw_lookup";

	$wpdb->query( 

		$wpdb->prepare( 

			"

			 DELETE FROM $table_name

			 WHERE sid = %d", $sid

			)

	);

	$table_name = $wpdb->prefix . "gw_sections";

	$wpdb->update($table_name, array('parent_sid' => "0"), array('parent_sid' => "$sid"));

$show_edit = 'false';

    	

} else {//add new or edit existing section

     		

    

	

	if ($_POST['form'] == 'add_node') {

		echo '<div id="message" class="updated fade"><p>Your new node has been added below.</p></div>';

	

		$title = $_POST['title'];

		$detail = $_POST['detail'];

		$logo_id = $_POST['image_id'];

		$restricted = $_POST['restricted'];

		$access_type = $_POST['access_type'];

		$hide = $_POST['hide'];

		

		if ($access_type == 'users') {

			$users_id = implode(', ', $_POST['auth_users']);

			$access_type .= ', '.$users_id;

			

			

		} else if ($access_type == 'roles') {

			$roles_id = implode(', ', $_POST['auth_roles']);

			$access_type .= ', '.$roles_id;

		}

		

		//set blanks to 0 for mySQL 5 installations	not running in traditional mode

		if ($restricted == '') {

			$restricted = 0;

		}

		if ($logo_id == '') {

			$logo_id = 0;

		}

		if ($hide == '') {

			$hide = 0;

		}

		$table_name = $wpdb->prefix . "gw_sections";			

		$wpdb->insert($table_name, array('title' => "$title", 'detail' => "$detail", 'restricted' => "$restricted", 'access_type' => "$access_type", 'hide' => "$hide", 'logo' => "$logo_id"));

		

		

	} else if ($_POST['form'] == 'edit_node') {

		echo '<div id="message" class="updated fade"><p>Your changes have been saved.</p></div>';

		$title = $_POST['title'];

		$detail = $_POST['detail'];	

		$logo_id = $_POST['image_id'];

		$restricted = $_POST['restricted'];

		$access_type = $_POST['access_type'];

		$hide = $_POST['hide'];

		$sid = $_POST['sid']; 

		

		if ($access_type == 'users') {

			$users_id = implode(', ', $_POST['auth_users']);

			$access_type .= ', '.$users_id;

			

			

		} else if ($access_type == 'roles') {

			$roles_id = implode(', ', $_POST['auth_roles']);

			$access_type .= ', '.$roles_id;

		}

		

		//set blanks to 0 for mySQL 5 installations	not running in traditional mode

		if ($restricted == '') {

			$restricted = 0;

		}

		if ($logo_id == '') {

			$logo_id = 0;

		}

		if ($hide == '') {

			$hide = 0;

		}

							

		$table_name = $wpdb->prefix . "gw_sections";			

		$wpdb->update($table_name, array('title' => "$title", 'detail' => "$detail", 'restricted' => "$restricted", 'access_type' => "$access_type", 'hide' => "$hide", 'logo' => "$logo_id"), array('sid' => "$sid"));

		

		$show_edit = 'false';

		

		

	}

	

}

}

$output = '<div class="wrap">

            <h2>Manage Resource Organization</h2>';

if ($_GET) {//update options

if (($_GET['state'] == 'edit') && ($show_edit != 'false')) {//edit existing section

	$sid = $_GET['sid'];

	$table_name = $wpdb->prefix . "gw_sections";			

	$section_data = $wpdb->get_row("SELECT * FROM $table_name WHERE sid = $sid");

	$test = $wpdb->show_errors();

	$title = $section_data->title; 

	$detail = $section_data->detail; 

	$restricted = $section_data->restricted;

	$access_type_string = $section_data->access_type;

	$hide = $section_data->hide;  

	$logo = $section_data->logo; 

	

	//set some defaults if previously unrestricted

	$access_type_array = array();

	$access_all_checked = 'checked';

	

	if ($restricted == 1) {

		$restricted_checked = 'checked';	

		$access_type_array = array_map('trim',explode(",",$access_type_string));

		$access_type = $access_type_array[0];

		

		if ($access_type == 'all') {

			$access_all_checked = 'checked';

		} else if ($access_type == 'users') {

			$access_users_checked = 'checked';

		} else if ($access_type == 'roles') {

			$access_roles_checked = 'checked';

		}

	} else {

		$restricted_checked = '';

		$hidden = 'class="hidden"';

	}

	

	if ($hide == 1) {

		$hide_checked = 'checked';	

	} else {

		$hide_checked = '';

	}

	

$output .= '

	<h3>Edit Container</h3>

            <form method="post">

                <fieldset class="options">

		

                  <div style="float: left">

			  <label for="title"><strong>Book/Chapter/Section Title</strong></label>

			  <br />

			  <input id="title" type="text" name="title" size="20" value="'.$title.'"/>

			  <br />

			  <br />

			  <label for="image"><strong>Hide Container?</strong></label>

			  <br />	

			  <input name="hide" type="checkbox" value="1" '.$hide_checked.'/><br />

			  <em>Makes container and all sub-containers<br />visible only to Contributors or higher.<br />Useful for building content before going live.</em>

			  <br />

			  <br />

			  <input type="submit" name="newnode_save" value="Save Changes &raquo;" class="button-primary" />

			  <br /><br /><br /><br />

			  <input type="submit" name="delete" value="Delete Container &raquo;" class="button button-red" onclick="return confirm(\'Are you sure you want to permenantly delete container?\');"/>

                    </div>

			<div id="gw_preview_title"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Required for Books</strong></div>

			<div style="float: left; padding-left:10px" class="gw_border"> 

			

				<div style="float: left; padding-left:10px"> 						

				  <label for="detail"><strong>Tag Line</strong></label>

				  <br />

				  <input id="detail" type="text" name="detail" size="30" value="'.$detail.'"/>

				   

				  <br /><br />

				  

				  

				  <input id="gw_update_logo_button" type="button" name="image" class="button" value="Upload Book Cover Image" />

				  <br /> 

				  <em>Images should be 180 x 110px</em> 

				  <br />

				  <br />

				  <label for="image"><strong>Require Login for Access?</strong></label>	

				  <input id="members_only_check" name="restricted" type="checkbox" value="1" '.$restricted_checked.'/>

				  <br />

				  <br />

				  <div id="access_type" '.$hidden.'>

					  <label for="image"><strong>Who Can Access this Book?</strong></label>

					  <br />	

					  <input name="access_type" type="radio" value="all" '.$access_all_checked.' />&nbsp;All Members<br />

					  <input name="access_type" type="radio" value="users" '.$access_users_checked.' />&nbsp;Selected Members<br />

					  <input name="access_type" type="radio" value="roles" '.$access_roles_checked.' />&nbsp;Selected Roles<br />

				  </div>

				</div>

				

				<div style="float: left; padding-left:30px">

				<strong>Live Preview</strong>

				  <ul id="gw_new_top_node">

					  <li id="post-1" class="gw_bags ">

						  <div class="gw_thumb">

							  '.wp_get_attachment_image( $logo, 'iCafe-Library-book').'

						  </div>

						  <h2 id="gw_title">

							  '.$title.'

						  </h2>

						  <span id="gw_detail">'.$detail.'</span>

					  </li>

				  </ul>

				</div>

				

			</div>	

			

			<div id="user_list" style="display:none;">

     			<p>

				<h2>Make selections then close this window to continue book creation</h2>

         			<ul>';

											

						$blogusers = get_users('orderby=display_name');

						foreach ($blogusers as $user) {

							$checked = (in_array($user->ID, $access_type_array)) ? 'checked': '';

							$output .= '<li><input name="auth_users[]" type="checkbox" value="'.$user->ID.'" '.$checked.'/>&nbsp;&nbsp;' . $user->display_name . '</li>';

						}

					

	$output .=		'</ul>

     			</p>

			</div>

			<div id="role_list" style="display:none;">

     			<p>

				<h2>Make selections then close this window to continue book creation</h2>

				<span>

				This function is most useful with the addition of a roles/capibilities plugin like "Members".<br />

				Regardless of selection, Administrators will always have access to all books.

				</span>

         			<ul>';

					

     					$roles = $wp_roles->get_names();

						foreach($roles as $role_name => $role) {

							$checked = (in_array($role_name, $access_type_array)) ? 'checked': '';

							$output .= '<li><input name="auth_roles[]" type="checkbox" value="'.$role_name.'" '.$checked.'/>&nbsp;&nbsp;' . $role . '</li>';

						}

     $output .=		'</ul>

				</p>

			</div>

			

		

		 <input type="hidden" id="gw_logo_id" name="image_id" value="'.$logo.'"/> 

		  <input name="sid" type="hidden" value="'.$sid.'" />

		 <input name="form" type="hidden" value="edit_node" />

	</fieldset>	 

		

            

	</form>

	



';

	

} else {

$output .= '

	<h3><a href="#" id="showadd_node_form">Add New Container</a></h3>

            <form method="post" id="newnode">

                <fieldset class="options">

		

                  <div style="float: left">

			  <label for="title"><strong>Book/Chapter/Section Title</strong></label>

			  <br />

			  <input id="title" type="text" name="title" size="20"/>

			 <br />

			  <br />

			  <label for="image"><strong>Hide Container?</strong></label>

			  <br />	

			  <input name="hide" type="checkbox" value="1" /><br />

			  <em>Makes container and all sub-containers<br />visible only to Contributors or higher.<br />Useful for building content before going live.</em>

			  <br />

			  <br />

			  <input type="submit" name="newnode_save" value="Add Container &raquo;" class="button-primary" />

                    </div>

			<div id="gw_preview_title"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Required for Books</strong></div>

			<div style="float: left; padding-left:10px" class="gw_border"> 

			

				<div style="float: left; padding-left:10px"> 						

				  <label for="detail"><strong>Tag Line</strong></label>

				  <br />

				  <input id="detail" type="text" name="detail" size="30"/>

				   

				  <br /><br />

				  

				  

				  <input id="gw_upload_logo_button" type="button" name="image" class="button" value="Upload Book Cover Image" />

				  <br /> 

				  <em>Images should be 180 x 110px</em> 

				  <br />

				  <br />

				  <label for="image"><strong>Require Login for Access?</strong></label>	

				  <input id="members_only_check" name="restricted" type="checkbox" value="1" />

				  <br />

				  <br />

				  <div id="access_type" class="hidden">

					  <label for="image"><strong>Who Can Access this Book?</strong></label>

					  <br />	

					  <input name="access_type" type="radio" value="all" checked />&nbsp;All Members<br />

					  <input name="access_type" type="radio" value="users" />&nbsp;Selected Members<br />

					  <input name="access_type" type="radio" value="roles" />&nbsp;Selected Roles<br />

				  </div>

				</div>

				

				<div style="float: left; padding-left:30px">

				<strong>Live Preview</strong>

				  <ul id="gw_new_top_node">

					  <li id="post-1" class="gw_bags ">

						  <div class="gw_thumb">

							  <img width="337" height="332" id="gw_newnode_image" src="" />

						  </div>

						  <h2 id="gw_title">

							  

						  </h2>

						  <span id="gw_detail"></span>

					  </li>

				  </ul>

				</div>

				

			</div>	

			

			<div id="user_list" style="display:none;">

     			<p>

				<h2>Make selections then close this window to continue book creation</h2>

         			<ul>';

											

						$blogusers = get_users('orderby=display_name');

						foreach ($blogusers as $user) {

							$output .= '<li><input name="auth_users[]" type="checkbox" value="'.$user->ID.'" />&nbsp;&nbsp;' . $user->display_name . '</li>';

						}

					

	$output .=		'</ul>

     			</p>

			</div>

			<div id="role_list" style="display:none;">

     			<p>

				<h2>Make selections then close this window to continue book creation</h2>

				<span>

				This function is most useful with the addition of a roles/capibilities plugin like "Members".<br />

				Regardless of selection, Administrators will always have access to all books.

				</span>

         			<ul>';

					

     					$roles = $wp_roles->get_names();

						foreach($roles as $role_name => $role) {

							$output .= '<li><input name="auth_roles[]" type="checkbox" value="'.$role_name.'" />&nbsp;&nbsp;' . $role . '</li>';

						}

     $output .=		'</ul>

				</p>

			</div>

		 <input type="hidden" id="gw_logo_id" name="image_id" />  

		 <input name="form" type="hidden" value="add_node" />

	</fieldset>	 

		

            

	</form>

';

}



} else {



}

$output .= '</div>';



$table_name = $wpdb->prefix . "gw_sections";

//Start the output page

$output .= '<h3>Current Resource Tree</h3>	

		 <div class="gw_sort">

		 Simply drag and drop to change order or nesting

			<ol class="gw_sortable">';

//build level one

$level1_nodes = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = 0 ORDER BY section_order");

if ($level1_nodes) {

foreach ($level1_nodes as $level1_node) {

	$output .= gw_generate_section_sort($level1_node->sid, $level1_node->title, $level1_node->detail);

		

		//build level two

		$level2_nodes = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = $level1_node->sid ORDER BY section_order");

		if ($level2_nodes) {

			$output .= '<ol>';

			foreach ($level2_nodes as $level2_node) {

				$output .= gw_generate_section_sort($level2_node->sid, $level2_node->title, $level2_node->detail);

				

				//build level three

				$level3_nodes = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = $level2_node->sid ORDER BY section_order");

				if ($level3_nodes) {

					$output .= '<ol>';

					foreach ($level3_nodes as $level3_node) {

						$output .= gw_generate_section_sort($level3_node->sid, $level3_node->title, $level3_node->detail);

					}

				$output .= '</ol>';

				}

			}

		$output .= '</ol>';

		}



}

}

$output .= '</ol>

		</div>';



$output .= '<div class="gw_assigned_tiles">

	<strong>Resources For</strong><br />

	<div id="gw_add_tiles_heading">&nbsp;</div>

	<br />

	

	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">

	  <tr>

		<td width="20%">ID</td>

		<td width="20%">Title</td>

		<td width="60%" align="right">Default Tile?</td>

	  </tr>

	</table>



		<ul id="gw_assigned_tiles_sort" class="gw_connectedSortable">';

	

			

	

$output .= '	</ul>

	</div>';

$output .= '<div class="gw_avalible_tiles">

		<strong>Avalible Tiles</strong><br />

	<div id="gw_tile_display_options"><a href="#" class="gw_tile_display_mode" id="gw_unused">Unused Tiles</a> | <a href="#" class="gw_tile_display_mode" id="gw_all">All Tiles</a></div>

	<br />

	

	<ul id="gw_avalible_tiles_sort" class="gw_connectedSortable">';

 				

			$output .= gw_avalible_tile_list();

		

$output .= '</ul>

	</div>';

$output .= '<div class="spacer" style="clear: both;"></div>';

echo $output;

}

//generate lists of tiles for the section manager

function gw_avalible_tile_list() {

global $wpdb;

//list all the existing tiles

$tile_list = '';

$table_name = $wpdb->prefix . "gw_tiles";

$tiles = $wpdb->get_results("SELECT * FROM $table_name ORDER BY title");

if ($tiles) {



foreach ($tiles as $tile) {

	$tile_list .= '<li id="gw_tile_'.$tile->tid.'" title="'.$tile->tid.' - '.str_replace("\\", "", str_replace("\"", "&quot;", $tile->description)).'">'.$tile->title.'</li>';

}

}

return $tile_list;



}



//store the default view for a book

function gw_set_default_tile_ajax() {

	global $wpdb;

	if(!empty($_POST['default_tile'])) {

		$default_tile = $_POST['default_tile'];

		$default_tile_values = explode("_", $default_tile);

		$book = $default_tile_values[0];

		$section = $default_tile_values[1];

		$tile = $default_tile_values[2];

		$url_hash = '#section_'.$section.'_tile_'.$tile;

		

		$table_name = $wpdb->prefix . "gw_sections";

		$wpdb->update($table_name, array('default_tile' => "$url_hash"), array('sid' => "$book"));

		

	}



}



//generate lists of tiles for the section manager

function gw_avalible_tile_list_ajax() {

global $wpdb;

if(!empty($_POST['mode'])) {

$mode = $_POST['mode'];

if ($mode == 'gw_all') {

	//list all the existing tiles

	$tile_list = '';

	$table_name = $wpdb->prefix . "gw_tiles";

	$tiles = $wpdb->get_results("SELECT * FROM $table_name ORDER BY tid");

	if ($tiles) {

	

		foreach ($tiles as $tile) {

			$tile_list .= '<li id="gw_tile_'.$tile->tid.'" title="'.$tile->tid.' - '.str_replace("\\", "", str_replace("\"", "&quot;", $tile->description)).'">'.$tile->title.'</li>';

		}

	}

}else if ($mode == 'gw_unused'){

	

	$table_gw_lookup = $wpdb->prefix . "gw_lookup";

 	$table_gw_tiles = $wpdb->prefix . "gw_tiles";

	  		$tiles = $wpdb->get_results("SELECT $table_gw_tiles.tid, $table_gw_tiles.title, $table_gw_tiles.description FROM $table_gw_tiles LEFT JOIN $table_gw_lookup ON $table_gw_lookup.tid = $table_gw_tiles.tid WHERE $table_gw_lookup.tid IS NULL ORDER BY $table_gw_tiles.tid");

	if ($tiles) {

	

		foreach ($tiles as $tile) {

			$tile_list .= '<li id="gw_tile_'.$tile->tid.'" title="'.$tile->tid.' - '.str_replace("\\", "", str_replace("\"", "&quot;", $tile->description)).'">'.$tile->title.'</li>';

		}

	} else {

	

		$tile_list = 'All tiles are currently associated with a section.<br /><br />Please create new tiles or switch back to "All Tiles" view.<br /><br />Tiles may be reused in multiple sections';

	}

}

}

echo $tile_list;

die;



}



//create the nodes of the sortable tree in the section admin page

function gw_generate_section_sort($sid, $title, $detail) {

$sortable_section = '

					<li id="list_'.$sid.'">

						<div class="gw_sortable_container">

							<div class="gw_sort_expand_control">

								<span class="disclose">

									<span></span>

								</span>

							<a href="'.admin_url().'admin.php?page=iCafe-Library-Sections&state=edit&sid='.$sid.'">edit</a>

							</div>

							<div class="gw_sort_text">

								<div class="gw_sort_title">

									'.$title.'

								</div>

								

								<div class="gw_sort_detail">

									'.$detail.'

								</div>

								<div class="gw_add_tiles"><a class="gw_add_resource" id="gw_add_'.$sid.'">Add Resource Tiles</a></div>

							</div>

						</div>';

					

return $sortable_section;



}



//process the ajax call on a resort of section order

function store_sort() {

if (!empty($_POST["list"]) && $_POST["update_sql"] = 'ok') {

global $wpdb;

parse_str($_POST['list'], $order);

$index = 0;

foreach ($order['list'] as $sid => $parent_id) {

	//insert new order into the DB

	$table_name = $wpdb->prefix . "gw_sections";			

	$wpdb->UPDATE($table_name, array('parent_sid' => "$parent_id", 'section_order' => "$index"), array('sid' => "$sid"));

	$index++;

}

}



}

function load_tile_sort() {

if(!empty($_POST['section'])) {

	global $wpdb;	

	$section = $_POST['section'];



	//find the parent book for this node

	$table_name = $wpdb->prefix . "gw_sections";

	$parent = $wpdb->get_row("SELECT sid, parent_sid FROM $table_name WHERE sid = $section");

	$parent_sid = $parent->parent_sid;

	$recursive_sid = $parent->sid;	

	while ($parent_sid != 0) {

		$parent = $wpdb->get_row("SELECT sid, parent_sid FROM $table_name WHERE sid = $parent_sid");

		$parent_sid = $parent->parent_sid;

		$recursive_sid = $parent->sid;

	}







	//list all the current tiles for the selected node

  $tile_list = '';

  $table_gw_lookup = $wpdb->prefix . "gw_lookup";

  $table_gw_tiles = $wpdb->prefix . "gw_tiles";

  $tiles = $wpdb->get_results("SELECT * FROM $table_gw_tiles INNER JOIN $table_gw_lookup ON $table_gw_lookup.tid = $table_gw_tiles.tid WHERE $table_gw_lookup.sid = $section ORDER BY  $table_gw_lookup.resource_order");

  if ($tiles) {

  

	  foreach ($tiles as $tile) {

	

		  $tile_list .= '<li id="gw_tile_'.$tile->tid.'" title="hello">	  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">

		  <tr>

			<td width="16%">'.$tile->tid.' - </td>

			<td width="74%">'.$tile->title.'</td>

			<td width="10%" align="right"><input name="default_tile" type="radio" value="'.$recursive_sid.'_'.$section.'_'.$tile->tid.'" /></td>

		  </tr>

		</table></li>';

	  }

  

  }



}

$table_name = $wpdb->prefix . "gw_sections";

$section = $wpdb->get_row("SELECT title FROM $table_name WHERE sid = $section");

$response = array(

   	 'tile_list'=>$tile_list,

   	 'section_heading'=>$section->title

);

	echo json_encode($response);

die;

}

//update the database with new tiles in a section

function store_resource_sort() {

if(!empty($_POST['ul'])) {

if($_POST['ul'] == 'gw_assigned_tiles_sort') {

	$section = $_POST['section'];

	global $wpdb;

	$table_name = $wpdb->prefix . "gw_lookup";

	$wpdb->query( 

		$wpdb->prepare( 

			"

			 DELETE FROM $table_name

			 WHERE sid = %d", $section

			)

	);

	parse_str($_POST['list'], $order);

	

	foreach ($order['gw_tile'] as $tid) {

		//insert new order into the DB

		$table_name = $wpdb->prefix . "gw_lookup";			

		$wpdb->insert($table_name, array('sid' => "$section", 'tid' => "$tid"));

		

	}

}

}

die;

}

//create resource manager admin section

function gw_admin_resources() {

global $wpdb;

$state = 'show_form';

if ($_POST) {//update options

	if ($_POST['form'] == 'add_resource') { //add new tile

		echo '<div id="message" class="updated fade"><p>Your new tile is shown below. You can add this tile to a book in the iCafe Library Book Manager</p></div>';

		$state = 'display';

		$title = $_POST['title'];

		$description = $_POST['description'];

		$media_type = $_POST['media_type'];

		$image_id = $_POST['tile_image_id'];

		$youtube_url = $_POST['youtube_url'];

		$embed_code = $_POST['embed_code'];

		$raw_code = $_POST['raw_code'];

		$links = $_POST['links'];



		



		//set blanks to 0 for mySQL 5 installations	not running in traditional mode

		if ($image_id == '') {

			$image_id = 0;

		}

		$table_name = $wpdb->prefix . "gw_tiles";			

		$wpdb->insert($table_name, array('title' => "$title", 'description' => "$description", 'media_type' => "$media_type", 'picture' => "$image_id", 'youtube_url' => "$youtube_url", 'embed_code' => "$embed_code", 'raw_code' => "$raw_code", 'links' => "$links"));

		$embed_code =  gw_safe_iframe($embed_code);

		$tid = $wpdb->insert_id;

		$edit_override = 'true';

		

		

		

		

		

	} else if ($_POST['form'] == 'edit_resource') { //edit existing tile

		echo '<div id="message" class="updated fade"><p>Your changes are shown below.</p></div>';

		$state = 'display';

		$title = $_POST['title'];

		$description = $_POST['description'];

		$media_type = $_POST['media_type'];

		$image_id = $_POST['tile_image_id'];

		$youtube_url = $_POST['youtube_url'];

		$embed_code = $_POST['embed_code'];

		$raw_code = $_POST['raw_code'];

		$links = $_POST['links'];

		$tid = $_POST['tid'];



		



		//set blanks to 0 for mySQL 5 installations	not running in traditional mode

		if ($image_id == '') {

			$image_id = 0;

		}

		$table_name = $wpdb->prefix . "gw_tiles";			

		$wpdb->UPDATE($table_name, array('title' => "$title", 'description' => "$description", 'media_type' => "$media_type", 'picture' => "$image_id", 'youtube_url' => "$youtube_url", 'embed_code' => "$embed_code", 'raw_code' => "$raw_code", 'links' => "$links"), array('tid' => "$tid"));

		$edit_override = 'true';

		$embed_code =  gw_safe_iframe(stripslashes($embed_code));

		$edit_override = 'true';

	}

	

}

if ($_GET['tid']) { //display the tile the user clicked on from the list of tile

if ($edit_override == 'true') { //tile was edited reset to dislay the tile...not the edit form

$state = 'display';

} else {

$state = $_GET['state'];

$tid = $_GET['tid'];

}

if ($state == 'delete') { //delete tile

echo '<div id="message" class="updated fade"><p>Tile deleted.</p></div>';

	$table_name = $wpdb->prefix . "gw_tiles";

	$wpdb->query( 

		$wpdb->prepare( 

			"

			 DELETE FROM $table_name

			 WHERE tid = %d", $tid

			)

	);

	$table_name = $wpdb->prefix . "gw_lookup";

	$wpdb->query( 

		$wpdb->prepare( 

			"

			 DELETE FROM $table_name

			 WHERE tid = %d", $tid

			)

	);

$state = 'show_form';

} else { //show the new, edited, or clicked on tile

if ($edit_override == 'true') {

	$state = 'display';

} else {

	$state = $_GET['state'];

}

}

}



 

$output = '

<h3>Create and Manage Resource Tiles</h3>

<div class="gw_tile_edit">';

if ($state == 'show_form') {//show the new tile screen

$output .= '

<form method="post" id="new_resource">

<div class="gw_tile">

<h2>Title: <input type="text" name="title" size="40"/></h2>

	<label for="description"><strong>Description</strong></label>

	<br />

	<textarea name="description" cols="80" rows="2"></textarea>

	<br />

	<br />

	<label for="media_type"><strong>Include Media</strong></label>

	<br />

	<div class="gw_media_buttons">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; None<br /><input name="media_type" type="radio" value="none" checked /></div>

	<div class="gw_media_buttons">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; YouTube<br /><input name="media_type" type="radio" value="youtube" /></div>

	<div class="gw_media_buttons">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Picture<br /><input name="media_type" type="radio" value="picture" /></div>

	<div class="gw_media_buttons">iFrame Embed Code<br /><input name="media_type" type="radio" value="embed" /></div>

	<div class="gw_media_buttons">Raw Code (advanced)<br /><input name="media_type" type="radio" value="raw" /></div>

	<div class="spacer" style="clear: both;"></div>

	<br />

	<div id="gw_media_youtube" class="media_type_entry">

	<label for="youtube_url"><strong>YouTube Video URL</strong></label>

	<br />

	<input type="text" name="youtube_url" size="50"/>

	<br />

	Use the full URL. Ex: <em>http://www.youtube.com/watch?v=fp8IatJUJDA</em>	

	</div>

	<div id="gw_media_embed" class="media_type_entry">

	<label for="embed_code"><strong>Content Embed Code</strong></label>

	<br />

	<textarea name="embed_code" cols="80" rows="3"></textarea>

	<br />

	&lt;iframe&gt; embed code from site (Prezi, Vimeo, SchoolTube, SlideShare, etc.) 

	<br />

	<em>You must embed content with a width of <strong>400px</strong> or less</em>	

	</div>

	<div id="gw_media_raw" class="media_type_entry">

	<label for="gw_raw"><strong>Raw Code</strong></label>

	<br />

	<textarea name="raw_code" cols="80" rows="5"></textarea>

	<br />

	Insert any code to render here.

	<br />

	<em><strong>Warning:</strong> Poorly formed code here could cause your library to fail</em>	

	</div>

	<div id="gw_media_picture" class="media_type_entry">

	<label for="embed_code"><strong>Select Picture</strong></label>

	<br />

	<div id="tile_add_pic_button">

	<br /><br />

	<input id="gw_tile_image_button" type="button" name="image" class="button" value="Upload/Select Picture" />

	<br />

	<em>You must select a picture with a width of <strong>400px</strong> or less</em>	

	 <input type="hidden" id="gw_tile_image_id" name="tile_image_id" />

	 </div>

	 <div id="tile_thumbnail_div">

	 Thumbnail Preview

	 <div id="tile_image_thumbnail">

	 <img width="200" id="gw_tile_image" src="" />  

	 </div>

	 </div>

	

	</div>

	<div class="spacer" style="clear: both;"></div>

	<br />

	<br />

	

	<label for="links"><strong>Additional Resource Links</strong></label>

	<br />

	<textarea name="links" cols="80" rows="5"></textarea>

	<br />

	Include links to documentation, how-to guides, external web sites, etc. 

	<br />

	List the links as <strong>Title, URL</strong> seperated by a comma.  Press return after each entry.<br />

	<em>Title, http://www.link.to.resource<br />

	Title, http://www.link.to.resource<br />

	Title, http://www.link.to.resource<br />...</em>



<div class="gw_buttons"><input type="submit" name="new_resource_save" value="Add Resource" /></div>

<div class="spacer" style="clear: both;"></div>

</div>



<input name="form" type="hidden" value="add_resource" />

</form>

';

} else if ($state == 'display') { //show the tile

	$output .= gw_make_tile('none', $tid, true);

} else if ($state == 'edit') {//edit existing tile

$table_name = $wpdb->prefix . "gw_tiles";			

$tile_data = $wpdb->get_row("SELECT * FROM $table_name WHERE tid = $tid");

$title = stripslashes($tile_data->title); 

$description = stripslashes($tile_data->description); 

$media_type = $tile_data->media_type; 

$picture = $tile_data->picture; 

$youtube_url = $tile_data->youtube_url; 

$embed_code_actual = stripslashes($tile_data->embed_code); 

$embed_code =  gw_safe_iframe($embed_code_actual);

$raw_code = stripslashes($tile_data->raw_code); 

$links = $tile_data->links; 

$none_checked = '';

$embed_checked = '';

$youtube_checked = '';

$picture_checked = '';

$raw_checked = '';

switch ($media_type) {

    case "none":

        $none_checked = 'checked';

        break;

	case "youtube":

        $youtube_checked = 'checked';

        break;    

    case "picture":

        $picture_checked = 'checked';

        break;

	case "embed":

        $embed_checked = 'checked';

        break;

	case "raw":

        $raw_checked = 'checked';

        break;

}

$output .= '

<form method="post" id="new_resource">

<div class="gw_tile">

	

	<h2>Title: <input type="text" name="title" size="40" value="'.$title.'"/></h2>

	

		<label for="description"><strong>Description</strong></label>

		<br />

		<textarea name="description" cols="80" rows="2">'.$description.'</textarea>

		<br />

		<br />

		<label for="media_type"><strong>Include Media</strong></label>

		<br />

		<div class="gw_media_buttons">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; None<br /><input name="media_type" type="radio" value="none" '.$none_checked.'/></div>

		<div class="gw_media_buttons">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; YouTube<br /><input name="media_type" type="radio" value="youtube" '.$youtube_checked.'/></div>

		<div class="gw_media_buttons">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Picture<br /><input name="media_type" type="radio" value="picture" '.$picture_checked.'/></div>

		<div class="gw_media_buttons">iFrame Embed Code<br /><input name="media_type" type="radio" value="embed" '.$embed_checked.'/></div>

		<div class="gw_media_buttons">Raw Code (advanced)<br /><input name="media_type" type="radio" value="raw" '.$raw_checked.'/></div>

		<div class="spacer" style="clear: both;"></div>

		<br />

		<div id="gw_media_youtube" class="media_type_entry">

		<label for="youtube_url"><strong>YouTube Video URL</strong></label>

		<br />

		<input type="text" name="youtube_url" size="50" value="'.$youtube_url.'"/>

		<br />

		Use the full URL. Ex: <em>http://www.youtube.com/watch?v=fp8IatJUJDA</em>	

		</div>

		<div id="gw_media_embed" class="media_type_entry">

		<label for="embed_code"><strong>Content Embed Code</strong></label>

		<br />

		<textarea name="embed_code" cols="80" rows="3">'.$embed_code_actual.'</textarea>

		<br />

		&lt;iframe&gt; embed code from site (Prezi, Vimeo, SchoolTube, SlideShare, etc.)

		<br />

		<em>You must embed content with a width of <strong>400px</strong> or less</em>	

		</div>

		<div id="gw_media_raw" class="media_type_entry">

		<label for="gw_raw"><strong>Raw Code</strong></label>

		<br />

		<textarea name="raw_code" cols="80" rows="5">'.$raw_code.'</textarea>

		<br />

		Insert any code to render here.

		<br />

		<em><strong>Warning:</strong> Poorly formed code here could cause your library to fail</em>	

		</div>

		<div id="gw_media_picture" class="media_type_entry">

		<label for="embed_code"><strong>Select Picture</strong></label>

		<br />

		<div id="tile_add_pic_button">

		<br /><br />

		<input id="gw_tile_image_button" type="button" name="image" class="button" value="Upload/Select Picture" />

		<br />

		<em>You must select a picture with a width of <strong>400px</strong> or less</em>	

		 <input type="hidden" id="gw_tile_image_id" name="tile_image_id"  value="'.$picture.'"/>

		 </div>

		 <div id="tile_thumbnail_div">

		 Thumbnail Preview

		 <div id="tile_image_thumbnail">

		  '.wp_get_attachment_image( $picture, 'thumbnail','' ,array( 'id' => 'gw_tile_image')).' 

		 </div>

		 </div>

		

		</div>

		<div class="spacer" style="clear: both;"></div>

			

		<br />

		<br />

		

		<label for="links"><strong>Additional Resource Links</strong></label>

		<br />

		<textarea name="links" cols="80" rows="5">'.$links.'</textarea>

		<br />

		Include links to documentation, how-to guides, external web sites, etc. 

		<br />

		List the links as <strong>Title, URL</strong> seperated by a comma. Press return after each entry.<br />

		<em>Title, http://www.link.to.resource<br />

		Title, http://www.link.to.resource<br />

		Title, http://www.link.to.resource<br />...</em>

	

	

	<div class="gw_buttons"><input type="submit" name="new_resource_save" value="Cancel" /><input type="submit" name="new_resource_save" value="Save" onclick="location.href=document.URL.split(\'?\')[0];"></div>

	<div class="spacer" style="clear: both;"></div>

</div>

<input name="tid" type="hidden" value="'.$tid.'" />

<input name="form" type="hidden" value="edit_resource" />

</form>

';	

}

//close the resource tile container

$output .= '

</div>

<div class="gw_tile_list">

<strong>Resource Tiles and ID #\'s</strong><br />

<a class="gw_add_tile" href="'.admin_url().'admin.php?page=iCafe-Library-Resources">Add New Tile</a>

';



//list all the existing tiles

$table_name = $wpdb->prefix . "gw_tiles";

$tiles = $wpdb->get_results("SELECT * FROM $table_name ORDER BY tid DESC");

if ($tiles) {

$output .= '<div id="gw_tile_list_ul"><ul>';

foreach ($tiles as $tile) {

$output .= '<li><a href="'.admin_url().'admin.php?page=iCafe-Library-Resources&state=display&tid='.$tile->tid.'" title="'.str_replace("\\", "", str_replace("\"", "&quot;", $tile->description)).'">'.$tile->tid.' - '.$tile->title.'</a></li>';

}

$output .= '</ul></div>';

}

$output .= '</div>

';

echo $output;



}



//create custom libraries

function gw_admin_custom_bookshelves() {

	

	global $wpdb;



	echo '

			<div class="wrap">

				<h2>iCafe Library Custom Bookshelves</h2>

				<br />

				After building a library of resources you can display a bookshelf with all your books on any WordPress page by using the shortcode <strong>[icafe-library]</strong><br /><br />

				If you wish to display a bookshelf with only certain selected books, simply select the desired books below and copy the custom shortcode to any WordPress page on your site. Tip - Books will be displayed in the order you select them.

				<br />

				<h3>Custom Bookshelf Shortcode</h3> 

				[icafe-library books="<span id="custom_shortcode"></span>"]

				

		';

	$output = '<div id="all_books">';

	$output .= '<ul id="gw_books">';

	$table_name = $wpdb->prefix . "gw_sections";

	$level1_nodes = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = 0 ORDER BY sid");

	if ($level1_nodes) {

		foreach ($level1_nodes as $level1_node) {

			$output .= gw_make_book_admin($level1_node->sid, $level1_node->title, $level1_node->detail, $level1_node->logo);				

		}

	}

	$output .= 		'</ul></div>';

	echo $output;

}





//create the top level books to select in the custom bookshelf admin page

function gw_make_book_admin($sid, $title, $detail, $image) {

 $section = '<a href="#" class="gw_book_tile custom_library_book" id="'.$sid.'"><li class="gw_book">

				<div class="gw_thumb">

					'.wp_get_attachment_image( $image, 'iCafe-Library-book').'

				</div>

				<h2 >'.$title.'</h2>

				<span>'.$detail.'</span>

			</li></a>';

return $section;



}

//create resource tile

function gw_make_tile($sid, $tid, $edit) {



//DB funtions

global $wpdb;

$table_name = $wpdb->prefix . "gw_tiles";			

$tile_data = $wpdb->get_row("SELECT * FROM $table_name WHERE tid = $tid");

$title = stripslashes($tile_data->title); 

$description = stripslashes($tile_data->description);

$media_type = $tile_data->media_type; 

$picture = $tile_data->picture; 

$youtube_url = $tile_data->youtube_url;

$embed_code_actual = stripslashes($tile_data->embed_code); 

$embed_code =  gw_safe_iframe($embed_code_actual);

$raw_code = stripslashes($tile_data->raw_code); 

$links = $tile_data->links; 

if (strlen($links) != 0) {

	$links_section = '<strong>Resource Links</strong>

		<ul>

			'.gw_convert_links($links).'

		</ul>';

}

$output = '

<div class="gw_tile" id="section_'.$sid.'_tile_'.$tid.'">

	<h2>'.$title.'</h2>

	<div class="gw_tile_description">'.$description.'</div>

	<div class="spacer" style="clear: both;"></div>

	'.gw_has_media($media_type, $picture, $embed_code, $youtube_url, $raw_code).'

	

	'.$links_section.'

	</div>

	<div class="spacer" style="clear: both;"></div>

</div>';

if ($edit) {

$output .= '

	<div class="gw_edit_tile_links"><a class="gw_edit_tile" href="'.admin_url().'admin.php?page=iCafe-Library-Resources&state=edit&tid='.$tid.'">Edit</a> | <a class="gw_delete_tile"href="'.admin_url().'admin.php?page=iCafe-Library-Resources&state=delete&tid='.$tid.'" onclick="return confirm(\'Are you sure you want to permenantly delete this resource tile?\');">Delete</a></div>';

}

return $output;

}





//change layout for video or not

function gw_has_media($media_type, $picture, $embed_code, $youtube_url, $raw_code) {

	if ($media_type == 'picture') {

		

		return '<div class="gw_tile_video">

			 '.wp_get_attachment_image($picture, 'full').' 

		</div>

		<div class="gw_tile_links gw_tile_links_narrow">';

		

	} else if ($media_type == 'embed') {

		return '<div class="gw_tile_video">

			'.$embed_code.'

		</div>

		<div class="gw_tile_links gw_tile_links_narrow">';

		

	} else if ($media_type == 'youtube') {

		return '<div class="gw_tile_video">

			'.gw_youtube_embed($youtube_url).'

		</div>

		<div class="gw_tile_links gw_tile_links_narrow">';

		

	} else if ($media_type == 'raw') {

		return '<div class="gw_tile_raw">

			'.$raw_code.'

		</div>

		<div class="gw_tile_links gw_tile_links_narrow">';

		

	} else {

		return '<div class="gw_tile_no_video"></div>

		<div class="gw_tile_links gw_tile_links_wide">';

	}

}

//convert YouTube URLs to iframe embed code

function gw_youtube_embed($youtube_url) {

	 list($url, $video_ID) = explode('=', $youtube_url, 2);

	 return '

	<iframe width="400" height="225" src="http://www.youtube.com/embed/'.$video_ID.'" frameborder="0" allowfullscreen></iframe>

	';

}



//ensure bad embed code doesn't break the layout

function gw_safe_iframe($embed_code) {

if (strlen($embed_code) == 0) {

 return '';

} else if (!strncmp($embed_code, '<iframe ', 8)) {

	if ((substr($embed_code, -9) === '</iframe>')) {

		return $embed_code;

	} else {

		return '<font color="red">Your video embed code was not correct. Your code should look similar to:</font> <br /> ' .htmlspecialchars('<iframe width="400" height="225" src="http://www.youtube.com/embed/12345abcde" frameborder="0" allowfullscreen></iframe>');

	}

} else {

	return '<font color="red">Your video embed code was not correct. Your code should look similar to: </font><br /> ' .htmlspecialchars('<iframe width="400" height="225" src="http://www.youtube.com/embed/12345abcde" frameborder="0" allowfullscreen></iframe>');

}

}

function gw_convert_links($links) { //convert the comma list of links and titles to real html links

$link = explode("\n", $links);

reset($link);

$link_list = '';

foreach($link as $li) {

 list($title, $url) = explode(",", $li);

 	$link_list .= '<li><a href="'.$url.'" target="_new">'.$title.'</a></li>';

}

return $link_list;

}

//output Groundwork Ajax calls

function gw_groundwork( $atts, $content = null ) {

   extract( shortcode_atts( array(

      'books' => null

      ), $atts ) );

add_filter( 'edit_post_link', '__return_false' );

ob_start(); // begin output buffering

$output ='';

$output .= '<div id="groundwork">';

$output .= gw_display($books);

$output .= '</div>';

echo $output;

$groundwork = ob_get_contents(); // end output buffering

    ob_end_clean(); // grab the buffer contents and empty the buffer

    return $groundwork;



   

}

//check if this is part of a restricted book

function gw_is_restricted() {

	global $wpdb;

	if ($_GET['book']) {//OK...we are not showing the first page

		$sid = $_GET['book'];

		$table_name = $wpdb->prefix . "gw_sections";

		$restricted = $wpdb->get_row("SELECT restricted, access_type FROM $table_name WHERE sid = $sid");

		if ($restricted->restricted == 1) { 

			if (!is_user_logged_in()) { 

				auth_redirect(); //force login

			} 

		}

	}

}



function gw_display($books) {

	global $current_user;

	//DB funtions

	global $wpdb;

	$output = '';

	$url = 'http';

	if ($_SERVER['HTTPS'] == 'on') {

	$url .= 's';

	}	

	$url .= '://';

	$url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$url = substr($url, 0, strpos($url, '?'));

		//check if we have any post requests

	if ($_GET['book']) {//OK...we are not showing the first page

	$sid = $_GET['book'];

	

	//check for restricted access

	$table_name = $wpdb->prefix . "gw_sections";

	$restricted = $wpdb->get_row("SELECT restricted, access_type FROM $table_name WHERE sid = $sid");

	

	$access = TRUE;

	

	if ($restricted->restricted == 1) { //is it a restricted book? Force login happens earlier in gw_is_restricted() but we need to check again before enforcing

		$access = FALSE;

			

		//check for restriction type

		$access_type_array = array_map('trim',explode(",",$restricted->access_type));

		$access_type = $access_type_array[0];

		

		if ($access_type == 'all') { //all logged in users can access

			$access = TRUE;

			

		} else if ($access_type == 'users') { //is it restricted by user?

			$user_ID = get_current_user_id();						

			

			if (in_array($user_ID, $access_type_array)) { //does this user have access

				$access = TRUE;	

			}

	

		} else if ($access_type == 'roles') { //is it restricted by role?

			$current_user = wp_get_current_user();

			$roles = $current_user->roles;

			

			if (in_array($roles[0], $access_type_array)) { //does this user have the correct role

				$access = TRUE;

			}

	

		}

		

		if (current_user_can('activate_plugins')) { //admins can always see books

			$access = TRUE;

		}

		

	}

	

	if ($access) {					

		$output .= '<div id="gw_back"><a href="'.$url.'">Back to Library</a></div><div id="gw_side_navigation">';

		$table_name = $wpdb->prefix . "gw_sections";

		

		//build level two

			$chapters = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = $sid ORDER BY section_order");

				if ($chapters) {

					$output .= '<div class="gw_accordion">'; 

					

					foreach ($chapters as $chapter) {//generate chapter navigation

						if ($chapter->hide == 1) {

							if (current_user_can('edit_post')) {

								$output .= gw_generate_headings($chapter->sid, $chapter->title, $chapter->detail);

								$output .= '<div>';

								$output .= gw_generate_sections($chapter->sid);

								//build level three

								$sections = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = $chapter->sid ORDER BY section_order");

								

								if ($sections) {

									$output .= '<div class="gw_accordion">';

									foreach ($sections as $section) { //generate section navigation

										if ($section->hide == 1) {

											if (current_user_can('edit_post')) {

												$output .= gw_generate_headings($section->sid, $section->title, $section->detail);

												$output .= '<div>'.gw_generate_sections($section->sid).'</div>';

											}

										} else {

											$output .= gw_generate_headings($section->sid, $section->title, $section->detail);

											$output .= '<div>'.gw_generate_sections($section->sid).'</div>';

										}

										

									}

									$output .= '</div>';

								}

							}

						} else {

							$output .= gw_generate_headings($chapter->sid, $chapter->title, $chapter->detail);

							$output .= '<div>';

							$output .= gw_generate_sections($chapter->sid);

							//build level three

							$sections = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = $chapter->sid ORDER BY section_order");

							

							if ($sections) {

								$output .= '<div class="gw_accordion">';

								foreach ($sections as $section) { //generate section navigation

									if ($section->hide == 1) {

										if (current_user_can('edit_post')) {

											$output .= gw_generate_headings($section->sid, $section->title, $section->detail);

											$output .= '<div>'.gw_generate_sections($section->sid).'</div>';

										}

									} else {

										$output .= gw_generate_headings($section->sid, $section->title, $section->detail);

										$output .= '<div>'.gw_generate_sections($section->sid).'</div>';

									}

									

								}

								$output .= '</div>';

							}

						}

						

						

						if ($chapter->hide == 1) {

							if (current_user_can('edit_post')) {

								$output .= '</div>';

							}

						} else {

							$output .= '</div>';

						}

					}

						$output .= '</div>';

				}

				$output .= '</div>';

			

			} else {

				$output .= '<div id="gw_back"><a href="'.$url.'">Back to Library</a></div><div id="gw_side_navigation">

				<h2>Restricted Resource</h2>

				The resource you’ve selected is only available to selected members of this site. If you believe this message is an error or to request access to this resource please contact the site owner.' ;

			}

				

				//Resource Tile Area	

				

				$output .= '<div id="gw_tile_stage">&nbsp;</div>';

									

		} else { //ok, we are just showing the bookshelf

			

			$output .= '<ul id="gw_books">';

			$table_name = $wpdb->prefix . "gw_sections";

			

			if (is_null($books)) {

				$level1_nodes = $wpdb->get_results("SELECT * FROM $table_name WHERE parent_sid = 0 ORDER BY section_order");

			} else {

				$level1_nodes = $wpdb->get_results("SELECT * FROM $table_name WHERE sid IN ($books) ORDER BY FIELD(sid,$books);");

			}

			

			

			if ($level1_nodes) {

				foreach ($level1_nodes as $level1_node) {

					if ($level1_node->hide == 1) {

						if (current_user_can('edit_post')) {

							$output .= gw_make_book($level1_node->sid, $level1_node->title, $level1_node->detail, $level1_node->logo, $level1_node->default_tile);	

						}

					} else {

						$output .= gw_make_book($level1_node->sid, $level1_node->title, $level1_node->detail, $level1_node->logo, $level1_node->default_tile);

					}

				}

			}

		

		$output .= 		'</ul>';

				

		}

	

	

	return $output;



}





//output the resource tiles for the selected section

function gw_resource_output() {

	//DB funtions

	global $wpdb;

	if ($_POST['sid']) {//What chapter/section are we displaying tiles for?

	$sid = $_POST['sid'];

	$output = '';

	//Need the book ID to check restrictions

	//find the parent book for this section

	$table_name = $wpdb->prefix . "gw_sections";

	$parent = $wpdb->get_row("SELECT sid, parent_sid FROM $table_name WHERE sid = $sid");

	$parent_sid = $parent->parent_sid;

	$recursive_sid = $parent->sid;	

	while ($parent_sid != 0) {

		$parent = $wpdb->get_row("SELECT sid, parent_sid FROM $table_name WHERE sid = $parent_sid");

		$parent_sid = $parent->parent_sid;

		$recursive_sid = $parent->sid;

	}

	

		

			//check for restricted access

			$table_name = $wpdb->prefix . "gw_sections";

			$restricted = $wpdb->get_row("SELECT restricted, access_type FROM $table_name WHERE sid = $recursive_sid");

			

			$access = 'TRUE';

			

			if ($restricted->restricted == 1) { //is it a restricted book? Force login happens earlier in gw_is_restricted() but we need to check again before enforcing

			

				$access = 'FALSE';

					

				//check for restriction type

				$access_type_array = array_map('trim',explode(",",$restricted->access_type));

				$access_type = $access_type_array[0];

				

				if ($access_type == 'all') { //all logged in users can access

					$access = 'TRUE';

					

				} else if ($access_type == 'users') { //is it restricted by user?

					$user_ID = get_current_user_id();						

					

					if (in_array($user_ID, $access_type_array)) { //does this user have access

						

						$access = 'TRUE';	

					}

			

				} else if ($access_type == 'roles') { //is it restricted by role?

					$current_user = wp_get_current_user();

					$roles = $current_user->roles;

					

					if (in_array($roles[0], $access_type_array)) { //does this user have the correct role

						$access = 'TRUE';

					}

			

				}

				

				if (current_user_can('activate_plugins')) { //admins can always see books

					$access = 'TRUE';

				}

			

			

		}



		if ($access == 'TRUE') {

				

			$table_name = $wpdb->prefix . "gw_lookup";

			$resources = $wpdb->get_results("SELECT tid FROM $table_name WHERE sid = $sid ORDER BY resource_order");

			if ($resources) {

				$output .= '<div id="gw_tile_container">';

				foreach ($resources as $resource) {

					$output .= gw_make_tile($sid, $resource->tid, false);

				}

				$output .= '<div id="gw_tile_spacer">&nbsp;</div></div>';

			} 

		}

		echo $output;

		die();	

	}

}

//create the top level books

function gw_make_book($sid, $title, $detail, $image, $default_tile) {

 $section = '<a href="?book='.$sid.'&book_title='.$title.$default_tile.'" class="gw_book_tile"><li class="gw_book">

				<div class="gw_thumb">

					'.wp_get_attachment_image( $image, 'iCafe-Library-book').'

				</div>

				<h2 >'.$title.'</h2>

				<span>'.$detail.'</span>

			</li></a>';

return $section;



}

//create the chapters (accordian menu)

function gw_generate_headings($sid, $title, $detail) {

return '<div><a class="gw_chapter_menu_link" href="#'.$sid.'" alt="'.$detail.'">'.$title.'</a></div>';

}

//create the sections (sub chapter links)

function gw_generate_sections($sid) {

$current_section = $sid;

$output = '';

global $wpdb;

		

$table_gw_lookup = $wpdb->prefix . "gw_lookup";

$table_gw_tiles = $wpdb->prefix . "gw_tiles";

$resources = $wpdb->get_results("SELECT $table_gw_tiles.tid, $table_gw_tiles.title, $table_gw_tiles.description FROM $table_gw_tiles INNER JOIN $table_gw_lookup ON $table_gw_lookup.tid = $table_gw_tiles.tid WHERE $table_gw_lookup.sid = $current_section ORDER BY  $table_gw_lookup.resource_order");





if ($resources) {

	$output .= '<ul class="gw_menu_list">';

	foreach ($resources as $resource) {

		$output .= '<li class="gw_menu_tile_link"><a class="section_'.$sid.'_tile_'.$resource->tid.' "href="#section_'.$sid.'_tile_'.$resource->tid.'">'.$resource->title.'</a></li>';

	}

	$output .= '</ul>';

}



return $output;

}

?>