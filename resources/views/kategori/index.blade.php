@extends('layouts.master')

@section('title')
    Kategori
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
                    <button onclick="addForm('{{ route('kategori.store') }}')" class="btn btn-success btn-xs btn-flat">
                        <i class="fa fa-plus-circle"></i> Tambah
                    </button>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@includeIf('kategori.form')
@include('kategori.deletemodal')
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
                url: '{{ route('kategori.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_kategori'},
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
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Kategori');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');

        $.get(url)
        .done((response) => {
            $('#modal-form [name=nama_kategori]').val(response.nama_kategori);
        })
        .fail(() => {
            alert('Tidak dapat menampilkan data');
        });
    }

    function deleteForm(url) {
        deleteUrl = url; // Simpan URL penghapusan di variabel global
        $('#modal-delete').modal('show'); // Tampilkan modal konfirmasi
    }
</script>
@endpush


