<?php
// app/Exports/LaporanBarangExport.php

namespace App\Exports;

use App\Models\PenjualanDetail;
use App\Models\Barang;
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

        // Get the list of unique id_barang from $barang result
        $idBarangList = $barang->pluck('id_barang')->unique()->toArray();

        // Fetch the data of Barang based on id_barang list
        $barangData = Barang::whereIn('id_barang', $idBarangList)->get()->keyBy('id_barang');

        return view('laporanbarang.excel', compact('barang', 'barangData', 'tanggal', 'tanggalAkhir'));
    }
}
