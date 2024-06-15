function loadProducts() {
    // Συλλογή δεδομένων από τη φόρμα
    var formData = new FormData(document.getElementById("loadProductsForm"));
  
    // Αποστολή δεδομένων με χρήση AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "updateinventory.php", true);
  
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Εμφανίστε ή επεξεργαστείτε τυχόν απάντηση από τον εξυπηρετητή
        console.log(xhr.responseText);
        location.reload();
       
      }
    };
  
    xhr.send(formData);
  }

  function unloadProducts() {
    // Συλλογή δεδομένων από τη φόρμα
    var formDataa = new FormData(document.getElementById("unloadProductsForm"));
  
    // Αποστολή δεδομένων με χρήση AJAX
    var xhrr = new XMLHttpRequest();
    xhrr.open("POST", "updateproducts.php", true);
  
    xhrr.onreadystatechange = function() {
      if (xhrr.readyState == 4 && xhrr.status == 200) {
        // Εμφανίστε ή επεξεργαστείτε τυχόν απάντηση από τον εξυπηρετητή
        console.log(xhrr.responseText);
        location.reload();
     
      }
    };
  
    xhrr.send(formDataa);
  }