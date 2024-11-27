<div>
    
    <canvas id="workHoursChart"></canvas>
    
    <script>
           var ctx = document.getElementById('workHoursChart').getContext('2d');
var employeeStatusChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            'Not Yet In',
            'On Time: ',
            'Late In:'
        ],
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)', // Color for "Not Yet In"
                'rgba(75, 192, 192, 0.2)',  // Color for "On Time"
                'rgba(255, 159, 64, 0.2)'   // Color for "Late In"
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true, // Allow resizing to custom dimensions
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                enabled: true
            }
        }
    }
});

    </script>
</div>

