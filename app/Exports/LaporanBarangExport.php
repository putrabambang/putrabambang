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
        $tanggal = $this->awal;
        $tanggalAkhir = $this->akhir;

        $awal = date('Y-m-d', strtotime("+1 day", strtotime($this->awal)));
        $barang = PenjualanDetail::with('barang')
            ->select('id_barang', DB::raw('SUM(jumlah) as jumlah_penjualan'))
            ->whereBetween('created_at', ["$tanggal", "$tanggalAkhir"])
            ->orWhere('created_at', 'LIKE', "%$tanggalAkhir%")
            ->orderBy('jumlah_penjualan', 'desc')
            ->groupBy('id_barang')
            ->get();

        return view('laporanbarang.excel', compact('barang', 'tanggal', 'tanggalAkhir'));
    }
}
