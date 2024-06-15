var myChart; // Ορισμός της μεταβλητής myChart στο επίπεδο του window

function loadData() {
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            updateChart(data);
        }
    };

    // Προσθήκη παραμέτρων ημερομηνιών στο URL
    var url = "adminstats.php?startDate=" + startDate + "&endDate=" + endDate;

    xhr.open("GET", url, true);
    xhr.send();
}

function destroyChart() {
    if (myChart) {
        myChart.destroy();
    }
}

function updateChart(data) {
    destroyChart();

    var ctx = document.getElementById('myChart').getContext('2d');
    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['New Requests', 'New Offers', 'Completed Requests', 'Completed Offers'],
            datasets: [{
                label: 'Statistics',
                data: [data.new_requests, data.new_offers, data.completed_requests, data.completed_offers],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

window.onload = function () {
    loadData();
};