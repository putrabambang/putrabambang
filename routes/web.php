<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    LaporanController,
    BarangController,
    ItemController,
    MemberController,
    PengeluaranController,
    PenjualanController,
    PembelianController,
    PembelianDetailController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
    UserController,
    PenggilinganController,
    PenggilinganDetailController,
    PengeluaranbaksoController,
    LaporanbaksoController,
    LaporanbarangController,
};
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
        Route::get('/barang/data', [BarangController::class, 'data'])->name('barang.data');
        Route::post('/barang/delete-selected', [BarangController::class, 'deleteSelected'])->name('barang.delete_selected');
        Route::post('/barang/cetak-barcode', [BarangController::class, 'cetakBarcode'])->name('barang.cetak_barcode');
        Route::resource('/barang', BarangController::class);
        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)->except('create');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)->except('create', 'show', 'edit');

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/barang/data', [BarangController::class, 'data'])->name('barang.data');
        Route::post('/barang/cetak-barcode', [BarangController::class, 'cetakBarcode'])->name('barang.cetak_barcode');
        Route::resource('/barang', BarangController::class)->except('destroy', 'update', 'store');

        Route::get('/penjualan/data/{awal}/{akhir}', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/scan', [PenjualanController::class, 'scan'])->name('scan');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');

        Route::get('/penjualandetail', [PenjualanDetailController::class, 'index'])->name('penjualan_detail.index');

        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)->except('create', 'show', 'edit');

        Route::get('/laporanbarang', [LaporanbarangController::class, 'index'])->name('laporanbarang.index');
        Route::get('/laporanbarang/data/{awal}/{akhir}', [LaporanbarangController::class, 'data'])->name('laporanbarang.data');
        Route::get('/laporanbarang/pdf/{awal}/{akhir}', [LaporanbarangController::class, 'exportPDF'])->name('laporanbarang.export_pdf');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');
    });

    Route::group(['middleware' => 'level:1,2,3'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);
        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::group(['middleware' => 'level:1,3'], function () {
        Route::get('/item/data', [ItemController::class, 'data'])->name('item.data');
        Route::post('item/delete-selected', [ItemController::class, 'deleteSelected'])->name('item.delete_selected');
        Route::resource('/item', ItemController::class);

        Route::get('/penggilingan/data', [PenggilinganController::class, 'data'])->name('penggilingan.data');
        Route::get('/penggilingan', [PenggilinganController::class, 'index'])->name('penggilingan.index');
        Route::get('/penggilingan/{id}', [PenggilinganController::class, 'show'])->name('penggilingan.show');
        Route::delete('/penggilingan/{id}', [PenggilinganController::class, 'destroy'])->name('penggilingan.destroy');
        Route::post('/penggilingan/konfirmasi/{id}', [PenggilinganController::class, 'konfirmasi'])->name('penggilingan.konfirmasi');
        Route::post('/penggilingan/batalkonfir/{id}', [PenggilinganController::class, 'batalkonfir'])->name('penggilingan.batalkonfir');
        Route::get('/penggilingandetail', [PenggilingandetailController::class, 'index'])->name('penggilingandetail.index');

        Route::get('/order/baru', [PenggilinganController::class, 'create'])->name('order.baru');
        Route::post('/order/simpan', [PenggilinganController::class, 'store'])->name('order.simpan');
        Route::get('/order/selesai', [PenggilinganController::class, 'selesai'])->name('order.selesai');
        Route::get('/order/nota-kecil', [PenggilinganController::class, 'notaKecil'])->name('order.nota_kecil');
        Route::get('/order/nota-besar', [PenggilinganController::class, 'notaBesar'])->name('order.nota_besar');

        Route::get('/pengeluaranbakso/data', [PengeluaranbaksoController::class, 'data'])->name('pengeluaranbakso.data');
        Route::resource('/pengeluaranbakso', PengeluaranbaksoController::class);

        Route::get('/laporanbakso', [LaporanbaksoController::class, 'index'])->name('laporanbakso.index');
        Route::get('/laporanbakso/data/{awal}/{akhir}', [LaporanbaksoController::class, 'data'])->name('laporanbakso.data');
        Route::get('/laporanbakso/pdf/{awal}/{akhir}', [LaporanbaksoController::class, 'exportPDF'])->name('laporanbakso.export_pdf');

        Route::get('/order/{id}/data', [PenggilinganDetailController::class, 'data'])->name('order.data');
        Route::get('/order/loadform/{grandtotal}/{diterima}', [PenggilinganDetailController::class, 'loadForm'])->name('order.load_form');
        Route::resource('/order', PenggilinganDetailController::class)->except('create', 'show', 'edit');
    });
});
