<?php

namespace App\Http\Controllers;

use App\Exports\LaporanBarangExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanBarangController extends Controller
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

    public function data(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $barang = PenjualanDetail::with('barang')
            ->select('id_barang', DB::raw('SUM(jumlah) as jumlah_penjualan'))
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('jumlah_penjualan', 'desc')
            ->groupBy('id_barang')
            ->get();

        return datatables()
            ->of($barang)
            ->addIndexColumn()
            ->addColumn('kode_barang', function ($barang) {
                return '<span class="label label-success">' . $barang->barang->kode_barang . '</span>';
            })
            ->addColumn('nama_barang', function ($barang) {
                return $barang->barang->nama_barang;
            })
            ->addColumn('harga_jual', function ($barang) {
                return 'Rp. ' . number_format($barang->barang->harga_jual, 0, ',', '.');
            })
            ->addColumn('jumlah', function ($barang) {
                return number_format($barang->jumlah_penjualan, 0, ',', '.');
            })
            ->addColumn('subtotal', function ($barang) {
                return 'Rp. ' . number_format($barang->jumlah_penjualan * $barang->barang->harga_jual, 0, ',', '.');
            })
            ->rawColumns(['kode_barang'])
            ->make(true);
    }

    public function exportExcel(Request $request, $awal, $akhir)
    {
        $export = new LaporanBarangExport($awal, $akhir);
        return Excel::download($export, 'Laporan-barang-' . date('Y-m-d-his') . '.xlsx');
    }

    public function exportPDF(Request $request, $awal, $akhir)
    {
        $barang = PenjualanDetail::with('barang')
            ->select('id_barang', DB::raw('SUM(jumlah) as jumlah_penjualan'))
            ->whereBetween('created_at', [$awal, $akhir])
            ->orderBy('jumlah_penjualan', 'desc')
            ->groupBy('id_barang')
            ->get();

        $data = $this->data($request);
        $barangArray = $barang->toArray();

        $pdf = PDF::loadView('laporanbarang.pdf', compact('barangArray', 'awal', 'akhir'));
        return $pdf->stream('Laporan-barang-' . date('Y-m-d-his') . '.pdf');
    }
}
