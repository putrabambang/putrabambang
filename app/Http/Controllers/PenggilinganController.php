<?php

namespace App\Http\Controllers;
use App\Models\Penggilingan;
use App\Models\Penggilingan_detail;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class PenggilinganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('penggilingan.index');
    }
    public function data()
    {
        $penggilingan = penggilingan::orderBy('id_penggilingan', 'desc')->get();

        return datatables()
            ->of($penggilingan)
            ->addIndexColumn()
            ->addColumn('id_penggilingan', function ($penggilingan) {
                return '<span class="label label-success">'. $penggilingan->id_penggilingan .'</span>';
            })
            ->addColumn('total_item', function ($penggilingan) {
                return format_uang($penggilingan->total_item);
            })
            ->addColumn('total_harga', function ($penggilingan) {
                return 'Rp. '. format_uang($penggilingan->total_harga);
            })
            ->addColumn('total_akhir', function ($penggilingan) {
              //  return 'Rp. '. format_uang($penggilingan->total_akhir);
              $total_akhir=$penggilingan->total_akhir;
              $total_awal=$penggilingan->total_harga;
                if ($total_akhir==$total_awal) {
                     //'<span class="label label-danger "><i class="">$total_akhir</i></span>';
                    $ubah ='<span class="label label-success">'.'Rp. '. format_uang($penggilingan->total_akhir).'</span>';
                }else {
                    $ubah ='<span class="label label-danger">'.'Rp. '. format_uang($penggilingan->total_akhir).'</span>';
                }
                return $ubah ;
            })
            ->addColumn('tanggal', function ($penggilingan) {
                return ($penggilingan->created_at);
            })
            ->addColumn('status', function ($penggilingan) {
                $status= $penggilingan->status ;
       if($status == 1){
           $cek = '<span onclick="konfirmasi(`'.route('penggilingan.konfirmasi',$penggilingan->id_penggilingan).'`)" class="label label-danger "><i class="">belum di ambil</i></span>';

       }else{

           $cek = '<span onclick="batalkonfir(`'.route('penggilingan.batalkonfir',$penggilingan->id_penggilingan).'`)" class="label label-success "><i class="">sudah di ambil</i></span>' ;
    
       }
                return $cek ;
            })
            ->editColumn('kasir', function ($penggilingan) {
                return $penggilingan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penggilingan){
                return '
                <div class="btn-group">
                    <button onclick="konfirmasi(`'.route('penggilingan.konfirmasi',$penggilingan->id_penggilingan).'`)" class="btn btn-success btn-xs btn-flat"><i class="">konfirmasi</i></button>
                   <button onclick="showDetail(`'.route('penggilingan.show',$penggilingan->id_penggilingan).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'.route('penggilingan.destroy',$penggilingan->id_penggilingan).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                     </div>
                ';
            })
            ->rawColumns(['aksi', 'status','id_penggilingan','total_akhir'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $penggilingan = new penggilingan();
        $penggilingan->total_item = 0;
        $penggilingan->total_harga = 0;
        $penggilingan->total_akhir = 0;
        $penggilingan->bayar = 0;
        $penggilingan->status = 1;
        $penggilingan->diterima = 0;
        $penggilingan->id_user = auth()->id();
        $penggilingan->save();

        session(['id_penggilingan' => $penggilingan->id_penggilingan]);
        return redirect()->route('order.index');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $penggilingan = penggilingan::findOrFail($request->id_penggilingan);
        $penggilingan->total_item = $request->total_item;
        $penggilingan->total_harga = $request->total;
        $penggilingan->total_akhir= $request->bayar;
        $penggilingan->bayar = $request->bayar;
        $penggilingan->status = $request->status;
        $penggilingan->diterima = $request->diterima;
        $penggilingan->update();

        return redirect()->route('order.selesai');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = penggilingan_detail::with('item')->where('id_penggilingan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('id_item', function ($detail) {
                return '<span class="label label-success">'. $detail->item->id_item .'</span>';
            })
            ->addColumn('nama_item', function ($detail) {
                return $detail->item->nama_item;
            })
            ->addColumn('harga', function ($detail) {
                return 'Rp. '. format_uang($detail->harga);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->addColumn('total_akhir', function ($detail) {
                return 'Rp. '. format_uang($detail->total_akhir);
            })
            ->rawColumns(['id_item'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penggilingan= penggilingan::find($id);
        $penggilingan->status = $request->status;
        $penggilingan->update();

    return response()->json('Data berhasil disimpan', 200);
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
        $penggilingan= penggilingan::find($id);
        $penggilingan->status = $request->status;
        $penggilingan->update();

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
        $penggilingan = penggilingan::find($id);
        $detail    = penggilingan_detail::where('id_penggilingan', $penggilingan->id_penggilingan);
        $detail->delete();
        $penggilingan->delete();


        return response(null, 204);
    }
    public function selesai()
    {
        $setting = Setting::first();

        return view('penggilingan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penggilingan = penggilingan::find(session('id_penggilingan'));
        if (! $penggilingan) {
            abort(404);
        }
        $detail = penggilingan_detail::with('item')
            ->where('id_penggilingan', session('id_penggilingan'))
            ->get();
        
        return view('penggilingan.nota_kecil', compact('setting', 'penggilingan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penggilingan = penggilingan::find(session('id_penggilingan'));
        if (! $penggilingan) {
            abort(404);
        }
        $detail = penggilinganDetail::with('item')
            ->where('id_penggilingan', session('id_penggilingan'))
            ->get();

        $pdf = PDF::loadView('penggilingan.nota_besar', compact('setting', 'penggilingan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('order-'. date('Y-m-d-his') .'.pdf');
    }
   
    public function konfirmasi($id){
        ///dd($id);

        $penggilingan= penggilingan::find($id);
        $penggilingan->status = 2;
        $penggilingan->update();
     
    return response()->json('Data berhasil disimpan', 200);
    }

    public function batalkonfir($id){
        ///dd($id);

        $penggilingan= penggilingan::find($id);
        $penggilingan->status = 1;
        $penggilingan->update();
     
    return response()->json('Data berhasil disimpan', 200);
    }

}

