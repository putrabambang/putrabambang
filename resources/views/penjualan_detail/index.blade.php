@extends('layouts.master')

@section('title')
    Transaksi Penjualan
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

    .table-penjualan tbody tr:last-child {
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
    <li class="active">Transaksi Penjualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                 <form class="form-penjualan">
                    @csrf
                    <div class="form-group row">
                        <label for="no_penjualan" class="col-lg-2">No Penjualam</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="text"class="btn btn-success" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}"> 
                            </div>
                        </div>
                    </div>
                </form>
                    
                <form class="form-barang">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_barang" class="col-lg-2">Kode barang</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_penjualan" id="id_penjualan"value="{{ $id_penjualan }}"> 
                                <input type="hidden" name="id_barang" id="id_barang">
                                <input type="hidden" name="status" id="status" value="1">
                                <input  onchange="tambahbarang()"type="text"  class="form-control" name="kode_barang" id="kode_barang">
                                <span class="input-group-btn">
                                   
                                    <button onclick="tampilbarang()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                                 
                            </div>
                            <div id="reader" width="50px"></div>
                        </div>
                    </div>
                    
                </form>

                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                            <input type="hidden" name="status" id="status" value="1">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="id_member" id="id_member" value="{{ $memberSelected->id_member }}">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_member" class="col-lg-2 control-label">Member</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kode_member" value="{{ $memberSelected->kode_member }}">
                                        <span class="input-group-btn">
                                            <button onclick="tampilMember()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" 
                                        value="{{ ! empty($memberSelected->id_member) ? $diskon : 0 }}" 
                                        readonly>
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
                                    <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penjualan->diterima ?? 0 }}">
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
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                <button type="button" class="btn btn-danger btn-sm btn-flat" onclick="batalPenjualan('{{ route('penjualan.destroy', $id_penjualan) }}')"><i class="fa fa-times-circle"></i> Batal Transaksi</button>
                 <p class="text-center">Pastikan Jumlah,harga,uang di terima dan kembalian Seblum Menyimpan Transaksi ini</p>
                            
        </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.barang')
@includeIf('penjualan_detail.member')
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let table, table2;
            

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi.data', $id_penjualan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_barang'},
                {data: 'nama_barang'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'diskon'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });
        table2 = $('.table-barang').DataTable();

         $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());
            let stok = $(this).attr("data-stok");
            if (jumlah > stok) {
                $(this).val(0);
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah Tidak boleh Melebihi Stok!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                return;
            }
            if (jumlah < 1) {
                $(this).val(1);
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah Tidak boleh Kurang dari Stok!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah Tidak boleh Melebihi 10000!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                return;
            }

            $.post(`{{ url('/transaksi') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                        Swal.fire({
                        position: 'top-right',
                        icon: 'success',
                        title: 'Success',
                        text: 'Berhasil Mengubah Jumlah!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                    });
                })
                .fail(errors => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tidak Dapat Mengubah Jumlah!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                    return;
                });
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#diskon').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });
        $('.btn-simpan').on('click', function () {
    $('.form-penjualan').submit();
});

$('.form-penjualan').on('submit', function (e) {
    e.preventDefault();
    var form = $(this);

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize(),
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Transaksi berhasil disimpan',
                showCancelButton: true,
                cancelButtonText: 'Dashboard',
                confirmButtonText: 'Cetak Nota',
                showCloseButton: true,
                closeButtonAriaLabel: 'Tutup',
                showCancelButton: true,
                cancelButtonText: 'Transaksi Baru'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buka halaman cetak nota dalam jendela pop-up
                    var notaUrl = '';
                    @if ($setting->tipe_nota == 1)
                        notaUrl = '{{ route("transaksi.nota_kecil") }}';
                    @else
                        notaUrl = '{{ route("transaksi.nota_besar") }}';
                    @endif

                    window.open(notaUrl, 'Nota Penjualan', 'width=800,height=600');

                    Swal.fire({
    icon: 'info',
    title: 'Pilih Tindakan Selanjutnya',
    showCancelButton: true,
    cancelButtonText: 'Transaksi Baru',
    confirmButtonText: 'Menu Utama',
    showCloseButton: true,
    closeButtonAriaLabel: 'Tutup'
}).then((result) => {
    if (result.isConfirmed) {
        // Mengarahkan pengguna ke halaman dashboard
        window.location.href = '{{ route("dashboard") }}';
    } else if (result.dismiss === Swal.DismissReason.cancel) {
        // Mengarahkan pengguna ke halaman transaksi baru
        window.location.href = '{{ route("transaksi.baru") }}';
    }
});
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Tindakan ketika tombol Transaksi Baru diklik
                    // Mengarahkan pengguna ke halaman transaksi baru
                    window.location.href = '{{ route("transaksi.baru") }}';
                } else if (result.dismiss === Swal.DismissReason.close) {
                    // Tindakan ketika tombol Tutup diklik
                    // Mengarahkan pengguna ke halaman dashboard
                    window.location.href = '{{ route("dashboard") }}';
                }
            });
        },
        error: function (xhr, status, error) {
            // Penanganan kesalahan jika ada
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menyimpan transaksi.'
            });
        }
    });
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
        //hidebarang();
        tambahbarang();
    }
    function batalPenjualan(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Anda tidak dapat mengembalikan data transaksi yang sudah dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Tidak, kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        Swal.fire({
                        title: 'Terhapus!',
                        text: 'Transaksi telah dihapus.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Transaksi Baru',
                        cancelButtonText: 'Menu Utama',
                        timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect atau melakukan tindakan untuk transaksi baru
                            window.location.href = "{{route('transaksi.baru')}}";
                        } else {
                            // Redirect atau melakukan tindakan untuk kembali ke menu utama
                            window.location.href = "{{route('dashboard')}}";
                        }
                    });
                },
                    error: function (xhr, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan. Transaksi gagal dibatalkan!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

    function tambahbarang() {
        $.post('{{ route('transaksi.store') }}', $('.form-barang').serialize())
            .done(response => {
                Swal.fire({toast: true,
                        icon: 'success',
                        title: 'Success',
                        text: 'Berhasil Menambah Barang!',
                        showConfirmButton: false,
                        position: 'top-right',
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                //$('#kode_barang').focus();
                $('#kode_barang').val("").focus().select();
                table.ajax.reload(() => loadForm($('#diskon').val()));
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
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                return;
            });
    }
    function tampilMember() {
        $('#modal-member').modal('show');
    }

    function pilihMember(id, kode) {
        $('#id_member').val(id);
        $('#kode_member').val(kode);
        $('#diskon').val('{{ $diskon }}');
        loadForm($('#diskon').val());
        $('#diterima').val(0).focus().select();
        hideMember();
    }

    function hideMember() {
        $('#modal-member').modal('hide');
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
                    Swal.fire({
                        position: 'top-right',
                        title: 'Deleted!',
                        text:  'Data telah dihapus.',
                        icon:     'success', 
                        toast: true,
                        showConfirmButton: false,
                        timer: 1900}
                        

                                 )
                })
            .fail((errors) => {
                        Swal.fire({
                        position: 'top-right',
                        icon: 'error',
                        title: 'Oops...',
                        text: 'data gagal dihapus bro!',
                        toast: true,
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                    });
                    })
                }});
            }
    
    function loadForm(diskon = 0, diterima = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
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
                Swal.fire({
                    position: 'top-right',
                        toast: true,
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tidak dapat menampilkan data!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                return;
            })
    }
    function onScanSuccess(decodedText, decodedResult) {
            $('#kode_barang').val( decodedText);
            let id = decodedText;
            $.post('{{ route('transaksi.store') }}', $('.form-barang').serialize())
            .done(response => {
                Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Berhasil Menambah Barang!',
                        showConfirmButton: false,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                //$('#kode_barang').focus();
                $('#kode_barang').val("").focus().select();
                table.ajax.reload(() => loadForm($('#diskon').val()));
            })
            .fail(errors => {
                Swal.fire({ 
                     
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Data barang tidak ada! scan ulang!!!',
                        showConfirmButton: true ,
                        timer: 1500
                        //footer: '<a href="">Why do I have this issue?</a>'
                        })
                return;
            });
            }

            function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            //s console.warn(`Code scan error = ${error}`);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
               // tambahkan untuk delete cookie innerHeight terlebih dahulu
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    
    function notaKecil(url, title) {
        popupCenter(url, title, 625, 500);
    }

    function notaBesar(url, title) {
        popupCenter(url, title, 900, 675);
    }

    function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

        const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left       = (width - w) / 2 / systemZoom + dualScreenLeft
        const top        = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow  = window.open(url, title, 
        `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }
</script>
@endpush