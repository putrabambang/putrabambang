<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\penggilingan;
use App\Models\penggilingan_detail;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenggilinganDetailController extends Controller
{

   
    public function index(Request $request)
    {   
        $item = item::orderBy('nama_item')->get();

if ($request->has('nomor_order') && $request->nomor_order !="") {
   $id_penggilingan= $request->nomor_order;
    $penggilingan = penggilingan::find($id_penggilingan);

    return view('penggilingan_detail.index', compact('item',  'id_penggilingan', 'penggilingan'));
} else if($id_penggilingan = session('id_penggilingan')) {
            $penggilingan = penggilingan::find($id_penggilingan);

            return view('penggilingan_detail.index', compact('item',  'id_penggilingan', 'penggilingan'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('order.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        $detail = penggilingan_detail::with('item')
            ->where('id_penggilingan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;
        $grandtotal = 0;
        foreach ($detail as $item) {
            $row = array();
            $row['id_item'] = '<span class="label label-success">'. $item->item['id_item'] .'</span';
            $row['nama_item'] = $item->item['nama_item'];
            $row['harga']  = 'Rp. '. format_uang($item->harga);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penggilingan_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['total_akhir']      = '<input type="number" class="form-control input-sm quantity2" data-id="'. $item->id_penggilingan_detail .'" value="'. $item->total_akhir .'">';
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('order.destroy', $item->id_penggilingan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;
            $grandtotal += $item->total_akhir;
            $total += $item->harga * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'id_item' => '
            <div class="grandtotal hide">'. $grandtotal .'</div>
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_item' => '',
            'harga'         => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'total_akhir'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'id_item', 'jumlah','total_akhir'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $item = item::where('id_item', $request->id_item)->first();
        if (! $item) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new penggilingan_detail();
        $detail->id_penggilingan = $request->id_penggilingan;
        $detail->id_item = $item->id_item;
        $detail->harga = $item->harga;
        $detail->jumlah = 1;
        $detail->subtotal = $item->harga;
        $detail->total_akhir = $item->harga;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        if ( $request->has ('total_akhir')) { 
            $detail = penggilingan_detail::find($id);
             $detail->total_akhir = $request->total_akhir ;
             $detail->update();
        }
        else{  $detail = penggilingan_detail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga * $request->jumlah ;
        $detail->total_akhir =  $detail->harga * $request->jumlah ;
        $detail->update();
    }
      
    }

    public function destroy($id)
    {
        $detail = penggilingan_detail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm( $grandtotal = 0, $diterima = 0 )
    { 
        $bayar   = $grandtotal;
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($grandtotal),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }
}