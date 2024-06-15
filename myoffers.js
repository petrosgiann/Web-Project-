window.onload = () => {
  // Fetch data from the server using PHP
  fetch('getoffers.php')
    .then(response => response.text())  // Χρησιμοποιεί response.text() αν τα δεδομένα είναι σε μορφή κειμένου
    .then(data => loadTableData(data))
    .catch(error => console.error('Error fetching data:', error));
}

function loadTableData(requestData) {
  const tableBody = document.getElementById('tableData');
  // Έλεγχος αν τα δεδομένα είναι HTML
  if (typeof requestData === 'string') {
    tableBody.innerHTML = requestData;
  } else {
    console.error('Unexpected data format:', requestData);
  }
}