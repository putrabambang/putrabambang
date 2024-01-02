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
        // Mengambil semua kategori
        $semuaKategori = Kategori::orderBy('id_kategori', 'desc')->get();
    
        // Mengambil data barang dengan jumlah stok
        $barang = Barang::select('id_kategori', DB::raw('SUM(stok) as jumlahstok1'), DB::raw('SUM(stok_gudang) as jumlahstok2'))
            ->groupBy('id_kategori')
            ->get();
    
        // Menggabungkan data kategori dengan data barang
        $kategoriBarang = $semuaKategori->map(function ($kategori) use ($barang) {
            $barangKategori = $barang->where('id_kategori', $kategori->id_kategori)->first();
            $kategori->jumlahstok1 = $barangKategori ? $barangKategori->jumlahstok1 : 0;
            $kategori->jumlahstok2 = $barangKategori ? $barangKategori->jumlahstok2 : 0;
            return $kategori;
        });
    
        // Sorting the collection based on the sum of stok columns
        $kategoriBarang = $kategoriBarang->sortByDesc(function ($item) {
            return $item->jumlahstok1 + $item->jumlahstok2;
        });
    
        // Mengonversi hasil penggabungan ke dalam format DataTable
        return datatables()
            ->of($kategoriBarang)
            ->addIndexColumn()
            ->addColumn('nama_kategori', function ($kategoriBarang) {
                return '<span class="label label-success">' . $kategoriBarang->nama_kategori . '</span>';
            })
            ->addColumn('jumlah', function ($kategoriBarang) {
                return ($kategoriBarang->jumlahstok1 + $kategoriBarang->jumlahstok2);
            })
            ->addColumn('aksi', function ($kategoriBarang) {
                return '
                    <div class="btn-group">
                        <button onclick="editForm(`' . route('kategori.update', $kategoriBarang->id_kategori) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                        <button onclick="deleteData(`' . route('kategori.destroy', $kategoriBarang->id_kategori) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
