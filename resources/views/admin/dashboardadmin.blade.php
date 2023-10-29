@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- uppp -->
                            <!-- grafik penjualan -->
                            <select id="yearFilter" onchange="updateChart()">
                                @foreach ($uniqueYears as $year)
                                    <option value="{{ $year }}" @if ($year == $currentYear) selected @endif>{{ $year }}</option>
                                @endforeach
                            </select>
                            <h4 id='omzet' class="mt-4"></h4>
                            <canvas id="monthlyReportChart" width="400" height="150"></canvas>
                            <h4 class="mt-5">Pesanan Terkini</h4>
                            <!-- tab -->
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="table1-tab" data-toggle="tab" href="#table1" role="tab" aria-controls="table1" aria-selected="true">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="table2-tab" data-toggle="tab" href="#table2" role="tab" aria-controls="table2" aria-selected="false">Pelanggan</a>
                </li>
                </ul>
                <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="table1" role="tabpanel" aria-labelledby="table1-tab">
                    <!-- Table 1 -->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Nama Pelanggan</th>
                                                <th scope="col">Tanggal Pemesanan</th>
                                                <th scope="col">Tanggal Selesai</th>
                                                <th scope="col">Total Belanja</th>
                                                <th scope="col">Status Transaksi</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pesanan_admin as $key => $d)
                                                <tr>
                                                    <th>{{ ++$key }}</th>
                                                    <td>{{ $d->nama_pelanggan }}</td>
                                                    <td>{{ $d->tgl_pesan }}</td>
                                                    <td>
                                                        @if ($d->tgl_selesai == null)
                                                            -
                                                        @else
                                                            {{ $d->tgl_selesai }}
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($d->total_belanja) }}</td>
                                                    <td>{{ $d->status_transaksi }}</td>
                                                    <td>
                                                        <a href="/detailpesananadm/{{ $d->id_transaksi }}">
                                                            <button class="btn btn-success btn-sm"><i class="fa fa-info"
                                                                    aria-hidden="true"></i>
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- table 1 -->
                        </div>
                        <div class="tab-pane fade" id="table2" role="tabpanel" aria-labelledby="table2-tab">
                            <!-- Table 2-->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Nama Pelanggan</th>
                                                <th scope="col">Tanggal Pemesanan</th>
                                                <th scope="col">Tanggal Selesai</th>
                                                <th scope="col">Total Belanja</th>
                                                <th scope="col">Status Transaksi</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pesanan_pelanggan as $key => $d)
                                                <tr>
                                                    <th>{{ ++$key }}</th>
                                                    <td>{{ $d->nama_pelanggan }}</td>
                                                    <td>{{ $d->tgl_pesan }}</td>
                                                    <td>
                                                        @if ($d->tgl_selesai == null)
                                                            -
                                                        @else
                                                            {{ $d->tgl_selesai }}
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($d->total_belanja) }}</td>
                                                    <td>{{ $d->status_transaksi }}</td>
                                                    <td>
                                                        <a href="/detailpesananbypel/{{ $d->id_transaksi }}">
                                                            <button class="btn btn-success btn-sm"><i class="fa fa-info"
                                                                    aria-hidden="true"></i>
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- table 2 -->
                        </div>
                        </div>
                        <!-- tab -->
                        <!-- downn -->
                    </div>
                </div>
            </div>
        </div>

<script>
        var yearFilter = document.getElementById('yearFilter');
        var monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var monthlySalesData = '';
        var ctx = document.getElementById('monthlyReportChart').getContext('2d');
        var chart;

        function updateChart() {
            var selectedYear = yearFilter.value;

            if (chart) {
                chart.destroy();
            }

            $.ajax({
                url: '/getMonthlySalesData/' + selectedYear,
                type: 'GET',
                success: function (data) {
                    console.log(data.totalMonthlySales);
                    var omzet = data.totalMonthlySales;
                    $("#omzet").text('Total Penjualan : ' + omzet.toLocaleString());
                    var monthlySalesData = data.data;
                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: monthNames,
                            datasets: [{
                                label: 'Total Penjualan',
                                data: monthlySalesData,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Grafik Penjualan ' + selectedYear,
                                    font: {
                                        size: 18
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        updateChart();
</script>
@endsection