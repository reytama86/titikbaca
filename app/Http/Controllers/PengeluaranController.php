<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    public function index(){
        return view('pengeluaran.index');
    }

    public function data()
    {
        $pengeluaran = Pengeluaran::orderBy('id_pengeluaran','desc')->get();
        return datatables()
        ->of($pengeluaran)
        ->addIndexColumn()
        ->addColumn('created_at',function($pengeluaran){
            return tanggal_indonesia($pengeluaran->created_at);
        })
        ->addColumn('nominal', function($pengeluaran){
            return number_format($pengeluaran->nominal);
        })
        ->addColumn('aksi', function ($pengeluaran) {
            return '
                <button onclick="editForm(`'. route('pengeluaran.update', $pengeluaran->id_pengeluaran) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                <button onclick="deleteForm(`'. route('pengeluaran.destroy',$pengeluaran->id_pengeluaran). '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function store (Request $request)
    {
        $request->validate([
            'deskripsi' => 'required',
            'nominal' => 'required',
        ]);

        Pengeluaran::create($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, string $id){
        $pengeluaran = Pengeluaran::find($id);

        $pengeluaran->update($request->all());

        return response()->json('Data berhasil diupdate',200);
    }

    public function show(string $id){
        $pengeluaran = Pengeluaran::find($id);

        return response()->json($pengeluaran);
    }

    public function destroy(string $id){
        $pengeluaran = Pengeluaran::find($id);
        $pengeluaran->delete();
    }
}
