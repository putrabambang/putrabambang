<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pengeluaran;
use App\Models\Pengeluaranbakso;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Penggilingan;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
    {
        
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth();
        $data = DB::table('penjualan_detail')
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(jumlah) as total'))
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->groupBy('date')
        ->get();
        $tanggal_a = date('Y-m-');
        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $kategori = Kategori::count();
        $barang = Barang::count();
        $penjualan = Penjualan::count();
        $penggilingan = Penggilingan::count();
        $member = Member::count();
        $barangterjual = PenjualanDetail::where('created_at', 'LIKE', "%$tanggal_a%")->sum('jumlah');
        $pembelian = Pembelian::count();
        $orderan = Penggilingan::where('status', 'LIKE', 1)->count();
        $data_tanggal = [];
        $data_pendapatan = [];
        $data_penggilingan = [];
        // Mengambil data penjualan per bulan dari database
        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int)substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');
            $total_pemasukan = Penggilingan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaranbakso = Pengeluaranbakso::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');
            $pendapatan = $total_penjualan - $total_pengeluaran;
            $pendapatan_penggilingan = $total_pemasukan - $total_pengeluaranbakso;

            $data_pendapatan[] += $pendapatan;
            $data_penggilingan[] += $pendapatan_penggilingan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }
        // Mendapatkan tahun saat ini
        $currentYear = Carbon::now()->year;

        // Mendapatkan tahun sebelumnya
        $previousYear = $currentYear - 1;

        // Mengambil data penjualan dari tahun ini dan tahun sebelumnya
        $dataTahunIni = Penjualan::whereYear('created_at', $currentYear)
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(bayar) as total_pendapatan')
            )
            ->groupBy('bulan')
            ->get();

        $dataTahunSebelumnya = Penjualan::whereYear('created_at', $previousYear)
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(bayar) as total_pendapatan')
            )
            ->groupBy('bulan')
            ->get();

        // Format data bulan dan total pendapatan untuk tahun ini
        $monthNames = [
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        ];

        $formattedDataTahunIni = $dataTahunIni->map(function ($item) use ($monthNames) {
            $monthName = $monthNames[$item->bulan];
            return [$monthName, $item->total_pendapatan];
        });

        // Format data bulan dan total pendapatan untuk tahun sebelumnya
        $formattedDataTahunSebelumnya = $dataTahunSebelumnya->map(function ($item) use ($monthNames) {
            $monthName = $monthNames[$item->bulan];
            return [$monthName, $item->total_pendapatan];
        });
        $tanggal_awal = date('Y-m-01');

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact(
                'orderan',
                'kategori',
                'barang',
                'penjualan',
                'penggilingan',
                'barangterjual',
                'member',
                'tanggal_awal',
                'tanggal_akhir',
                'data_tanggal',
                'data_penggilingan',
                'data_pendapatan',
                'data',
                'formattedDataTahunIni',
                'formattedDataTahunSebelumnya',
                'pembelian'
            ));
        } elseif (auth()->user()->level == 2) {
            return view('kasir.dashboard', compact(
                'orderan',
                'kategori',
                'barang',
                'penjualan',
                'penggilingan',
                'barangterjual',
                'member',
                'tanggal_awal',
                'tanggal_akhir',
                'data_tanggal',
                'data_penggilingan',
                'data_pendapatan'
            ));
        } else {
            return view('adminbakso.dashboard', compact(
                'orderan',
                'kategori',
                'barang',
                'penjualan',
                'penggilingan',
                'barangterjual',
                'member',
                'tanggal_awal',
                'tanggal_akhir',
                'data_tanggal',
                'data_penggilingan',
                'data_pendapatan'
            ));
        }
    }
}
