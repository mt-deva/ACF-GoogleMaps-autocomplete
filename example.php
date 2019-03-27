<?

/**
 * map.php
 */

$location = get_field('your_map_acf_field');

?>

<div id="google-map"
  data-address="<?php echo $location['address'] ?>"
  data-lat="<?php echo $location['lat'] ?>"
  data-lng="<?php echo $location['lng'] ?>">
</div>

<?
/**
 * map.css
 */
?>

<style>
  #google-map {
      margin: 0 auto;
      width: 600px;
      height: 600px;
  }


  input {
    display:block;
    width: 100%;
    max-width: 600px;
    height:30px;
    margin: 20px auto;
  }
</style>

<?
/**
 * map.js
 */
?>

<script>
  const map = document.getElementById("google-map");

	function initialize() {
		let latitude = map.dataset.lat;
		let longitude = map.dataset.lng;
    let center = new google.maps.LatLng(latitude, longitude);

		const mapOptions = {
			center: center,
			zoom: 10,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		const map = new google.maps.Map(
			map,
			mapOptions
		);
	}

	initialize();
</script>
