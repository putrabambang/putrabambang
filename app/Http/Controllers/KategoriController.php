<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kategori;
use App\Models\barang;
use Illuminate\Support\Facades\DB;
class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('kategori.index');
    }
public function data()
{
   $kategori = kategori::orderBy('id_kategori','desc') ->get();
  // $jumlah = barang::where('id_kategori', 'LIKE', "%$tanggal_a%")->sum('stok');
 //  $kategori = kategori::orderBy('id_kategori','desc') ->get();
  // $barang1 = barang::where('id_kategori', 'LIKE', "%$tanggal_a%")->sum('stok');
   $barang= barang::with('kategori')
   ->select('id_kategori',
   DB::raw('SUM(stok) as jumlahstok1'), 
   DB::raw('SUM(stok_gudang) as jumlahstok2'))
   ->ORDERBY ('jumlahstok1', 'desc')
   ->GROUPBY('id_kategori')
   ->get();
   return datatables()
       ->of ($barang)
       ->addindexColumn()
       ->addColumn('nama_kategori', function ($barang) {
        return '<span class="label label-success">'.$barang->kategori->nama_kategori.'</span>';
    })
       ->addColumn('jumlah', function ($barang) {
        return ($barang->jumlahstok1 + $barang->jumlahstok2);
    })
       ->addColumn('aksi',function($barang){
           return'
           <div class="btn-group">
           <button onclick="editForm(`'.route('kategori.update',$barang->id_kategori).'`)"  class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
           <button onclick="deleteData(`'.route('kategori.destroy',$barang->id_kategori).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
           </div>
           ';
       })
       ->rawColumns(['aksi','jumlah','nama_kategori'])
       ->make(true);

}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    $kategori = new Kategori();
    $kategori->nama_kategori = $request->nama_kategori;
    $kategori->save();
    
    return response()->json('Data berhasil disimpan',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    $kategori = kategori::find($id);

    return response()->json($kategori);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();

        return response(null, 204);
    }
}
