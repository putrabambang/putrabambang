@extends('layouts.master')
@section('title')
Daftar Item
@endsection
@section('breadcrumb')
@parent 
<li class="active">Daftar Item</li>    
@endsection
@section('content')
          <!-- Main row -->
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                    <div class="btn-group">
                        <button onclick="addForm('{{route('item.store')}}')"class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Tambah</button>
                        <button onclick="deleteSelected('{{ route('item.delete_selected') }}')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Hapus</button>
                        </div>
                 
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                <form action="" method="post"class="form-item">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="2%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                              <th>Nama Item</th>        
                              <th>Harga Jual</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
              </div>
            </div>
              </div>
            </div>
         
          </div>

@includeIf('item.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('item.data') }}',
            },
            columns: [
                {data: 'select_all',searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_item'},
                {data: 'harga'},
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
        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });  
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah item');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_item]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit item');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_item]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_item]').val(response.nama_item);
                $('#modal-form [name=harga]').val(response.harga);
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
    function deleteSelected(url) {
        if ($('input:checked').length > 0) {
            Swal.fire({
            title: 'kamu yakin menghapus barang ini?',
            text: "kamu tidak dapat mengembalikan barang ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, $('.form-item').serialize())

                    .done((response) => {
                        table.ajax.reload();

                        Swal.fire({title: 'Deleted!',
                        text:  'barang telah dihapus.',
                        icon:     'success',
                        showConfirmButton: false,
                        timer: 1500}
                             );
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
             }else {
        Swal.fire({//title: 'Deleted!',
                        text:  'pilih barang minimal 1 barang ',
                        icon:     'warning',
                        showConfirmButton: false,
                        timer: 1500} );
                        return;
                    }
                }
    
</script>
@endpush