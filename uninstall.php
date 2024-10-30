<?php

if (!defined('WP_UNINSTALL_PLUGIN'))
	exit();

//Remove all options and tables
global $wpdb;

    $table = $wpdb->prefix . "gw_sections";
	$wpdb->query("DROP TABLE IF EXISTS $table");

	$table = $wpdb->prefix . "gw_tiles";
	$wpdb->query("DROP TABLE IF EXISTS $table");

	$table = $wpdb->prefix . "gw_lookup";
	$wpdb->query("DROP TABLE IF EXISTS $table");

	delete_option("icafe_library");

?>