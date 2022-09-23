<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use App\Models\Member;
use App\Models\pengeluaran;
use App\Models\Pengeluaranbakso;
use App\Models\penjualan;
use App\Models\penggilingan;
use App\Models\barang;
use Illuminate\Http\Request;
use App\Models\penjualandetail;

class DashboardController extends Controller
{
    public function index()
    {
      $tanggal_a = date('Y-m-');
        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $kategori = Kategori::count();
        $barang = barang::count();
        $penjualan = penjualan::count();
        $penggilingan = penggilingan::count();
        $member = Member::count();
        $barangterjual = PenjualanDetail::where('created_at', 'LIKE', "%$tanggal_a%")->sum('jumlah');
        $orderan = penggilingan::where('status', 'LIKE', 1)->count();
        $data_tanggal = array();
        $data_pendapatan = array();
        $data_penggilingan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');
            $total_pemasukan = penggilingan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaranbakso = Pengeluaranbakso::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');
            $pendapatan = $total_penjualan - $total_pengeluaran;
            $pendapatan_penggilingan = $total_pemasukan - $total_pengeluaranbakso;
            
            $data_pendapatan[] += $pendapatan;
            $data_penggilingan[] += $pendapatan_penggilingan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        $tanggal_awal = date('Y-m-01');

        if (auth()->user()->level == 1) {
              return view('admin.dashboard', compact('orderan', 'kategori', 'barang', 'penjualan','penggilingan','barangterjual', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_penggilingan','data_pendapatan'));
       } else  if (auth()->user()->level == 2) {
            return view('kasir.dashboard',compact('orderan', 'kategori', 'barang', 'penjualan','penggilingan','barangterjual', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_penggilingan','data_pendapatan'));
        }
        else {
            return view('adminbakso.dashboard',compact('orderan', 'kategori', 'barang', 'penjualan','penggilingan','barangterjual', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_penggilingan','data_pendapatan'));
        }
    }
}
