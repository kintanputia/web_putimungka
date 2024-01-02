@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- upp -->
                            <!-- grafik penjualan -->
                            <select id="yearFilter" onchange="updateChart()">
                            @foreach ($uniqueYears as $year)
                                <option value="{{ $year }}" @if ($year == $currentYear) selected @endif>{{ $year }}</option>
                            @endforeach
                            </select>
                            <h4 id='omzet' class="mt-4"></h4>
                            <canvas id="monthlyReportChart" width="400" height="150"></canvas>
                            <!-- Filter form -->
                                <!-- tampil error-->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('laporanpenjualan') }}" method="GET" class="mb-3 mt-3">
                                    <div class="form-row align-items-center">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="start_date">Tanggal Mulai</label>
                                                <input type="date" name="start_date" class="form-control" id="start_date" placeholder="Start Date">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="end_date">Tanggal Selesai</label>
                                                <input type="date" name="end_date" class="form-control" id="end_date" placeholder="End Date">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-primary btn-sm">Cek</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <!-- tampil tanggal start dan end -->
                            @if ($start_date && $end_date)
                                <p>Menampilkan transaksi dari {{ $start_date }} hingga {{ $end_date }}</p>
                            @endif
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
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">ID Transaksi</th>
                                        <th scope="col">Nama Pelanggan</th>
                                        <th scope="col">Tanggal Transaksi Selesai</th>
                                        <th scope="col">Total Belanja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($penjualan_admin as $key => $d)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $d->id_transaksi }}</td>
                                                <td>{{ $d->nama_pelanggan }}</td>
                                                <td>{{ $d->tgl_selesai }}</td>
                                                <td>{{ number_format($d->total_belanja) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- table 1 -->
                            </div>
                            <div class="tab-pane fade" id="table2" role="tabpanel" aria-labelledby="table2-tab">
                                <!-- Table 2-->
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">ID Transaksi</th>
                                        <th scope="col">Nama Pelanggan</th>
                                        <th scope="col">Tanggal Transaksi Selesai</th>
                                        <th scope="col">Total Belanja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($penjualan_pelanggan as $key => $d)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $d->id_transaksi }}</td>
                                                <td>{{ $d->nama_pelanggan }}</td>
                                                <td>{{ $d->tgl_selesai }}</td>
                                                <td>{{ number_format($d->total_belanja) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- table 2 -->
                            </div>
                            </div>
                            <!-- tab -->
                            <button onclick="cetak_laporan()" class="btn btn-primary btn-sm mt-3 mb-3 float-right">Cetak Laporan</button>
                            <div>
                        </div>
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
                    console.log(data);
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
<script>
    function cetak_laporan(){
        var queryParams = new URLSearchParams(window.location.search);

        var startDate = queryParams.get('start_date');
        var endDate = queryParams.get('end_date');

        if (startDate && endDate) {
            console.log(`Start Date: ${startDate}`);
            console.log(`End Date: ${endDate}`);
        }
        else {
            if (!startDate) {
            startDate = '';
            }
            if (!endDate) {
                endDate = '';
            }
        }

        var url = "{{ route('generate-laporan') }}" + "?start_date=" + startDate + "&end_date=" + endDate;
        window.location.href = url;
    }
</script>
@endsection