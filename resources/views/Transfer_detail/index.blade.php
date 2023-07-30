@extends('layouts.master')

@section('title')
    Transfer barang
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Transfer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <form class="form-barang">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_barang" class="col-lg-2">Kode barang</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_transfer" id="id_transfer" value="{{ $id_transfer }}">
                                <input type="hidden" name="id_barang" id="id_barang">
                                <input onchange="tambahbarang()" type="text" class="form-control" name="kode_barang" id="kode_barang">
                                <span class="input-group-btn">
                                    <button onclick="tampilbarang()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-transfer">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('transfer.store') }}" class="form-transfer" method="post">
                            @csrf
                            <input type="hidden" name="id_transfer" value="{{ $id_transfer }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <div class="form-group row">
                                <label for="role" class="col-lg-2 control-label">Role</label>
                                <div class="col-lg-8">
                                    <select name="role" class="form-control" id="role" required>
                                        <option value="1">Pajang/Toko</option>
                                        <option value="2">Tarik/Gudang</option>
                                    </select>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('transfer_detail.barang')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-transfer').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transfer_detail.data', $id_transfer) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_barang'},
                {data: 'nama_barang'},
                {data: 'jumlah'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        }).on('draw.dt', function () {
            loadForm();
        });
        table2 = $('.table-barang').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/transfer_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm());
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });
        $('.btn-simpan').on('click', function (e) {
            e.preventDefault();
            $('.form-transfer').submit();
        });
    });

    function tampilbarang() {
        $('#modal-barang').modal('show');
    }

    function hidebarang() {
        $('#modal-barang').modal('hide');
    }

    function pilihbarang(id, kode) {
        $('#id_barang').val(id);
        $('#kode_barang').val(kode);
        tambahbarang();
    }

    function tambahbarang() {
        $.post('{{ route('transfer_detail.store') }}', $('.form-barang').serialize())
            .done(response => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: 'Success',
                    text: 'Berhasil Menambah Barang!',
                    showConfirmButton: false,
                    position: 'top-right',
                    timer: 1500
                });
                $('#kode_barang').val("").focus().select();
                table.ajax.reload( loadForm());
            })
            .fail(errors => {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tidak dapat menambah data!',
                    showConfirmButton: false,
                    position: 'top-right',
                    timer: 1500
                });
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
    function loadForm(diskon = 0, diterima = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

    }
</script>
@endpush
