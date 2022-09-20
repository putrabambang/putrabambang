<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;

class itemController extends Controller
{
    public function index()
    {
        return view ('item.index');
    }
    public function data()
    {
     
   $item =item::orderBy('id_item','desc') ->get();
    
       return datatables()
           ->of ($item)
           ->addColumn('select_all',function($item){
            return '
            <input type="checkbox" name="id_item[]" value="'. $item->id_item .'">
            ';
          })
           ->addColumn('harga',function ($item){
               return 'Rp. '.  format_uang($item->harga);
           })
           ->addindexColumn()
           ->addColumn('aksi',function($item){
               return'
               <div class="btn-group">
               <button type="button"onclick="editForm(`'.route('item.update',$item->id_item).'`)"  class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
               <button type="button"onclick="deleteData(`'.route('item.destroy',$item->id_item).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
               </div>
               ';
           })
           ->rawColumns(['aksi', 'select_all'])
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
           
           // $item = Member::latest()->first() ?? new Member();
           
            $item = new item();
            $item->nama_item = $request->nama_item;
            $item->harga = $request->harga;
            $item->save();
            //$item = item::latest()->first() ?? new item();
            //$request['kode_item'] = 'ABI'. tambah_nol_didepan((int)
           // $item->id_item +1, 5);
    
           // $item = item::create($request->all());
         
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
        $item =item::find($id);
    
        return response()->json($item);
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
            $item =item::find($id);
            $item->update($request->all());
    
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
            $item =item::find($id);
            $item->delete();
    
            return response(null, 204);
        }
        public function deleteSelected(Request $request)
        {
            foreach ($request->id_item as $id) {
                $item =item::find($id);
                $item->delete();
            }
    
            return response(null, 204);
        }
      

    }