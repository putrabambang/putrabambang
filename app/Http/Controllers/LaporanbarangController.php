<?php

namespace App\Http\Controllers;

use App\Exports\LaporanBarangExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PenjualanDetail;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

class LaporanbarangController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-01');
        $tanggalAkhir = date('Y-m-d');
        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }
        return view('laporanbarang.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function data($awal, $akhir)
    {
        $tanggal = $awal;
        $tanggalAkhir = $akhir;

        $lbarang = PenjualanDetail::with('barang')
            ->select('id_barang', DB::raw('SUM(jumlah) as jumlah_penjualan'))
            ->whereBetween('created_at', ["$tanggal", "$tanggalAkhir"])
            ->orWhere('created_at', 'LIKE', "%$tanggalAkhir%")
            ->orderBy('jumlah_penjualan', 'desc')
            ->groupBy('id_barang')
            ->get();

        return datatables()
            ->of($lbarang)
            ->addIndexColumn()
            ->addColumn('nama_barang', function ($lbarang) {
                return $lbarang->barang->nama_barang;
            })
            ->addColumn('harga_jual', function ($lbarang) {
                return 'Rp. ' . format_uang($lbarang->barang->harga_jual);
            })
            ->addColumn('jumlah', function ($lbarang) {
                return $lbarang->jumlah_penjualan;
            })
            ->addColumn('subtotal', function ($lbarang) {
                return $lbarang->jumlah_penjualan * $lbarang->barang->harga_jual;
            })
            ->rawColumns(['kode_barang'])
            ->make(true);
    }

    public function exportExcel($awal, $akhir)
    {
        $export = new LaporanBarangExport($awal, $akhir);
        return Excel::download($export, 'Laporan-barang-' . date('Y-m-d-his') . '.xlsx');
    }

    public function exportPDF($awal, $akhir)
    {
        $tanggal = $awal;
        $tanggalAkhir = $akhir;

        $barang = PenjualanDetail::with('barang')
            ->select('id_barang', DB::raw('SUM(jumlah) as jumlah_penjualan'))
            ->whereBetween('created_at', ["$tanggal", "$tanggalAkhir"])
            ->orWhere('created_at', 'LIKE', "%$tanggalAkhir%")
            ->orderBy('jumlah_penjualan', 'desc')
            ->groupBy('id_barang')
            ->get();

        $pdf = PDF::loadView('laporanbarang.pdf', compact('barang', 'awal', 'akhir'));

        return $pdf->stream('Laporan-barang-' . date('Y-m-d-his') . '.pdf');
    }
}
