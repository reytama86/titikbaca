@extends('layouts.master');

@section('title')
    Daftar Produk
@endsection('content')

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="route{{'produk.store'}}" class="btn btn-success btn-xs btn-flat">
                        <i class="fa fa-plus-circle"></i> Tambah
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive">
            <form action="" class="form-produk">
                @csrf
                <table class="table table-striped table-bordered">
                    <thead>
                    <th>
                        <input type="checkbox" name="select_all" id="select_all">
                    </th>
                    <th width="5%">
                        No
                    </th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Diskon</th>
                    <th>Harga Jual Setelah Diskon</th>
                    <th>Stok</th>
                    <th width="10%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </form>
            </div>
        </div>
    </div>
</div>
@includeIf('produk.form');
@includeIf('produk.delete');
@includeIf('produk.deleteselected');

@push('scripts');
<script>
     let deleteUrl;
</script>
@endsection