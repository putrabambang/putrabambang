@extends('layouts.master')

@section('title')
    Laporan barang terjual {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Laporanp barang terjual</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Ubah Periode</button>
                <a href="{{ route('laporanbarang.export_excel', [$tanggalAwal, $tanggalAkhir]) }}" class="btn btn-success btn-xs btn-flat">
    <i class="fa fa-file-excel-o"></i> Export Excel
</a>
                <a href="{{ route('laporanbarang.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode barang</th>
                        <th>Nama barang</th>
                        <th>Harga barang</th>
                        <th>Jumlah Terjual</th>
                        <th>Subtotal</th>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="4" style="text-align:center">Total</th>
                            <th ></th>
                            <th ></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('laporanbarang.form')
@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('laporanbarang.data', [$tanggalAwal, $tanggalAkhir]) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},  
                {data: 'kode_barang'},
                {data: 'nama_barang'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'}
            ],
            columnDefs: [
                {
                    targets: 5,
                    render: $.fn.dataTable.render.number('.', '.', 0, 'Rp. ')
                },
            ],
           
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[Rp,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(5, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                var numFormat = $.fn.dataTable.render.number('.', '.', 0, 'Rp. ').display;
                $(api.column(5).footer()).html(
                    '' + numFormat(pageTotal)
                );
                $(api.column(4).footer()).html(
                    '' + numFormat(total) + ''
                );
            }

        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });

    function updatePeriode() {
        $('#modal-form').modal('show');
    }
</script>
@endpush

