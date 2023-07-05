<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index');
    }

    public function data()
    {
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        $barang = Barang::with('kategori')
            ->select('id_kategori', DB::raw('SUM(stok) as jumlahstok1'), DB::raw('SUM(stok_gudang) as jumlahstok2'))
            ->orderBy('jumlahstok1', 'desc')
            ->groupBy('id_kategori')
             ->get();

        return datatables()
            ->of($barang)
            ->addIndexColumn()
            ->addColumn('nama_kategori', function ($barang) {
                return '<span class="label label-success">' . $barang->kategori->nama_kategori . '</span>';
            })
            ->addColumn('jumlah', function ($barang) {
                return ($barang->jumlahstok1 + $barang->jumlahstok2);
            })
            ->addColumn('aksi', function ($barang) {
                return '
                    <div class="btn-group">
                        <button onclick="editForm(`' . route('kategori.update', $barang->id_kategori) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                        <button onclick="deleteData(`' . route('kategori.destroy', $barang->id_kategori) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                ';
            })
            ->rawColumns(['aksi', 'jumlah', 'nama_kategori'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $kategori = new Kategori();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);

        return response()->json($kategori);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();

        return response(null, 204);
    }
}
