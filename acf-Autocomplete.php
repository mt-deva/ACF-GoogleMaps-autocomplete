<?php

/*
Plugin Name: Advanced Custom Fields: Autocomplete
Description: Adds Google Autocomplete field without maps
Version: 1.0.0
Author: Callam Williams
*/

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('LQ_acf_plugin_Autocomplete') ) :

class LQ_acf_plugin_Autocomplete {
	var $settings;

	function __construct() {

		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ ),
			'enqueue_google_maps'		=> true,
		);

		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
	}

	function include_field( $version = false ) {
		include_once(plugin_dir_path( __FILE__ ) . 'fields/class-LQ-acf-field-Autocomplete.php');
	}

}

new LQ_acf_plugin_Autocomplete();

endif;

?>
