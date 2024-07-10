@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">



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
    <canvas id="pieChart" width="400" height="400"></canvas>
    <div class="row">
        <div class="col-12">
            <label for="categoryFilter">Pilih Kategori:</label>
            <select id="categoryFilter" class="form-control">
                <option value="">Semua</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <canvas id="ruteChart"></canvas>
    <script>
        // Pendapatan Chart
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

        // Rute Chart
        let ruteData = {!! json_encode($ruteData) !!};
        const ctxRute = document.getElementById('ruteChart').getContext('2d');
        const ruteChart = new Chart(ctxRute, {
            type: 'bar',
            data: {
                labels: ruteData.map(item => item.tujuan),
                datasets: [{
                    label: 'Jumlah Pembelian',
                    data: ruteData.map(item => item.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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

        document.getElementById('categoryFilter').addEventListener('change', function() {
            const selectedCategory = this.value;
            const filteredData = selectedCategory ?
                ruteData.filter(item => item.category === selectedCategory) :
                ruteData;

            ruteChart.data.labels = filteredData.map(item => item.tujuan);
            ruteChart.data.datasets[0].data = filteredData.map(item => item.total);
            ruteChart.update();
        });

        // Pie Chart
        // var ctx = document.getElementById('pieChart').getContext('2d');
        // var pieChart = new Chart(ctx, {
        //     type: 'pie',
        //     data: {
        //         labels: {!! $pembelianPerKategori->pluck('name')->toJson() !!},
        //         datasets: [{
        //             data: {!! $pembelianPerKategori->pluck('total')->toJson() !!},
        //             backgroundColor: [
        //                 'rgba(255, 99, 132, 0.2)',
        //                 'rgba(54, 162, 235, 0.2)'
        //             ],
        //             borderColor: [
        //                 'rgba(255, 99, 132, 1)',
        //                 'rgba(54, 162, 235, 1)'
        //             ],
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         responsive: true,
        //         legend: {
        //             position: 'top',
        //         },
        //         title: {
        //             display: true,
        //             text: 'Pembelian Berdasarkan Kategori'
        //         }
        //     }
        // });

        var ctx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! $pembelianPerKategori->pluck('name')->toJson() !!},
                datasets: [{
                    data: {!! $pembelianPerKategori->pluck('total')->toJson() !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        // Tambahkan lebih banyak warna jika ada lebih banyak kategori
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        // Tambahkan lebih banyak warna jika ada lebih banyak kategori
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Pembelian Berdasarkan Kategori'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue) {
                                return previousValue + currentValue;
                            }, 0);
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                            return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                        }
                    }
                }
            }
        });
    </script>
@endsection
