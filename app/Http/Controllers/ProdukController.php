<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');
        return view('produk.index',compact('kategori'));
    }

    public function data()
    {   
        //jadi kaya ada penambahan di table produknya gitu ya alih alihnya nambah kolom nama_kategori di tabel produk
        // Melakukan query untuk mengambil data produk dengan menggabungkan tabel kategori
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
        // Memilih semua kolom dari tabel produk dan kolom nama_kategori dari tabel kategori
        ->select('produk.*', 'kategori.nama_kategori')
        // Mengurutkan hasil berdasarkan kolom kode_produk dari tabel produk secara ascending
        ->orderBy('produk.kode_produk', 'asc')
        // Menjalankan query dan mengambil hasilnya
        ->get();

        $produk = $produk->map(function ($item) {
            $discountAmount = $item->harga_jual * ($item->diskon / 100);
            $item->harga_diskon = $item->harga_jual - $discountAmount;
            return $item;
    });

    return datatables()
        ->of($produk)
        ->addIndexColumn()
        ->addColumn('select_all', function($produk) {
            return '<input type="checkbox" name="id_produk[]" value="'. $produk->id_produk .'">';
        })
        ->addColumn('kode_produk', function($produk) {
            return '<span class="label label-success">'. $produk->kode_produk . '</span>';
        })
        ->addColumn('harga_beli', function($produk) {
            return format_uang($produk->harga_beli);
        })
        ->addColumn('harga_jual', function($produk) {
            return format_uang($produk->harga_jual);
        })
        ->addColumn('diskon', function($produk) {
            return $produk->diskon . '%';
        })
        ->addColumn('harga_diskon', function($produk) {
            return format_uang($produk->harga_diskon);
        })
        ->addColumn('stok', function($produk) {
            return number_format($produk->stok, 0, ",", ".");
        })
        ->addColumn('aksi', function ($produk) {
            return '
                <button type="button" onclick="editForm(`'. route('produk.update', $produk->id_produk) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                <button type="button" onclick="deleteForm(`'. route('produk.destroy', $produk->id_produk). '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                ';
        })
        ->rawColumns(['aksi', 'kode_produk', 'select_all'])
        ->make(true);
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama_produk' => 'required|string|max:255',
        'id_kategori' => 'required|integer',
        'merk' => 'required|string|max:255',
        'harga_beli' => 'required|numeric',
        'harga_jual' => 'required|numeric',
        'diskon' => 'nullable|numeric',
        'stok' => 'required|integer',
    ]);

    $produk = Produk::latest()->first();
    $request['kode_produk'] = 'P' . str_pad((int) ($produk ? $produk->id_produk : 0) + 1, 6, '0', STR_PAD_LEFT);
    Produk::create($request->all());

    return response()->json('Data Berhasil Disimpan', 200);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::find($id);
        $produk->update($request->all());

        return response()->json('Data Berhasil Diupdate', 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::find($id);
        $produk->delete();
    }

    public function deleteSelected(Request $request){
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }
    }

    public function cetakBarcode(Request $request)
    {   
    // Mengambil semua produk berdasarkan array id_produk
    $produks = Produk::findMany($request->id_produk);

    // Memetakan setiap produk untuk menghitung harga_diskon
    $dataproduk = $produks->map(function($item) {
        $discountAmount = $item->harga_jual * ($item->diskon / 100);
        $item->harga_diskon = $item->harga_jual - $discountAmount;
        return $item;
    });

    // Jika ingin mencetak ke PDF, kembalikan sebagai array
    $pdf = PDF::loadView('produk.barcode', ['dataproduk' => $dataproduk]);
    $pdf->setPaper('a4', 'portrait');
    return $pdf->stream('produk.pdf');
    }

}
