<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simulasi data kosong
        const dataKunjunganHarian = {
            labels: [],
            data: []
        };
        const dataPendapatanBulanan = {
            labels: [],
            data: []
        };
        const dataKunjunganPoli = {
            labels: [],
            data: []
        };

        renderChartKunjunganHarian(dataKunjunganHarian);
        renderChartPendapatanBulanan(dataPendapatanBulanan);
        renderChartKunjunganPerPoli(dataKunjunganPoli);

        function renderChartKunjunganHarian(result) {
            const ctx = document.getElementById('kunjunganChart').getContext('2d');
            const labels = result.labels.length ? result.labels : ['Tidak ada data'];
            const dataPoints = result.data.length ? result.data : [0];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: dataPoints,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function renderChartPendapatanBulanan(data) {
            const ctx = document.getElementById('pendapatanChart').getContext('2d');
            const labels = data.labels.length ? data.labels : ['Tidak ada data'];
            const totals = data.data.length ? data.data : [0];

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: totals,
                        backgroundColor: 'rgba(255, 206, 86, 0.3)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderChartKunjunganPerPoli(result) {
            const ctx = document.getElementById('poliChart').getContext('2d');
            const labels = result.labels.length ? result.labels : ['Tidak ada data'];
            const dataPoints = result.data.length ? result.data : [0];
            const bgColors = result.labels.length ?
                ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'] :
                ['rgba(200,200,200,0.3)'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataPoints,
                        backgroundColor: bgColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '60%'
                }
            });

            const progressContainer = document.getElementById('progressPoli');
            progressContainer.innerHTML = '';

            if (result.labels.length === 0 || result.data.length === 0) {
                progressContainer.innerHTML = `<p class="text-muted text-center">Tidak ada data kunjungan</p>`;
                return;
            }

            const total = result.data.reduce((a, b) => a + b, 0);
            result.labels.forEach((label, i) => {
                const percentage = ((result.data[i] / total) * 100).toFixed(1);
                progressContainer.innerHTML += `
            <div class="mb-2">
            <div class="d-flex justify-content-between">
                <small><strong>${label}</strong></small>
                <small>${percentage}%</small>
            </div>
            <div class="progress">
                <div class="progress-bar" style="width: ${percentage}%;">${percentage}%</div>
            </div>
            </div>
        `;
            });
        }
    });
</script>
