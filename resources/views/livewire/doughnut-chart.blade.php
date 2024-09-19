<div>
    <canvas id="doughnutChart"></canvas>

    <script>
        document.addEventListener('livewire:load', function () {
            var ctx = document.getElementById('doughnutChart').getContext('2d');

            var doughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Dataset',
                        data: @json($data),
                        backgroundColor: @json($backgroundColor),
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    }
                }
            });

            Livewire.on('refreshChart', (labels, data) => {
                doughnutChart.data.labels = labels;
                doughnutChart.data.datasets[0].data = data;
                doughnutChart.update();
            });
        });
    </script>
</div>
