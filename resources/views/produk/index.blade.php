@extends('layouts.master')

@section('title')
    Daftar Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection


@section('content')
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="btn-group">
                        <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-xs btn-flat">
                            <i class="fa fa-plus-circle"></i> Tambah
                        </button>
                        <button onclick="deleteSelected('{{route('produk.delete_selected')}}')" class="btn btn-danger btn-xs btn-flat">
                            <i class="fa fa-plus-trash"></i> Hapus
                        </button>
                        <button onclick="cetakBarcode('{{route('produk.cetak_barcode')}}')" class="btn btn-info btn-xs btn-flat">
                            <i class="fa fa-barcode"></i> Cetak Barcode
                        </button>
                    </div>
                </div>
                <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-striped table-bordered">
                        <thead>
                                <th width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">No</th>
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
                            <!-- Table data will be populated here -->
                        </tbody>
                    </table>
                </form>
                </div>
            </div>
        </div>
    </div>
@includeIf('produk.form')
@include('produk.deletemodal')
@include('produk.deleteselectedmodal')
@endsection

@push('scripts')
<script>
    let deleteUrl;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('produk.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'nama_kategori'},
                {data: 'merk'},
                {data: 'harga_beli'},
                {data: 'harga_jual'},
                {data: 'diskon'},
                {data: 'harga_diskon', title: 'Harga Jual Setelah Diskon'},
                {data: 'stok'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form form').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let method = form.find('[name=_method]').val();
            
            $.ajax({
                url: url,
                type: method,
                data: form.serialize(),
                success: function(response) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                },
                error: function() {
                    alert('Tidak dapat menyimpan data');
                }
            });
        });

        $('[name=select_all]').on('click', function(){
            $(':checkbox').prop('checked', this.checked);
        });

        $('#btn-confirm-delete').on('click', function () {
            $.post(deleteUrl, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                $('#modal-delete').modal('hide');
                table.ajax.reload();
            })
            .fail(() => {
                alert('Tidak dapat menghapus data');
            });
        });

        $('#btn-confirm-deleteselected').on('click', function () {
            if($('input:checked').length > 1) {
                $.post(deleteUrl, $('.form-produk').serialize())
            .done((response) => {
                $('#modal-delete').modal('hide');
                table.ajax.reload();
            })
            .fail((errors) => {
                alert('TIdak dapat menghapus data');
                return;
            });
        } else {
            alert('Pilih data yang akan dihapus');
            return;
        }
    });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Produk');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_produk]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Produk');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_produk]').focus();

        $.get(url)
        .done((response) => {
            $('#modal-form [name=nama_produk]').val(response.nama_produk);
            $('#modal-form [name=id_kategori]').val(response.id_kategori);
            $('#modal-form [name=merk]').val(response.merk);
            $('#modal-form [name=harga_beli]').val(response.harga_beli);
            $('#modal-form [name=harga_jual]').val(response.harga_jual);
            $('#modal-form [name=diskon]').val(response.diskon);
            $('#modal-form [name=stok]').val(response.stok);
        })
        .fail(() => {
            alert('Tidak dapat menampilkan data');
        });
    }

    function deleteForm(url) {
        deleteUrl = url; // Simpan URL penghapusan di variabel global
        $('#modal-delete').modal('show'); // Tampilkan modal konfirmasi
    }

    function deleteSelected(url){
        deleteUrl = url;
        $('#modal-selected-delete').modal('show');
    }

    function cetakBarcode(url){
        if($('input:checked').length < 1) {
            alert('Pilih data yang akan dicetak');
            return
        }else if($('input:checked').length < 3){
            alert('Pilih minimal 3 data yang akan dicetak');
            return;
        }else{
            $('.form-produk')
            .attr('action', url)
            .attr('target', '_blank')
            .submit();

        }
    }
</script>
@endpush


