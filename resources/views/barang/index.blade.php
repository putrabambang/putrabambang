
@extends('layouts.master')

@section('title')
Daftar Barang
@endsection
@section('breadcrumb')
@parent 
<li class="active">Daftar Barang</li>    
@endsection
@section('content')
          <!-- Main row -->
          <div class="row">
            <div class="col-md-12">
              <div class="box">
            
                   <div class="box-header with-border">
                    <div class="btn-group">
                        <button onclick="addForm('{{route('barang.store')}}')"class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Tambah</button>
                        <button onclick="deleteSelected('{{ route('barang.delete_selected') }}')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Hapus</button>
                        <button onclick="cetakBarcode('{{ route('barang.cetak_barcode') }}')" class="btn btn-info btn-xs btn-flat"><i class="fa fa-barcode"></i> Cetak Barcode</button>
                    </div>
                </div>
               <form action="" method="post"class="form-barang">
                   @csrf    <!-- /.box-header -->
                <div class="box-body table-responsive">   
                <div class="alert alert-info alert-dismissible" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i> Perubahan berhasil disimpan
                    </div>
                    <table class="table table-stiped table-bordered">
                        <thead>
                  
                            <th>
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                              <th>Kode</th>
                              <th>Kategori</th>
                              <th>Nama Barang</th>        
                              <th>Harga Jual</th>
                              <th>Stok Toko</th>
                              <th>Subtotal</th>
                              <th>Stok Gudang</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="6" style="text-align:center">Total</th>
                            <th ></th>
                            <th ></th>
                            <th ></th>
                            <th ></th>
                        </tr>
                    </tfoot>
                    </table>

                </form>
              </div>
            </div>
              </div>
</div>

          </div>

@includeIf('barang.form')
   
        

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
                url: '{{ route('barang.data') }}',
            },
            columns: [
                {data: 'select_all',searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_barang'},
                {data: 'nama_kategori'},
                {data: 'nama_barang'},
                {data: 'harga_jual'},
                {data: 'stok'},
                {data: 'subtotal'},
                {data: 'stok_gudang'},
                {data: 'aksi', searchable: false, sortable: false},
            ],      
            columnDefs:
[
    
    {
        targets: 7,
        render: $.fn.dataTable.render.number( '.', '.',0, 'Rp. ' )
    },
], 
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[Rp,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                total = api
                    .column(6)
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                 // Total item
                    item = api
                    .column(8)
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
    
                // Total over this page
                pageTotal = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Update footer
                var numFormat = $.fn.dataTable.render.number( '.', '.',0, 'Rp. ' ).display;
                $( api.column( 7 ).footer() ).html(
                    ''+ numFormat(pageTotal)
                    
                    
                );
                $( api.column( 6 ).footer() ).html(
                    ''+total+''
                );
                $( api.column( 8).footer() ).html(
                    ''+item+''
                );
                $( api.column( 9).footer() ).html(
                    ''+(item + total)+''
                );
            }
        

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
        $('#modal-form .modal-title').text('Tambah Barang');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_barang]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Barang');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_barang]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=kode_barang]').val(response.kode_barang);   
                $('#modal-form [name=nama_barang]').val(response.nama_barang);   
                $('#modal-form [name=id_kategori]').val(response.id_kategori);
                $('#modal-form [name=harga_jual]').val(response.harga_jual);
                $('#modal-form [name=stok]').val(response.stok);
                $('#modal-form [name=stok_gudang]').val(response.stok_gudang);
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
                    $.post(url, $('.form-barang').serialize())

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
    function cetakBarcode(url) {
        if ($('input:checked').length < 1) {
            Swal.fire({//title: 'Deleted!',
                        text:  'pilih barang yang akan dicetak ',
                        icon:     'warning',
                        showConfirmButton: false,
                        timer: 1500}
                        

                                 );
            return;
        } else {
            $('.form-barang')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }
</script>
@endpush