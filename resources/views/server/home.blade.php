@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

<style>
    #pendapatanChart {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        border: 1px solid #eaeaea;
        max-width: 100%;
        height: auto;
        margin: 40px;
    }

    .card {
        border-radius: 8px;
    }

    .card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card .card-body .col-auto {
        padding: 0;
    }

    .card .card-body .fas {
        font-size: 2em;
        color: #ccc;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Data Rute</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rute }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pendapatan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp.
                            {{ number_format($pendapatan, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Data Transportasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transportasi }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-subway"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Data User</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <canvas id="pendapatanChart"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        fetch('/pendapatan-data')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('pendapatanChart').getContext('2d');
                const pendapatanChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => getMonthName(item.month) + '-' + item.year),
                        datasets: [{
                            label: 'Total Pendapatan',
                            data: data.map(item => item.total),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: 'rgba(75, 192, 192, 1)',
                            pointHoverBackgroundColor: 'rgba(75, 192, 192, 1)',
                            pointHoverBorderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuad'
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(200, 200, 200, 0.3)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                bodySpacing: 5,
                                titleMarginBottom: 10,
                                bodyFont: {
                                    size: 14
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        function getMonthName(monthNumber) {
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
                "Oktober", "November", "Desember"
            ];
            return monthNames[monthNumber - 1];
        }
    </script>
@endsection
