<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;
if( !class_exists('ACF_acf_field_Autocomplete') ) :

class ACF_acf_field_Autocomplete extends acf_field {

	function __construct( $settings ) {

		$this->name = 'autocomplete';
		$this->label = __("Google Autocomplete",'acf');
		$this->category = 'jquery';
		$this->defaults = array(
			'center_lat'	=> '',
			'center_lng'	=> ''
		);

		$this->settings = $settings;
			// do not delete!
    	parent::__construct();
	}

	function input_admin_enqueue_scripts() {

		// localize
		acf_localize_text(array(
		  'Sorry, this browser does not support geolocation'	=> __('Sorry, this browser does not support geolocation', 'acf'),
	  ));

	  if( !acf_get_setting('enqueue_google_maps') ) {
		 	return;
		 }

		$url = $this->settings['url'];
		$version = $this->settings['version'];

		wp_register_script('autocomplete', "{$url}assets/js/input.js", $version);
		wp_enqueue_script('autocomplete');

	  $api = array(
		'key'		=> acf_get_setting('google_api_key'),
		'client'	=> acf_get_setting('google_api_client'),
		'libraries'	=> 'places',
		'ver'		=> 3,
		'callback'	=> 'document.autocompleteField'
	  );

	  $api = apply_filters('acf/fields/google_map/api', $api);

	  if( empty($api['key']) ) unset($api['key']);
	  if( empty($api['client']) ) unset($api['client']);

		$api_path = add_query_arg($api, 'https://maps.googleapis.com/maps/api/js');

		wp_register_script( 'google_maps', $api_path, false, '3' );
		wp_enqueue_script( 'google_maps' );

	}

	function render_field( $field ) {
		// validate value
		if( empty($field['value']) ) {
			$field['value'] = array();
		}

		//var_dump($field);

		$field['value'] = wp_parse_args($field['value'], array(
			'address'	=> '',
			'lat'		=> '',
			'lng'		=> ''
		));

		$atts = array(
			'id'			=> $field['id'],
			'class'			=> "acf-google-map {$field['class']}",
			'data-lat'		=> $field['center_lat'],
			'data-lng'		=> $field['center_lng'],
		);

		if( $field['value']['address'] ) {
			$atts['class'] .= ' -value';
		}

?>

<div <?php acf_esc_attr_e($atts); ?>>

	<div class="acf-hidden">
		<?php foreach( $field['value'] as $k => $v ):
			acf_hidden_input(array( 'name' => $field['name'].'['.$k.']', 'value' => $v, 'data-name' => $k ));
		endforeach; ?>
	</div>

	<div class="title" style="border:none">
		<input
		data-maps-autocomplete
		class="search"
		type="text"
		placeholder="<?php _e("Search for address...",'acf'); ?>"
		value="<?php echo esc_attr($field['value']['address']); ?>"
		/>
	</div>

</div>
<?php

	}

	function validate_value( $valid, $value, $field, $input ){
		if( ! $field['required'] ) {
			return $valid;
		}

		if( empty($value) || empty($value['lat']) || empty($value['lng']) ) {
			return false;
		}

		return $valid;
	}

	function update_value( $value, $post_id, $field ) {
		if( empty($value) || empty($value['lat']) || empty($value['lng']) ) {
			return false;
		}

		return $value;
	}
}

new ACF_acf_field_Autocomplete( $this->settings );

endif;

?>
