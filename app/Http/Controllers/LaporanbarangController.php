<?php

namespace App\Http\Controllers;
use App\Exports\LaporanBarangExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PenjualanDetail;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\View;
use DataTables;
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
        $tanggalAwal = $awal;
    $tanggalAkhir = $akhir;

    // Menghitung total jumlah penjualan dan subtotal per barang
    $barang = PenjualanDetail::with('barang')
        ->select('id_barang', DB::raw('SUM(jumlah) as jumlah_penjualan'))
        ->whereBetween('created_at', ["$tanggalAwal", "$tanggalAkhir"])
        ->orWhere('created_at', 'LIKE', "%$tanggalAkhir%")
        ->orderBy('jumlah_penjualan', 'desc')
        ->groupBy('id_barang')
        ->get();

    // Mengambil data kode barang dari tabel Barang berdasarkan id_barang yang ada di tabel PenjualanDetail
    $barangData = collect([]);

    foreach ($barang as $penjualanDetail) {
        $barangItem = $penjualanDetail->barang;
        if ($barangItem) {
            $barangData->push([
                'id_barang' => $penjualanDetail->id_barang,
                'kode_barang' => $barangItem->kode_barang,
                'jumlah_penjualan' => $penjualanDetail->jumlah_penjualan,
                // Anda bisa tambahkan kolom lain dari tabel Barang yang ingin ditampilkan di sini
            ]);
        }
    }

    return DataTables::of($barangData)
        ->addIndexColumn()
        ->addColumn('kode_barang', function ($barang) {
            return '<span class="label label-success">' . $barang['kode_barang'] . '</span>';
        })
        ->addColumn('nama_barang', function ($barang) {
            return Barang::find($barang['id_barang'])->nama_barang;
        })
        ->addColumn('harga_jual', function ($barang) {
            $harga_jual = Barang::find($barang['id_barang'])->harga_jual;
            return 'Rp. ' . format_uang($harga_jual);
        })
        ->addColumn('jumlah', function ($barang) {
            return $barang['jumlah_penjualan'];
        })
        ->addColumn('subtotal', function ($barang) {
            $harga_jual = Barang::find($barang['id_barang'])->harga_jual;
            return $barang['jumlah_penjualan'] * $harga_jual;
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

        $pdf = PDF::loadView('laporanbarang.pdf', compact('barangdata', 'awal', 'akhir'));


        return $pdf->stream('Laporan-barang-' . date('Y-m-d-his') . '.pdf');
    }

}