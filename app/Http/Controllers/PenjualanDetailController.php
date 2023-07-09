<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Barang;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();
        $member = Member::orderBy('nama')->get();
        $diskon = Setting::first()->diskon ?? 0;

        if ($request->has('id_transaksi') && $request->id_transaksi != "") {
            $id_penjualan = $request->id_transaksi;
            $penjualan = Penjualan::find($id_penjualan);
            $memberSelected = $penjualan->member ?? new Member();

            return view('penjualan_detail.index', compact('barang', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
        } elseif ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            $memberSelected = $penjualan->member ?? new Member();

            return view('penjualan_detail.index', compact('barang', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with('barang')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">' . $item->barang['kode_barang'] . '</span>';
            $row['nama_barang'] = $item->barang['nama_barang'];
            $row['harga_jual'] = 'Rp. ' . format_uang($item->harga_jual);
            $row['jumlah'] = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_penjualan_detail . '" data-stok="' . $item->barang->stok . '" value="' . $item->jumlah . '">';
            $row['diskon'] = $item->diskon . '%';
            $row['subtotal'] = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi'] = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('transaksi.destroy', $item->id_penjualan_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga_jual);
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_barang' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_barang' => '',
            'harga_jual' => '',
            'jumlah' => '',
            'diskon' => '',
            'subtotal' => '',
            'aksi' => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_barang', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
{
    $barang = Barang::where('id_barang', $request->id_barang)
        ->orWhere('kode_barang', $request->kode_barang)
        ->first();
    
    if (!$barang) {
        return response()->json('Data gagal disimpan', 400);
    }

    $penjualan = PenjualanDetail::where('id_penjualan', $request->id_penjualan)
        ->where('id_barang', $barang->id_barang)
        ->first();

    if ($penjualan) {
        // Jika barang sudah ada, tambahkan jumlahnya
        $penjualan->jumlah += 1;
        $penjualan->subtotal = $penjualan->harga_jual - ($penjualan->diskon / 100 * $penjualan->harga_jual);
        $penjualan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    // Jika barang belum ada, buat sebagai baris baru
    $detail = new PenjualanDetail();
    $detail->id_penjualan = $request->id_penjualan;
    $detail->id_barang = $barang->id_barang;
    $detail->harga_jual = $barang->harga_jual;
    $detail->jumlah = 1;
    $detail->diskon = $barang->diskon;
    $detail->subtotal = $barang->harga_jual - ($barang->diskon / 100 * $barang->harga_jual);
    $detail->save();

    return response()->json('Data berhasil disimpan', 200);
}


    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga_jual);
        $detail->update();
    }

    public function destroy($id)
    {  
        $detail = PenjualanDetail::find($id); 
        $cek = Penjualan::find($detail->id_penjualan);
        
        if ($cek->total_item == 0) {
            $detail->delete();
        } else {
            $barang = Barang::find($detail->id_barang);
            $barang->stok += $detail->jumlah;
            $barang->update();
            $detail->delete();
        }
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }

    public function stok()
    {
        $stok = Barang::with('penjualan_detail')->get();
        return view('barang.stok', compact('stok'));
    }
}
