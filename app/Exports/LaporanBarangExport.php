<?php
// app/Exports/LaporanBarangExport.php

namespace App\Exports;

use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanBarangExport implements FromView
{
    protected $awal;
    protected $akhir;

    public function __construct($awal, $akhir)
    {
        $this->awal = $awal;
        $this->akhir = $akhir;
    }

    public function view(): View
    {
        $tanggalAwal = $this->awal;
        $tanggalAkhir = $this->akhir;

        // Menghitung total jumlah penjualan dan subtotal per barang
        $barang = PenjualanDetail::with('barang')
            ->select('id_barang', DB::raw('SUM(jumlah) as jumlah_penjualan'))
            ->whereBetween('created_at', ["$tanggalAwal", "$tanggalAkhir"])
            ->orWhere('created_at', 'LIKE', "%$tanggalAkhir%")
            ->orderBy('jumlah_penjualan', 'desc')
            ->groupBy('id_barang')
            ->get();

        $barangData = [];

        foreach ($barang as $penjualanDetail) {
            $barangItem = $penjualanDetail->barang;
            if ($barangItem) {
                $barangData[] = [
                    'kode_barang' => $barangItem->kode_barang,
                    'nama_barang' => $barangItem->nama_barang,
                    'harga_jual' => $barangItem->harga_jual,
                    'jumlah_penjualan' => $penjualanDetail->jumlah_penjualan,
                    'subtotal' => $penjualanDetail->jumlah_penjualan * $barangItem->harga_jual,
                ];
            }
        }

        return view('laporanbarang.excel', compact('barangData', 'tanggalAwal', 'tanggalAkhir'));
    }
}
