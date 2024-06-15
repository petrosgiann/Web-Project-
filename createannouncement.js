document.addEventListener("DOMContentLoaded", function () {
    loadCategories();

    document.getElementById("categoryDropdown").addEventListener("change", function () {
      var categoryId = this.value;
      if (categoryId !== "") {
        loadProducts(categoryId);
      } else {
        clearProductsTable();
      }
    });
  });

function loadCategories() {
    fetch("managmentcat.php") // Update the URL to your server-side script
      .then(response => response.json())
      .then(data => {
        populateCategoriesDropdown(data.categories);
      })
      .catch(error => console.error('Error loading categories:', error));
  }

function populateCategoriesDropdown(categories) {
    var dropdown = document.getElementById("categoryDropdown");

    dropdown.innerHTML = '<option value="">Select a category</option>';

    categories.forEach(category => {
        var option = document.createElement("option");
        option.value = category.category_id;
        option.text = category.category_name;
        dropdown.appendChild(option);
    });


}function loadProducts(categoryId) {
    fetch(`managment.php?category=${categoryId}`) 
        .then(response => response.json())
        .then(data => {
            displayProductsByCategory(data.items);
        })
        .catch(error => console.error('Error loading products:', error));
}


function displayProductsByCategory(products) {
    var productTableBody = document.getElementById("productTableBody");

    // Clear previous table data
    productTableBody.innerHTML = '';

   
    if (products.length === 0) {
        var noProductsRow = productTableBody.insertRow();
        var noProductsCell = noProductsRow.insertCell(0);
        noProductsCell.colSpan = 3;  // Span across all columns
        noProductsCell.innerHTML = "Δεν υπάρχουν διαθέσιμα προϊόντα για αυτή την κατηγορία";
    } else {
    products.forEach(product => {
        var row = productTableBody.insertRow();
        var cellName = row.insertCell(0);
        var cellAddQuantity = row.insertCell(1);

        cellName.innerHTML = product.product_name;

       
        var quantityInput = document.createElement("input");
        quantityInput.type = "number";
        quantityInput.min = "0";
        quantityInput.value = "0";
        cellAddQuantity.appendChild(quantityInput);

       
        var updateButton = document.createElement("button");
        updateButton.innerHTML = "Select";
        updateButton.addEventListener("click", function () {
            var addQuantity = parseInt(quantityInput.value);
            if (!isNaN(addQuantity) && addQuantity >= 0) {
                makeNewAnnouncement(product.product_id,  addQuantity);
            }
        });
        cellAddQuantity.appendChild(updateButton);
    
    });
}
}


function makeNewAnnouncement(productId, addedQuantity) {
    fetch(`createannouncement.php?product=${productId}&quantity=${addedQuantity}`)
    .then(response => response.json())
    .then(data => {
        // Handle the response if needed
        console.log(data.message);
        alert(data.message);
    
    });
   
    
}