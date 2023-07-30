<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\TransferDetail;
use App\Models\Barang;
use Illuminate\Http\Request;

class TransferDetailController extends Controller
{
    public function index()
    {
        $id_transfer = session('id_transfer');
        $barang = Barang::orderBy('nama_barang')->get();

        return view('transfer_detail.index', compact('id_transfer', 'barang'));
    }

    public function data($id)
    {
        $detail = TransferDetail::with('Barang')
            ->where('id_transfer', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">' . $item->barang['kode_barang'] . '</span>';
            $row['nama_barang'] = $item->barang['nama_barang'];
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_transferdetail . '" value="' . $item->jumlah . '">';
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('transfer_detail.destroy', $item->id_transferdetail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_barang' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_barang' => '',
            'jumlah'      => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_barang', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $barang = Barang::where('id_barang', $request->id_barang)->first();
        if (!$barang) {
            return response()->json('Data gagal disimpan', 400);
        }
        $transfer = TransferDetail::where('id_transfer', $request->id_transfer)
            ->where('id_barang', $barang->id_barang)
            ->first();
        if ($transfer) {
            // Jika barang sudah ada, tambahkan jumlahnya
            $transfer->jumlah += 1;
            $transfer->subtotal = $transfer->harga_beli  * $transfer->jumlah;
            $transfer->save();

            return response()->json('Data berhasil disimpan', 200);
        }

        $detail = new TransferDetail();
        $detail->id_transfer = $request->id_transfer;
        $detail->id_barang = $barang->id_barang;
        $detail->jumlah = 1;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = TransferDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = TransferDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }
}
