<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pengeluaran;
use App\Models\Pengeluaranbakso;
use App\Models\Penjualan;
use App\Models\Penggilingan;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Penjualandetail;

class DashboardController extends Controller
{
    public function index()
    {
        $tanggal_a = date('Y-m-');
        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $kategori = Kategori::count();
        $barang = Barang::count();
        $penjualan = Penjualan::count();
        $penggilingan = Penggilingan::count();
        $member = Member::count();
        $barangterjual = PenjualanDetail::where('created_at', 'LIKE', "%$tanggal_a%")->sum('jumlah');
        $orderan = Penggilingan::where('status', 'LIKE', 1)->count();
        $data_tanggal = [];
        $data_pendapatan = [];
        $data_penggilingan = [];

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
                'data_pendapatan'
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
