@extends('layouts.master')
@section('title')
Kategori
@endsection
@section('breadcrumb')
@parent 
<li class="active">Daftar Kategori</li>    
@endsection
@section('content')
          <!-- Main row -->
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <button onclick="addForm('{{route('kategori.store')}}')"class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Tambah</button>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table class="table table-stiped table-bordered">
                      <thead>
                          <th width="5%">No</th>
                          <th>Kategori</th>
                          <th>Jumlah barang</th>
                          <th width="15%"><i class="fa fa-cog"></i></th>
                      </thead>
                      <tfoot>
                        <tr>
                            <th colspan="2" style="text-align:center">Total</th>
                            <th ></th>
                            <th ></th>
                        </tr>
                    </tfoot>
                  </table>
              </div>
            </div>
              </div>
            </div>
         
          </div>

@includeIf('kategori.form')
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
                url: '{{ route('kategori.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_kategori'},
                {data: 'jumlah'},
                {data: 'aksi', searchable: false, sortable: false},
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
                    .column(2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                pageTotal = api
                    .column( 2, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Update footer
                //var numFormat = $.fn.dataTable.render.number( '.', '.',0, 'Rp. ' ).display;
                $( api.column(2 ).footer() ).html(
                    ''+pageTotal+''
                );
                $( api.column(2 ).footer() ).html(
                    ''+total+''
                );
            }
    });
////////////////////////////////////////////////////////////////////////////////32:44 
        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.ajax({
                    url:$('#modal-form form').attr('action'),
                    type:'post',
                    data:$('#modal-form form').serialize()

                })
                .done((response)=>{
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                })
                    .fail((errors) => { 
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        })
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_kategori]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_kategori]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_kategori]').val(response.nama_kategori);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
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