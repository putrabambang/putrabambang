@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $kategori }}</h3>

                <p>Total Kategori</p>
            </div>
            <div class="icon">
                <i class="fa fa-cube"></i>
            </div>
            <a href="{{ route('kategori.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $barang }}</h3>

                <p>Total barang</p>
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ route('barang.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $member }}</h3>

                <p>Total Member</p>
            </div>
            <div class="icon">
                <i class="fa fa-id-card"></i>
            </div>
            <a href="{{ route('member.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $penjualan }}</h3>

                <p>Total penjualan</p>
            </div>
            <div class="icon">
                <i class="fa fa-truck"></i>
            </div>
            <a href="{{ route('penjualan.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
 <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>{{ $penggilingan }}</h3>

                <p>Total Order Penggilingan</p>
            </div>
            <div class="icon">
                <i class="fa fa-th" aria-hidden="true"></i>
            </div>
            <a href="{{ route('penggilingan.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-navy">
            <div class="inner">
                <h3>{{ $barangterjual}}</h3>

                <p>Barang Terjual</p>
            </div>
            <div class="icon">
                <i class="fa fa-sellsy" aria-hidden="true"></i>
            </div>
            <a href="{{ route('laporanbarang.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $orderan }}</h3>

                <p>Orderan yang belum di ambil</p>
            </div>
            <div class="icon">
            <i class="fa fa-list-alt" aria-hidden="true"></i>
            </div>
            <a href="{{ route('penggilingan.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $pembelian }}</h3>

                <p>Pembelian</p>
            </div>
            <div class="icon">
            <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
            </div>
            <a href="{{ route('pembelian.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->
<!-- Main row -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Grafik Pendapatan {{ tanggal_indonesia($tanggal_awal, false) }} s/d {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="salesChart" style="height: 180px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row (main row) -->
<div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-bar-chart-o"></i>
            <h3 class="box-title">grafik pendapatan tahun ini</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div id="bar-chart" style="height: 300px;"></div>
            <div id="tooltip" class="flot-tooltip"></div>
        </div>
        <!-- /.box-body-->
    </div>
    <!-- /.box -->

<!-- /.row (main row) -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Grafik Pendapatan Penggilingan {{ tanggal_indonesia($tanggal_awal, false) }} s/d {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="salesChart2" style="height: 180px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

@endsection

@push('scripts')
<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load Flot and Flot categories plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.categories.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.js"></script>
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
<script src="{{ asset('AdminLTE-2/bower_components/Flot/jquery.flot.categories.js') }}"></script>
<script>
$(function() {
  // Data grafik untuk tahun ini
  var dataTahunIni = @json($formattedDataTahunIni);

// Data grafik untuk tahun sebelumnya
var dataTahunSebelumnya = @json($formattedDataTahunSebelumnya);

$(function() {
    var options = {
        
        grid: {
            borderWidth: 1,
            borderColor: '#f3f3f3',
            tickColor: '#f3f3f3'
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.2,
                align: 'center'
            }
        },
        xaxis: {
            mode: 'categories',
            tickLength: 0
        },
        yaxis: {
            tickFormatter: function (val, axis) {
                // Format angka menjadi mata uang Rupiah
                return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        },
        legend: {
            position: 'nw'
        },
        tooltip: {
            show: true,
            content: function(label, xval, yval, flotItem){
                var dataset = flotItem.seriesIndex;
                var data = (dataset === 0) ? dataTahunIni : dataTahunSebelumnya;
                var month = data[xval][0];
                var revenue = data[xval][1];
                return month + ": Rp " + revenue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        },
        responsive : true,
    };

    // Gabungkan data untuk kedua tahun
    var combinedData = [
        {
            data: dataTahunIni,
            label: 'Tahun Ini',
            color: '#3c8dbc'
        },
        {
            data: dataTahunSebelumnya,
            label: 'Tahun Sebelumnya',
            color: '#f56954',
            bars: {
                barWidth: 0.2, // Atur lebar batang berbeda untuk tahun sebelumnya
                align: 'right' // Geser batang tahun sebelumnya ke sebelah kanan
            }
        }
    ];

    $.plot('#bar-chart', combinedData, options);
});
});
$(function() {
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas);

    var salesChartData = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [
            {
                label: 'Pendapatan',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {{ json_encode($data_pendapatan) }}
            }
        ]
    };

    var salesChartOptions = {
        pointDot : true,
        responsive : true
    };

    salesChart.Line(salesChartData, salesChartOptions);
});
$(function() {
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas2 = $('#salesChart2').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart2 = new Chart(salesChartCanvas2);

    var salesChartData2 = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [
            {
                label: 'penggilingan',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {{ json_encode($data_penggilingan) }}
            }
        ]
    };

    var salesChartOptions2 = {
        pointDot : false,
        responsive : true
    };

    salesChart2.Line(salesChartData2, salesChartOptions2);
});
</script>
@endpush