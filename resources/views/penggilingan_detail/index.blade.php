@extends('layouts.master')

@section('title')
    order penggilingan
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-penggilingan tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">order penggilingan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                     <form class="form-order">
                    @csrf
                    <div class="form-group row">
                        <label for="no_order" class="col-lg-2">No Order</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="text"class="btn btn-success" name="id_penggilingan" id="id_penggilingan" value="{{ $id_penggilingan }}"> 
                            </div>
                        </div>
                    </div>
                </form>
                <form class="form-item">
                    @csrf
                    <div class="form-group row">
                        <label for="id_item" class="col-lg-2">Id item</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_penggilingan" id="id_penggilingan" value="{{ $id_penggilingan }}"> 
                                <input type="text" class="form-control" name="id_item" id="id_item">
                                <input type="hidden" class="form-control" name="nama_item" id="nama_item">
                                <span class="input-group-btn">
                                    <button onclick="tampil()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-penggilingan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        <th>Subtotal</th>
                        <th>total Akhir</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('order.simpan') }}" class="form-penggilingan" method="post">
                            @csrf
                            <input type="hidden" name="id_penggilingan" value="{{ $id_penggilingan }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                       

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="status" class="col-lg-2 control-label">Status</label>
                                <div class="col-lg-8">
                                    <select name="status" class="form-control" id="status" value="{{ $penggilingan->status ?? 1 }}"required>
                                        <option value="1">Belum di ambil</option>
                                        <option value="2">Sudah di ambil </option>
                                    </select>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diterima" class="col-lg-2 control-label">Diterima</label>
                                <div class="col-lg-8">
                                    <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penggilingan->diterima ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Order</button>
            </div>
        </div>
    </div>
</div>

@includeIf('penggilingan_detail.item')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-penggilingan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('order.data', $id_penggilingan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'id_item'},
                {data: 'nama_item'},
                {data: 'harga'},
                {data: 'jumlah'},
                {data: 'subtotal'},
                {data: 'total_akhir'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm();
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });
        table2 = $('.table-item').DataTable();
        
        $(document).on('input','.quantity', function () {
            let id = $(this).data('id');
            let jumlah = ($(this).val());

            $.post(`{{ url('/order') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'patch',
                    'jumlah': jumlah,

                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload ();
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menambah');
                    return;
                });
            });
            $(document).on('input','.quantity2', function () {
            let id = $(this).data('id');
            let total_akhir = ($(this).val());
            let jumlah = ($(this).val());
            $.post(`{{ url('/order') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'patch',
                    'jumlah': jumlah,
                    'total_akhir': total_akhir

                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload ();
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat mengubah');
                    return;
                });
        });
        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            loadForm($(this).val());
        }).focus(function () {
            $(this).select();
        });
        
        $('.btn-simpan').on('click', function () {
            $('.form-penggilingan').submit();
        });
    });

   
    function pilihitem(id,nama) {
        $('#id_item').val(id);
        $('#nama_item').val(nama);
        //hideitem();
        tambahitem();
    }

    function tambahitem() {
        $.post('{{ route('order.store') }}', $('.form-item').serialize())
            .done(response => {
                $('#nama_item').focus();
                table.ajax.reload();
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return;
            });
    }
   function tampil() {
        $('#modal-item').modal('show');
    }

    function hideitem() {
        $('#modal-item').modal('hide');
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

    function loadForm(diterima = 0) {
        $('#grandtotal').val($('.grandtotal').text());
        $('#total_item').val($('.total_item').text());
        $('#total').val($('.total').text());
        $.get(`{{ url('/order/loadform') }}/${$('.grandtotal').text()}/${diterima}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);

                $('#kembali').val('Rp.'+ response.kembalirp);
                if ($('#diterima').val() != 0) {
                    $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                    $('.tampil-terbilang').text(response.kembali_terbilang);
                }
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
</script>
@endpush