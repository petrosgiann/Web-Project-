// Custom icon for markers
var myIcon = L.icon({
    iconUrl: 'images/requestmarker.png',
    iconSize: [40, 40]
  });
  
  // Initialize map
  var map = L.map('map').setView([38.246639, 21.734573], 15);


  // OSM tile layer
var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
  


  
  var marker;

  // Function to handle map click event
  function onMapClick(e) {
    // Remove the existing marker if any
    if (marker) {
      map.removeLayer(marker);
    }

    // Create a marker at the clicked location
    marker = L.marker(e.latlng).addTo(map);


    document.getElementById("latitude").value = e.latlng.lat;
    document.getElementById("longitude").value = e.latlng.lng;

    // You can access latitude and longitude using e.latlng
    console.log("Latitude: " + e.latlng.lat + ", Longitude: " + e.latlng.lng);
  }

  // Add a click event listener to the map
  map.on("click", onMapClick);

  // Function to validate the form before submission
  function validateForm() {
    if (!marker) {
      alert("Please click on the map to provide your location.");
      return false;
    }

    // Add additional form validation logic if needed

    return true;
  }

  // Add a submit event listener to the form
  document.querySelector("form").addEventListener("submit", function (event) {
    // Validate the form before submission
    if (!validateForm()) {
      event.preventDefault(); // Prevent form submission if validation fails
    }
  });

