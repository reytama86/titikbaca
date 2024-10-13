<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(){
        return view('supplier.index');
    }

    public function data()
    {
        $supplier = Supplier::orderBy('id_supplier','desc')->get();
        return datatables()
        ->of($supplier)
        ->addIndexColumn()
        ->addColumn('aksi', function ($supplier) {
            return '
                <button type="button" onclick="editForm(`'. route('supplier.update', $supplier->id_supplier) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                <button type="button" onclick="deleteForm(`'. route('supplier.destroy',$supplier->id_supplier). '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function store (Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required|integer',
        ]);

        Supplier::create($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, string $id){
        $supplier = Supplier::find($id);

        $supplier->update($request->all());

        return response()->json('Data berhasil diupdate',200);
    }

    public function show(string $id){
        $supplier = Supplier::find($id);

        return response()->json($supplier);
    }

    public function destroy(string $id){
        $supplier = Supplier::find($id);
        $supplier->delete();
    }
}
