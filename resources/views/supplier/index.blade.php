@extends('layouts.master')
@section('title')
Supplier
@endsection
@section('breadcrumb')
    @parent
    <li class="active">Daftar Supplier</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{route('supplier.store')}}')" class="btn btn-success btn-xs btn-flat">
                <i class="fa fa-plus-circle"></i> Tambah
                </button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('supplier.form')
@includeIf('supplier.deletemodal')
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
            autoWidth: false,
            ajax: {
                url : '{{route('supplier.data')}}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama'},
                {data: 'telepon'},
                {data: 'alamat'},
                {data: 'aksi', searchable: false, sortable: false}
            ]
        });
        $('.modal-form form').on('submit', function(e){
            e.preventDefault();
            let form = $(this);
            let method = form.find(['name=_method']).val();
            let url = form.attr('action');
            $.ajax({
                url : url,
                type : method,
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
                '_token': $('[name=csrf-token]').attr('content'),
                '_method' : 'delete'
            })
            .done((response) => {
                $('#modal-delete').modal('hide');
                table.ajax.reload();
            })
            .fail(()=>{
                alert('tidak dapat menghapus data');
            })
        })
    });

    function addForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').title('Tambah Supplier');
        $('#modal-form form')[0].reset();
        $('#modal-form [name=_method]').val('post');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=nama]').focus();
    }

    function editForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Supplier');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();
        $.get(url)
        .done((response) => {
            $('#modal-form [name=nama]').val(response.nama);
            $('#modal-form [name=telepon]').val(response.telepon);
            $('#modal-form [name=alamat]').val(response.alamat);
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