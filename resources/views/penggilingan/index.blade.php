@extends('layouts.master')

@section('title')
    Data penggilingan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar penggilingan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                 <form action="{{ route('penggilingandetail.index') }}" method="get" data-toggle="validator" class="form-horizontal">
            <div class="form-group row">
                <label for="nomor_order" class="col-lg-2  control-label">Nomor order</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" name="nomor_order" id="nomor_order" value="{{ request('nomor_order') }}">
                    <span class="help-block with-errors"></span>
                </div>
            </div>
            </form>
                <table class="table table-stiped table-bordered table-penggilingan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nomor Order</th>
                        <th>Tanggal Masuk</th>
                        <th>Total Item </th>
                        <th>Total Awal</th>
                        <th>Total Bayar</th>
                        <th>Kasir</th>
                        <th>Status</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('penggilingan.detail')
@includeIf('penggilingan.form')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-penggilingan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penggilingan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'id_penggilingan'},
                {data: 'tanggal'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'total_akhir'},
                {data: 'kasir'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
   });
            $('#modal-form').validator().on('submit', function (e) {
                if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .done(response => {
                        Swal.fire({
                         icon: 'success',
                         title: 'Success',
                         text: 'data berhasil disimpan',
                         showConfirmButton: false,
                        timer: 1500
                        })
                })
                    .fail((errors) => {
                        Swal.fire({
                         icon: 'error',
                        title: 'Oops...',
                        text: 'data gagal disimpan!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                    });

                }
            });
     

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'id_item'},
                {data: 'nama_item'},
                {data: 'harga'},
                {data: 'jumlah'},
                {data: 'subtotal'},
                {data: 'total_akhir'},
            ]
        })

       
    });
    
    function konfirmasi(url) {
    
            Swal.fire({
        title: 'Konfirmasi pengambilan?',
        text: "Pastikan Nomor Pesanan Sudah sesuai!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
             $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post'
                })
            .done((response) => {
                    table.ajax.reload();
                    Swal.fire({title: 'Success!',
                        text:  'Status Berhasil Diubah.',
                        icon:     'success',
                        showConfirmButton: false,
                        timer: 1500}
                        

                                 )
                })
            .fail((errors) => {
                        Swal.fire({
                         icon: 'error',
                        title: 'Oops...',
                        text: 'Status Gagal Diubah!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                    });
   
                 }
                })
          
    }
    function batalkonfir(url) {
        Swal.fire({
        title: 'Ingin Batalkan Konfirmasi pengambilan?',
        text: "Pastikan Nomor Pesanan Sudah sesuai!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
             $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'post'
                })
            .done((response) => {
                    table.ajax.reload();
                    Swal.fire({title: 'Success!',
                        text:  'Status Berhasil Diubah.',
                        icon:     'success',
                        showConfirmButton: false,
                        timer: 1500}
                        

                                 )
                })
            .fail((errors) => {
                        Swal.fire({
                         icon: 'error',
                        title: 'Oops...',
                        text: 'Status Gagal Diubah!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                    });
   
                 }
                })
          
    }
    function editForm(url) {
        $('#modal-form').modal('show');
        
        $('#modal-form .modal-title').text('Edit status');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=status]').focus();

        $.get(url)
            .done((response) => {
                //$('#modal-form [name=nama_item]').val(response.nama_item);
                $('#modal-form [name=status]').val(response.status);
            })
            .fail((errors) => {
                Swal.fire({
                         icon: 'error',
                        title: 'Oops...',
                        text: 'Tidak dapat menampilkan data!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        });
                return;
            });
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        
        Swal.fire({
        title: 'kamu yakin menghapus data ini?',
        text: "kamu tidak dapat mengembalikan data ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
             $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
            .done((response) => {
                    table.ajax.reload();
                    Swal.fire({title: 'Deleted!',
                        text:  'Data telah dihapus.',
                        icon:     'success',
                        showConfirmButton: false,
                        timer: 1500}
                        

                                 )
                })
            .fail((errors) => {
                        Swal.fire({
                         icon: 'error',
                        title: 'Oops...',
                        text: 'data gagal dihapus!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                    });
   
                 }
                })
    }

   
   
</script>
@endpush