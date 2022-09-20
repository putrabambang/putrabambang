<?php

namespace App\Http\Controllers;
use App\Models\Pengeluaranbakso;
use Illuminate\Http\Request;

class PengeluaranbaksoController extends Controller
{
    public function index()
    {
        return view('pengeluaranbakso.index');
    }

    public function data()
    {
        $pengeluaranbakso = Pengeluaranbakso::orderBy('id_pengeluaran', 'desc')->get();

        return datatables()
            ->of($pengeluaranbakso)
            ->addIndexColumn()
            ->addColumn('created_at', function ($pengeluaranbakso) {
                return tanggal_indonesia($pengeluaranbakso->created_at, false);
            })
            ->addColumn('nominal', function ($pengeluaranbakso) {
                return format_uang($pengeluaranbakso->nominal);
            })
            ->addColumn('aksi', function ($pengeluaranbakso) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('pengeluaranbakso.update', $pengeluaranbakso->id_pengeluaran) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('pengeluaranbakso.destroy', $pengeluaranbakso->id_pengeluaran) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
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
        $pengeluaranbakso = Pengeluaranbakso::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pengeluaranbakso = Pengeluaranbakso::find($id);

        return response()->json($pengeluaranbakso);
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
        $pengeluaranbakso = Pengeluaranbakso::find($id)->update($request->all());

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
        $pengeluaranbakso = Pengeluaranbakso::find($id)->delete();

        return response(null, 204);
    }
}