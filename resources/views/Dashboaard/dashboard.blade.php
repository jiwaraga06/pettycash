@extends('Layout.layout')
@section('title', 'Dashboard')
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Dashboard</h4>
        </div>

        {{-- Ringkasan --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card card-stats card-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Total Saldo</p>
                                    <h4 class="card-title">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->id_role == 1 || Auth::user()->id_role == 4)

            <div class="col-md-3">
                <div class="card card-stats card-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Pengajuan</p>
                                    <h4 class="card-title">{{ $jumlahPengajuan }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-3">
                <div class="card card-stats card-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Approved</p>
                                    <h4 class="card-title">{{ $jumlahApproved }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stats card-danger">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                            <div class="col-7 d-flex align-items-center">
                                <div class="numbers">
                                    <p class="card-category">Rejected</p>
                                    <h4 class="card-title">{{ $jumlahRejected }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Grafik Pengajuan per Status</h4>
                    </div>
                    <div class="card-body" style="height: 450px">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Grafik Summary Amount</h4>
                    </div>
                    <div class="card-body" style="height: 450px">
                        <canvas id="mychart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ChartJS --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script>
        $(function() {
            getsummaryAmount()
        })
        var ctx = document.getElementById('statusChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Dept Approved', 'Finance Approved', 'Paid', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $jumlahPending }},
                        {{ $jumlahDeptApproved }},
                        {{ $jumlahFinanceApproved }},
                        {{ $jumlahPaid }},
                        {{ $jumlahRejected }}
                    ],
                    backgroundColor: ['#17a2b8', '#007bff', '#28a745', '#6f42c1', '#dc3545']
                }]
            }
        });

        function getsummaryAmount() {
            var body = {
                _token: '{{ csrf_token() }}',
            }
            $.ajax({
                url: "{{ route('chartSummaryAmount') }}",
                type: "POST",
                data: body,
                success: function(data) {
                    var data = data.data;
                    console.log(data);
                    var lineChart = document.getElementById('mychart').getContext('2d');
                        var myLineChart = new Chart(lineChart, {
                            type: 'line',
                            data: {
                                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                                    "Oct", "Nov", "Dec"
                                ],
                                datasets: [{
                                    label: "Active Users",
                                    borderColor: "#1d7af3",
                                    pointBorderColor: "#FFF",
                                    pointBackgroundColor: "#1d7af3",
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 4,
                                    pointHoverBorderWidth: 1,
                                    pointRadius: 4,
                                    backgroundColor: 'transparent',
                                    fill: true,
                                    borderWidth: 2,
                                    data: data.map((e) => e.total_amount)
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 10,
                                        fontColor: '#1d7af3',
                                    }
                                },
                                tooltips: {
                                    bodySpacing: 4,
                                    mode: "nearest",
                                    intersect: 0,
                                    position: "nearest",
                                    xPadding: 10,
                                    yPadding: 10,
                                    caretPadding: 10
                                },
                                layout: {
                                    padding: {
                                        left: 15,
                                        right: 15,
                                        top: 15,
                                        bottom: 15
                                    }
                                }
                            }
                        });

                },
                error: function(xhr) {
                    alert("Terjadi kesalahan: " + xhr.responseText);
                }
            });
        }
    </script>
@endsection
