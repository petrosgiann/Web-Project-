const axios = require('axios');
const fs = require('fs');

const url = 'http://usidas.ceid.upatras.gr/web/2023/export.php';

// Κάνουμε GET αίτηση στο URL
axios.get(url)
  .then(response => {
    const data = response.data;

    // Αποθηκεύουμε τα δεδομένα σε ένα αρχείο JSON
    fs.writeFileSync('productsUpload.json', JSON.stringify(data, null, 2));

    console.log('Τα δεδομένα αποθηκεύτηκαν επιτυχώς στο αρχείο productsURL.json');
  })
  .catch(error => {
    console.error(`Σφάλμα κατά την απευθείας κλήση: ${error.response.status}`);
  });
