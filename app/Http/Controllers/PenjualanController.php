<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Barang;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-01');
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('penjualan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function data($awal, $akhir)
    {
        $tanggal = $awal;
        $tanggalAkhir = $akhir;
        $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));
        $penjualan = Penjualan::with('member')
            ->whereBetween('created_at', ["$tanggal", "$tanggalAkhir"])
            ->orWhere('created_at', 'LIKE', "%$tanggalAkhir%")
            ->orderBy('id_penjualan', 'desc')
            ->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('id_penjualan', function ($penjualan) {
                return '<span class="label label-success">' . $penjualan->id_penjualan . '</span>';
            })
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return ($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return ($penjualan->bayar);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return ($penjualan->created_at);
            })
            ->addColumn('kode_member', function ($penjualan) {
                $member = $penjualan->member->kode_member ?? '';
                return '<span class="label label-success">' . $member . '</spa>';
            })
            ->editColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member', 'id_penjualan'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->status = 0;
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }
    
    public function store(Request $request)
    {
        // Pengecekan apakah sudah terdapat Penjualan dengan data yang sama
        $penjualan = Penjualan::where('id_penjualan', $request->id_penjualan)->first();
    
        if ($penjualan->status == 0) {
            // Jika status penjualan adalah 0, lakukan pengurangan stok barang
            $penjualan = Penjualan::findOrFail($request->id_penjualan);
            $penjualan->id_penjualan = $request->id_penjualan;
            $penjualan->id_member = $request->id_member;
            $penjualan->total_item = $request->total_item;
            $penjualan->total_harga = $request->total;
            $penjualan->diskon = $request->diskon;
            $penjualan->bayar = $request->bayar;
            $penjualan->diterima = $request->diterima;
            $penjualan->status += $request->status;
            $penjualan->update();
    
            $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
            foreach ($detail as $item) {
                if ($item->status == 0) {
                    $item->diskon = $request->diskon;
                    $item->status += 1; // Tambahkan status 1 untuk menandai bahwa barang telah diproses
                    $item->update();
    
                    $barang = Barang::find($item->id_barang);
                    $barang->stok -= $item->jumlah;
                    $barang->update();
                }
            }
    
            return response()->json('Transaksi berhasil disimpan', 200);
        } else {
            // Jika status penjualan tidak sama dengan 0, lakukan pengecekan pada status barang di PenjualanDetail
            $penjualan = Penjualan::findOrFail($request->id_penjualan);
            $penjualan->id_penjualan = $request->id_penjualan;
            $penjualan->id_member = $request->id_member;
            $penjualan->total_item = $request->total_item;
            $penjualan->total_harga = $request->total;
            $penjualan->diskon = $request->diskon;
            $penjualan->bayar = $request->bayar;
            $penjualan->diterima = $request->diterima;
            $penjualan->update();
    
            $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
            foreach ($detail as $item) {
                if ($item->status == 0) {
                    $item->diskon = $request->diskon;
                    $item->status += 1; // Tambahkan status 1 untuk menandai bahwa barang telah diproses
                    $item->update();
    
                    $barang = Barang::find($item->id_barang);
                    $barang->stok -= $item->jumlah;
                    $barang->update();
                }
            }
    
            return response()->json('Data penjualan dengan data yang sama sudah pernah disimpan sebelumnya', 200);
        }
    }
    
    public function show($id)
    {
        $detail = PenjualanDetail::with('barang')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_barang', function ($detail) {
                return '<span class="label label-success">' . $detail->barang->kode_barang . '</span>';
            })
            ->addColumn('nama_barang', function ($detail) {
                return $detail->barang->nama_barang;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_barang'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $barang = Barang::find($item->id_barang);
            if ($barang) {
                $barang->stok += $item->jumlah;
                $barang->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('barang')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('barang')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaksi-' . date('Y-m-d-his') . '.pdf');
    }
}
