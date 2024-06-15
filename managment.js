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
}

function loadProducts(categoryId) {
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
        noProductsCell.colSpan = 4;  // Span across all columns
        noProductsCell.innerHTML = "Δεν υπάρχουν διαθέσιμα προϊόντα για αυτή την κατηγορία";
    } else {
    products.forEach(product => {
        var row = productTableBody.insertRow();
        var cellName = row.insertCell(0);
        var cellQuantity = row.insertCell(1);
        var cellAddQuantity = row.insertCell(2);
        var cellActions = row.insertCell(3);

        cellName.innerHTML = product.product_name;
        cellQuantity.innerHTML = product.quantity_available;

       
        var quantityInput = document.createElement("input");
        quantityInput.type = "number";
        quantityInput.min = "0";
        quantityInput.value = "0";
        cellAddQuantity.appendChild(quantityInput);

       
        var updateButton = document.createElement("button");
        updateButton.innerHTML = "Update";
        updateButton.addEventListener("click", function () {
            var addedQuantity = parseInt(quantityInput.value);
            if (!isNaN(addedQuantity) && addedQuantity >= 0) {

                var currentQuantity = parseInt(cellQuantity.innerHTML) || 0;
                cellQuantity.innerHTML = currentQuantity + addedQuantity;
                updateQuantityInDatabase(product.product_id, currentQuantity + addedQuantity);
            }
        });
        cellAddQuantity.appendChild(updateButton);

  
        var deleteButton = document.createElement("button");
        deleteButton.innerHTML = "Delete";
        deleteButton.addEventListener("click", function () {
            deleteProductFromDatabase(product.product_id);
        });
        cellActions.appendChild(deleteButton);

    
    });
}
}

function updateQuantityInDatabase(productId, addedQuantity) {
    fetch(`updateQuantity.php?product=${productId}&quantity=${addedQuantity}`)
    .then(response => response.json())
    .then(data => {
        // Handle the response if needed
        console.log(data.message);
    })
    .catch(error => console.error('Error updating quantity:', error));
    
}

function clearProductsTable() {
    var productTableBody = document.getElementById("productTableBody");
    productTableBody.innerHTML = '';
}


function deleteProductFromDatabase(productId) {
    // Confirm with the user before deleting the product
    var confirmation = confirm("Are you sure you want to delete this product?");
    
    if (confirmation) {
        fetch(`deleteProduct.php?product=${productId}`)
            .then(response => response.json())
            .then(data => {
                // Handle the response if needed
                console.log(data.message);
                
                // Optionally, you can reload the products after deletion
                var categoryId = document.getElementById("categoryDropdown").value;
                if (categoryId !== "") {
                    loadProducts(categoryId);
                }
            })
            .catch(error => console.error('Error deleting product:', error));
    }
}


function addProduct() {
    // Get values from input fields
    var productName = document.getElementById("productName").value;
    var quantity = parseInt(document.getElementById("quantity").value);
    var categoryId = document.getElementById("categoryDropdown").value;

    if (!categoryId) {
        alert("Please select a category.");
        return;
    }

    // Validate inputs
    if (!productName || isNaN(quantity) || quantity < 0) {
        alert("Please enter valid values.");
        return;
    }

    // Prepare data for sending to the server
    var data = {
        category_id: categoryId,
        product_name: productName,
        quantity_available: quantity
    };

    // Send the data to the server using fetch or another method
    fetch('addProduct.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(result => {
     
        console.log(result.message);
    })
    .catch(error => {
        console.error('Error adding product:', error);
    });

    loadProducts(categoryId);
}









