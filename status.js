function sendSelectedCategories() {
    // Συλλογή δεδομένων από τη φόρμα
    var formData = new FormData(document.getElementById("SelectedCategories"));
  
    // Αποστολή δεδομένων με χρήση AJAX
    var xhrr = new XMLHttpRequest();
    xhrr.open("POST", "status.php", true);
  
    xhrr.onreadystatechange = function() {
        if (xhrr.readyState == 4 && xhrr.status == 200) {
          console.log(xhrr.responseText);
        }
      };
      
  
    xhrr.send(formData);
  }
