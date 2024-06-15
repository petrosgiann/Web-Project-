// Custom icons for markers
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

// OSM tile layer
var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Fetch marker positions from the PHP script
fetch('adminmap.php')
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


                    fetch(`adminmap_car.php?latitude=${position.lat}&longitude=${position.lng}`)
                        .then(response => response.json())
                        .then(carData => {
                            // Log the retrieved request data for debugging
                            console.log('car Data:', carData);

                            // Create Pop-up content based on the fetched data
                                var popupContent = "Username: " + carData.username +
                                    "<br>Προιόντα: " + carData.product_name +
                                    "<br>Ποσότητα: " + carData.quantity;

                                // Create and bind Pop-up to the marker
                                L.marker([position.lat, position.lng], { icon: icon })
                                    .bindPopup(popupContent)
                                    .addTo(map);
                           
                        });
                       // .catch(error => console.error('Error fetching car data:', error));
                    break;
                case 'offer':
                    icon = offerIcon;


                    fetch(`adminmap_offer.php?latitude=${position.lat}&longitude=${position.lng}`)
                    .then(response => response.json())
                    .then(offerData => {
                        // Log the retrieved request data for debugging
                        console.log('offer Data:', offerData);

                        // Create Pop-up content based on the fetched data
                        var popupContent = "Ονοματεπώνυμο: " + offerData.fullname +
                                           "<br>Τηλέφωνο: " + offerData.phone +
                                           "<br>Ημερομηνία Καταχώρησης: " + offerData.date_submitted +
                                            "<br>Είδος: " + offerData.product_name +
                                            "<br>Πoσότητα που προσφέρεται: " + offerData.quantity ;
                                               //"<br>Ημερομηνία Υποβολής Task: " + requestData.task_date_submitted;

                        // Create and bind Pop-up to the marker
                        L.marker([position.lat, position.lng], { icon: icon })
                            .bindPopup(popupContent)
                            .addTo(map);
                    })
                    .catch(error => console.error('Error fetching request data:', error));
                    break;
                case 'request':
                    icon = requestIcon;

                    // Additional query for 'request' type
                    fetch(`adminmap_request.php?latitude=${position.lat}&longitude=${position.lng}`)
                        .then(response => response.json())
                        .then(requestData => {
                            // Log the retrieved request data for debugging
                            console.log('Request Data:', requestData);

                            // Create Pop-up content based on the fetched data
                            var popupContent = "Ονοματεπώνυμο: " + requestData.fullname +
                                               "<br>Τηλέφωνο: " + requestData.phone +
                                               "<br>Ημερομηνία Καταχώρησης: " + requestData.date_submitted +
                                                "<br>Είδος: " + requestData.product_name +
                                                "<br>Πληθυσμός : " + requestData.num_people_affected ;
                                                   //"<br>Ημερομηνία Υποβολής Task: " + requestData.task_date_submitted;

                            // Create and bind Pop-up to the marker
                            L.marker([position.lat, position.lng], { icon: icon })
                                .bindPopup(popupContent)
                                .addTo(map);
                        })
                        .catch(error => console.error('Error fetching request data:', error));
                    break;
                    case 'selected_offer':
                        icon = selected_offerIcon;
                        fetch(`adminmap_selectedoffer.php?latitude=${position.lat}&longitude=${position.lng}`)
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
                    [selectedofferData.rescuer_latitude, selectedofferData.rescuer_longitude],  // Rescuer position
                    [position.lat, position.lng]  // Offer position
                ], { color: 'green' }).addTo(map);
    

            console.log('Selected Offer Marker Created!');

            
        })
        .catch(error => console.error('Error fetching selected offer data:', error));
break;



case 'selected_request':
    icon = selected_requestIcon;
    fetch(`adminmap_selectedrequest.php?latitude=${position.lat}&longitude=${position.lng}`)
        .then(response => response.json())
        .then(selectedrequestData => {
            console.log('Selected Requests Data:', selectedrequestData);

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
                    [selectedrequestData.rescuer_latitude, selectedrequestData.rescuer_longitude],  // Car position
                    [position.lat, position.lng]  // Request position
                ], { color: 'yellow' }).addTo(map);

            console.log('Selected request Marker Created!');
        })
        .catch(error => console.error('Error fetching selected offer data:', error));
break;
            }

        });
    })
    .catch(error => console.error('Error fetching marker positions:', error));


   