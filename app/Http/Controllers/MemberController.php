<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.index');
    }

    public function data()
    {
    // Ambil data dari database
    $member = Member::orderBy('kode_member')->get();

    // Return data ke DataTables
    return datatables()
        ->of($member)
        ->addIndexColumn() // Menambahkan nomor urut otomatis
        ->addColumn('select_all', function($member) {
            return '<input type="checkbox" name="id_member[]" value="'. $member->id_member .'">';
        })
        ->addColumn('kode_member', function($member){
            return '<span class="label label-success">'. $member->kode_member .'</span>';
        })
        ->addColumn('aksi', function ($member) {
            return '
                <button type="button" onclick="editForm(`'. route('member.update', $member->id_member) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-edit"></i></button>
                <button type="button" onclick="deleteForm(`'. route('member.destroy', $member->id_member). '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                ';
        })
        ->rawColumns(['aksi', 'select_all', 'kode_member']) // Agar HTML di kolom 'aksi' dan 'kode_member' tidak di-escape
        ->make(true); // Mengembalikan data dalam format JSON
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
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'telepon' => 'required|string|max:15',
    ]);

    $member = Member::latest()->first();
    $kode_member = (int) $member->kode_member + 1 ?? 1;
    $member = new Member();
    $member->kode_member = tambah_nol_didepan($kode_member, 5);
    $member->nama = $request->nama;
    $member->alamat = $request->alamat;
    $member->telepon = $request->telepon;
    $member->save();

    return response()->json('Data Berhasil Disimpan', 200);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::find($id);

        return response()->json($member);
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
        $member = Member::find($id)->update($request->all());

        return response()->json('Data Berhasil Diupdate', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Member = Member::find($id);
        $Member->delete();
    }

    public function cetakMember(Request $request)
    { 
        $datamember = collect(array());
        foreach($request->id_member as $id){
            $member = Member::find($id);
            $datamember[] = $member;
        }
        $datamember = $datamember->chunk(2);
        $no = 1;
    // Jika ingin mencetak ke PDF, kembalikan sebagai array
    $pdf = PDF::loadView('member.cetak', compact('datamember', 'no'));
    $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
    return $pdf->stream('member.pdf');
    }
}
