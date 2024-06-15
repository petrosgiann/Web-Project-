var carIcon = L.icon({
    iconUrl: 'images/car.png',
    iconSize: [40, 40]
});

var offerIcon = L.icon({
    iconUrl: 'images/offersmarker.png', // Replace with the actual path
    iconSize: [40, 40]
});

var requestIcon = L.icon({
    iconUrl: 'images/requestmarker.png', // Replace with the actual path
    iconSize: [40, 40]
});

var selected_requestIcon = L.icon({
    iconUrl: 'images/selected_requestmarker.png', // Replace with the actual path
    iconSize: [40, 40]
});


var selected_offerIcon = L.icon({
    iconUrl: 'images/selected_offersmarker.png', // Replace with the actual path
    iconSize: [40, 40]
});


// Initialize map
var map = L.map('map').setView([38.246639, 21.734573], 15);
var carLat, carLng; 
var polylines = [];

// OSM tile layer
var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Fetch marker positions from the PHP script
fetch('rescuermap.php')
    .then(response => response.json())
    .then(markerPositions => {
        // Log the retrieved positions for debugging
        console.log('Marker Positions:', markerPositions);

        // Create markers based on the fetched positions
        markerPositions.forEach(position => {
            // Choose icon based on marker type
            var icon;
            switch (position.type) {
                case 'car':
                    icon = carIcon;
                  var carMarker = L.marker([position.lat, position.lng], { icon: icon }).addTo(map);
                   carLat = carMarker.getLatLng().lat;
                   carLng = carMarker.getLatLng().lng;
              
                   
                    break;
                    case 'offer':
                        icon = offerIcon;
                        fetch(`adminmap_offer.php?latitude=${position.lat}&longitude=${position.lng}`)
                            .then(response => response.json())
                            .then(offerData => {
                                console.log('Offer Data:', offerData);
                    
                                // Create popup content
                                var popupContent = "Ονοματεπώνυμο: " + offerData.fullname +
                                    "<br>Τηλέφωνο: " + offerData.phone +
                                    "<br>Ημερομηνία Καταχώρησης: " + offerData.date_submitted +
                                    "<br>Είδος: " + offerData.product_name +
                                    "<br>Πoσότητα που προσφέρεται: " + offerData.quantity +
                                    "<br><button id='offerButton'>Select</button></div>";
                    
                                // Create marker
                                var marker = L.marker([position.lat, position.lng], { icon: icon })
                                    .bindPopup(popupContent)
                                    .addTo(map);
                    
                                // Attach event listener to the button
                                marker.on('popupopen', function () {
                                    document.querySelector('#offerButton').addEventListener('click', function () {
                                        console.log('Offer Button Clicked!');
                                        location.reload();
                    
                                        // Create polyline
                                        var polyline = L.polyline([
                                            [carLat, carLng],  // Car position
                                            [position.lat, position.lng]  // Offer position
                                        ], { color: 'blue' }).addTo(map);
                    
                                        // Store the polyline in the array for future reference
                                        polylines.push(polyline);
                    
                                        // Perform insert operation for offers
                                        fetch(`select_offer.php?latitude=${position.lat}&longitude=${position.lng}`, {
                                            method: 'POST',
                                            body: JSON.stringify({ offerData }),
                                            headers: {
                                                'Content-Type': 'application/json',
                                            },
                                        })
                                            .then(response => response.json())
                                            .then(data => console.log('Insert result:', data));
                                            //.catch(error => console.error('Error inserting offer:', error));
                    
                                    });
                                });
                            })
                            .catch(error => console.error('Error fetching offer data:', error));
                    break;
                    
                case 'request':
                    icon = requestIcon;
                    fetch(`adminmap_request.php?latitude=${position.lat}&longitude=${position.lng}`)
                        .then(response => response.json())
                        .then(requestData => {
                            console.log('Request Data:', requestData);
                            var popupContent = "Ονοματεπώνυμο: " + requestData.fullname +
                                               "<br>Τηλέφωνο: " + requestData.phone +
                                               "<br>Ημερομηνία Καταχώρησης: " + requestData.date_submitted +
                                               "<br>Είδος: " + requestData.product_name +
                                               "<br>Πληθυσμός : " + requestData.num_people_affected +
                                               "<br><button id='requestButton'>Select</button></div>";

                            var marker = L.marker([position.lat, position.lng], { icon: icon })
                                .bindPopup(popupContent)
                                .addTo(map);

                                
                            marker.on('popupopen', function () {
                                document.querySelector('#requestButton').addEventListener('click', function () {
                                    console.log('Request Button Clicked!');
                                    location.reload();

                                    var polyline = L.polyline([
                                        [carLat, carLng],  // Car position
                                        [position.lat, position.lng]  // Offer position
                                    ], { color: 'green' }).addTo(map);
                                    
                                    // Perform insert operation for offers
                                      
                                      fetch(`select_request.php?latitude=${position.lat}&longitude=${position.lng}`, {
                                        method: 'POST',
                                        body: JSON.stringify({ requestData }),
                                        headers: {
                                          'Content-Type': 'application/json',
                                        },
                                      })
                                        .then(response => response.json())
                                        .then(data => console.log('Insert result:', data))
                                        .catch(error => console.error('Error inserting offer:', error));
                                      
                                });
                            });

                        })
                        .catch(error => console.error('Error fetching request data:', error));
                    break;



                    case 'selected_offer':
                        icon = selected_offerIcon;
                        fetch(`requestmap_selectedoffer.php?latitude=${position.lat}&longitude=${position.lng}`)
                            .then(response => response.json())
                            .then(selectedofferData => {
                                console.log('Selected Offer Data:', selectedofferData);
                    
                                // Create popup content
                                var popupContent = "Ονοματεπώνυμο: " + selectedofferData.fullname +
                                    "<br>Τηλέφωνο: " + selectedofferData.phone +
                                    "<br>Ημερομηνία Καταχώρησης: " + selectedofferData.date_submitted +
                                    "<br>Είδος: " + selectedofferData.product_name +
                                    "<br>Πoσότητα που προσφέρεται: " + selectedofferData.quantity +
                                    "<br>Ημερομηνία Υποβολής Εργασίας: " + selectedofferData.task_date_submitted +
                                    "<br>Ονοματεπώνυμο Διασώστη: " + selectedofferData.rescuer_fullname;
                   
                    

            // Create marker
            var selectedOfferMarker = L.marker([position.lat, position.lng], { icon: icon })
                .bindPopup(popupContent)
                .addTo(map);


                var polyline = L.polyline([
                    [carLat, carLng],  // Car position
                    [position.lat, position.lng]  // Offer position
                ], { color: 'green' }).addTo(map);

            console.log('Selected Offer Marker Created!');
        })
        .catch(error => console.error('Error fetching selected offer data:', error));
break;



case 'selected_request':
    icon = selected_requestIcon;
    fetch(`requestmap_selectedrequest.php?latitude=${position.lat}&longitude=${position.lng}`)
        .then(response => response.json())
        .then(selectedrequestData => {
            console.log('Selected Offer Data:', selectedrequestData);

            // Create popup content
            var popupContent = "Ονοματεπώνυμο: " + selectedrequestData.fullname +
                "<br>Τηλέφωνο: " + selectedrequestData.phone +
                "<br>Ημερομηνία Καταχώρησης: " + selectedrequestData.date_submitted +
                "<br>Είδος: " + selectedrequestData.product_name +
                "<br>Πληθυσμός : " + selectedrequestData.num_people_affected +
                "<br>Ημερομηνία Υποβολής Εργασίας: " + selectedrequestData.task_date_submitted +
                "<br>Ονοματεπώνυμο Διασώστη: " + selectedrequestData.rescuer_fullname;

            // Create marker
            var selectedrequestMarker = L.marker([position.lat, position.lng], { icon: icon })
                .bindPopup(popupContent)
                .addTo(map);


                var polyline = L.polyline([
                    [carLat, carLng],  // Car position
                    [position.lat, position.lng]  // Offer position
                ], { color: 'yellow' }).addTo(map);

            console.log('Selected request Marker Created!');
        })
        .catch(error => console.error('Error fetching selected offer data:', error));
break;
                 
            }
        });
    })
    .catch(error => console.error('Error fetching marker positions:', error));




