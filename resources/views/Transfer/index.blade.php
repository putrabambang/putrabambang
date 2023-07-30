@extends('layouts.master')

@section('title')
    Daftar transfer
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar transfer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="window.location.href='{{ route('transfer.baru') }}'"  class="btn btn-success btn-xs btn-flat"><i class="fa fa-exchange"></i> Transfer baru</button>
                @if(!empty(session('id_transfer')))
                <a href="{{ route('transfer_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i class=" fa fa-edit"></i> Transfer Aktif</a>
                @endif
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-transfer">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Total Item</th>
                        <th>User</th>
                        <th>Role Transfer</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('transfer.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-transfer').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transfer.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'total_item'},
                {data: 'kasir'},
                {data: 'role',
                    render: function (data) {
                if (data == 0) {
                        return '<span class="label label-danger">gudang</span>';
                    } else if (data > 0) {
                        return '<span class="label label-success">pajangan</span>';
                    } else {
                        return '';
                    }
                }
            },
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_barang'},
                {data: 'nama_barang'},
                {data: 'jumlah'},
            ]
        })
    });

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush
