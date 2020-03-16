document.autocompleteField = function() {
  // Has user pressed the down key to navigate autocomplete options?
  let hasDownBeenPressed = false;

  const inputHandling = function(searchInput) {
    // options
    const options = {
      componentRestrictions: {}
    };
    // Google Maps Autocomplete Method
    const autocomplete = new google.maps.places.Autocomplete(searchInput, options);
    google.maps.event.trigger(autocomplete, 'place_changed');
    // Default listener outside to stop nested loop returning odd results
    searchInput.addEventListener('keydown', e => {
      if (e.keyCode === 40) {
        hasDownBeenPressed = true;
      }
    });

    // GoogleMaps API custom eventlistener method
    google.maps.event.addDomListener(searchInput, 'keydown', e => {
      // Maps API e.stopPropagation();
      e.cancelBubble = true;

      // If enter key, or tab key
      if (e.keyCode === 13 || e.keyCode === 9) {
        // If user isn't navigating using arrows and this hasn't ran yet
        if (!hasDownBeenPressed && !e.hasRanOnce) {
          e.preventDefault();
          google.maps.event.trigger(e.target, 'keydown', {
            keyCode: 40,
            hasRanOnce: true
          });
        }
      }
    });

    // Clear the input on focus, reset hasDownBeenPressed
    searchInput.addEventListener('focus', () => {
      hasDownBeenPressed = false;
      searchInput.value = '';
    });

    // place_changed GoogleMaps listener when we do submit
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      // Get the place info from the autocomplete Api
      const place = autocomplete.getPlace();

      //If we can find the place lets go to it
      if (typeof place.address_components !== 'undefined') {
        // reset hasDownBeenPressed in case they don't unfocus
        hasDownBeenPressed = false;
        // 🤢 😷
        const hiddenInputs = Array.from(searchInput.parentNode.parentNode.children.item(0).children);
        hiddenInputs.forEach(function(input) {
          switch (input.dataset.name) {
            case 'address':
              input.value = place.formatted_address;
              break;
            case 'lat':
              input.value = place.geometry.location.lat();
              break;
            case 'lng':
              input.value = place.geometry.location.lng();
              break;
          }
        });
      }
    });
  };

  // search input

  const readyExistingFields = function() {
    const searchInput = Array.from(document.querySelectorAll('input[data-maps-autocomplete]'));
    searchInput.forEach(input => {
      inputHandling(input);
    });
  };

  const readyNewFields = function(input) {
    inputHandling(input);
  };

  if (typeof acf.add_action !== 'undefined') {
    acf.add_action('ready', readyExistingFields);
    acf.add_action('append', function(parent) {
      var input = parent[0].querySelector('.search');
      if (!input) return;
      readyNewFields(input);
    });
  }
};
