@extends('layouts.app')

@section('title')
    Data Product
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('mhs.product.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Foto Product</th>
                            <th class="text-white text-center">Nama Product</th>
                            <th class="text-white text-center">Stock Product</th>
                            <th class="text-white text-center">Harga Jual</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('')}}modules/datatables/datatables.min.js"></script>
    <script src="{{asset('')}}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{url('mahasiswa/products/list')}}",
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "image"},
                    {data: "name"},
                    {data: "qty"},
                    {data: "harga_jual"},
                    {data: "action"},
                ]
            })

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: "Warning !",
                    text: "Anda yakin ingin menghapus data ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{url('mahasiswa/products/destroy')}}/" + id,
                            method: 'DELETE',
                            dataType: 'json',
                            success: function(data) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });
                                Toast.fire({
                                    icon: "success",
                                    title: "Berhasil Menghapus Data."
                                });
                                table.draw()
                            },
                            error: function(err) {
                                console.log(err);
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });
                                Toast.fire({
                                    icon: "error",
                                    title: "Server Error"
                                });
                            }
                        })
                    }
                });
            })
        })
    </script>
@endpush