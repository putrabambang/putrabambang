<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\kategori;
use Alert;
use PDF;

class barangcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = kategori::all()->pluck('nama_kategori','id_kategori');
        return view ('barang.index',compact('kategori'));
    }
public function data()
{
   $barang = barang::leftJoin('kategori','kategori.id_kategori','barang.id_kategori')
   ->select('barang.*','nama_kategori')
   //->orderBy('kode_barang','asc') 
   ->get();

   return datatables()
       ->of ($barang)
       ->addColumn('select_all',function($barang){
        return '
        <input type="checkbox" name="id_barang[]" value="'. $barang->id_barang .'">
        ';
    })
       ->addColumn('kode_barang',function ($barang){
        return '<span class="label label-success">'.$barang->kode_barang.'</span>';
    })
       ->addColumn('harga_jual',function ($barang){
           return  'Rp. '.format_uang ($barang->harga_jual);
       })
       ->addColumn('modal',function ($barang){
        return'Rp. '.format_uang($barang->modal);
    })
       ->addColumn('subtotal',function ($barang){
        return (($barang->stok + $barang->stok_gudang) * $barang->harga_jual);
    })
       ->addindexColumn()
       ->addColumn('aksi',function($barang){
           return'
           <div class="btn-group">
           <button type="button"onclick="editForm(`'.route('barang.update',$barang->id_barang).'`)"  class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
           <button type="button"onclick="deleteData(`'.route('barang.destroy',$barang->id_barang).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
           </div>
           ';
       })
       ->rawColumns(['aksi','kode_barang', 'select_all'])
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
       $setting=Setting::first();

       $cek = barang::count();
       if($cek == 0){
        
           //$kodebarang = 'ABI00001';
        $kodebarang =$setting->kode_barang.'00001';
       }else{

           $ambil = barang::latest()->first() ?? new Member();
           $nourut = (int)substr($ambil->kode_barang, -5) +1;
           $kodebarang = $setting->kode_barang. tambah_nol_didepan($nourut,5);
          // dd($kodebarang);
       }
        $barang = new barang();
        $barang->kode_barang = $kodebarang;
        $barang->nama_barang = $request->nama_barang;
        $barang->id_kategori = $request->id_kategori;
        $barang->harga_jual = $request->harga_jual;
        $barang->modal= $request->modal;
        $barang->stok = $request->stok;
        $barang->stok_gudang = $request->stok_gudang;
        $barang->save();
  
       // Alert::success('Success Title', 'Success Message');
       return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
     
    public function show($id)
    {
    $barang = barang::find($id);

    return response()->json($barang);
    }


/////
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
        $barang = barang::find($id);
        $barang->kode_barang = $request->kode_barang;
        $barang->nama_barang = $request->nama_barang;
        $barang->id_kategori = $request->id_kategori;
        $barang->harga_jual = $request->harga_jual;
        $barang->stok = $request->stok;
        $barang->modal= $request->modal;
        $barang->stok_gudang = $request->stok_gudang;
        $barang->stok  +=  $request->tambahstok;
        $barang->update();


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
        $barang = barang::find($id);
        $barang->delete();

        return response(null, 204);
    }
    public function deleteSelected(Request $request)
    {
        foreach ($request->id_barang as $id) {
            $barang = barang::find($id);
            $barang->delete();
        }

        return response(null, 204);
    }
    public function cetakBarcode(Request $request)
    {
        $jumlahcetak=$request->jumlahcetak;
        $databarang = array();
        foreach ($request->id_barang as $id) {
            $barang = barang::find($id);
            $databarang[] = $barang;
        }

        $no  = 1;
        $pdf = PDF::loadView('barang.barcode', compact('databarang', 'no','jumlahcetak'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('barang.pdf');
    }
}


