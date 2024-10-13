@extends('layouts.master')
@section('title')
Daftar Pengeluaran
@endsection
@section('breadcrumb')
    @parent
    <li class="active">Daftar Pengeluaran</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{route('pengeluaran.store')}}')">
                <i class="fa fa-plus-circle"></i> Tambah
                </button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th>Tanggal</th>
                        <th>Jenis Pengeluaran</th>
                        <th>Nominal</th>
                        <th width="10%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('pengeluaran.form')
@includeIf('pengeluaran.deletemodal')
@endsection

@push('scripts')
<script>
    let deleteUrl;
    let table;
    $(function(){
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWIdth:false,
            ajax: {
                url : '{{route('pengeluaran.data')}}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'created_at'},
                {data: 'deskripsi'},
                {data: 'nominal'},
                {data: 'aksi', searchable: false, sortable: false}
            ]
        });
        $('#modal-form form').on('submit', function(e){
            e.preventDefault();
            let form = $(this);
            let method = form.find(['name=_method']).val();
            let url = form.attr('action');
            $.ajax({
                url : url,
                method : method,
                data : form.serialize(),
                success : function(response){
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                },
                error : function(){
                    alert('Tidak dapat menambahkan data');
                }
                
            });
        })

        $('#btn-confirm-delete').on('click',function(){
            $.post(deleteUrl, {
                '_token' : $('[name=csrf_token]').attr('content'),
                '_method' : 'delete'
            })
            .done((response) => {
                $('#modal-delete').modal('hide');
                table.ajax.reload();
            })
        })
    });

    function addForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Pengeluaran');
        $('#modal-form form')[0].reset();
        $('#modal-form [name=_method]').val('post');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=deskripsi]').focus();
    }

    function editForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Pengeluaran');
        $('#modal-form form')[0].reset();
        $('#modal-form [name=_method]').val('post');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=deskripsi]').focus();
        $.get(url)
        .done((response) => {
            $('#modal-form [name=deskripsi]').val(response.deskripsi);
            $('#modal-form [name=nominal]').val(response.nominal);
        })
        .fail(()=>{
            alert('Tidak dapat mengubah data');
        })
    }
    function deleteForm(url){
        deleteUrl = url;
        $('#modal-delete').modal('show');
    }
</script>

@endpush