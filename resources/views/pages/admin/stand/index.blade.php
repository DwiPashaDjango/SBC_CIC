@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endpush

@section('title')
    Data Stand/Both
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <a href="javascript:void(0)" class="btn btn-primary btn-sm add"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama Stand</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <x-modal id="action" size="md">
        <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <div class="form-group mb-3">
                <label for="" class="mb-2">Nama Stand/Both</label>
                <input type="text" name="name" id="name" class="form-control name">
                <span class="text-danger error-name"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" id="save" class="btn btn-primary">Simpan</button>
        </div>
    </x-modal> 
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('')}}modules/datatables/datatables.min.js"></script>
    <script src="{{asset('')}}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('admin.stand.index')}}",
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "name"},
                    {data: "action"},
                ]
            });

            $(".add").click(function(e) {
                e.preventDefault();
                $("#action").modal('show');
                $("#actionLabel").html('Tambah Data Stand/Both');
                $("#save").addClass('store')
                $("#save").removeClass('update')
            })

            $(document).on('click', '.store', function(e) {
                e.preventDefault();
                
                let name = $('#name').val()

                $.ajax({
                    url: "{{route('admin.stand.store')}}",
                    method: 'POST',
                    data: {name: name},
                    dataType: 'json',
                    success: function(data) {
                        if (data.errors) {
                            $.each(data.errors, function(index, value) {
                                $(".name").addClass('is-invalid');
                                $(".error-" + index).html(value);
                                setTimeout(() => {
                                    $(".name").removeClass('is-invalid');
                                    $(".error-" + index).html('');
                                }, 5000);
                            })
                        } else {
                            $("#action").modal('hide');
                            $("#name").val('')
                            table.draw()
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
                                title: data.message
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                $.ajax({
                    url: "{{url('admin/stands/show')}}/" + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $("#action").modal('show');
                        $("#actionLabel").html('Edit Data Stand/Both');
                        $("#save").removeClass('store')
                        $("#save").addClass('update')

                        $("#id").val(data.id)
                        $("#name").val(data.name)
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $(document).on('click', '.update', function(e) {
                let id = $("#id").val()
                let name = $("#name").val()

                $.ajax({
                    url: "{{url('admin/stands/update')}}/" + id,
                    method: 'PUT',
                    data: {name: name},
                    dataType: 'json',
                    success: function(data) {
                        if (data.errors) {
                            $.each(data.errors, function(index, value) {
                                $(".name").addClass('is-invalid');
                                $(".error-" + index).html(value);
                                setTimeout(() => {
                                    $(".name").removeClass('is-invalid');
                                    $(".error-" + index).html('');
                                }, 5000);
                            })
                        } else {
                            $("#action").modal('hide');
                            $("#name").val('')
                            table.draw()
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
                                title: data.message
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $(document).on('click', '.delete', function(e) {
                let id = $(this).data('id')
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{url('admin/stands/delete')}}/" + id,
                            method: 'DELETE',
                            dataType: 'json',
                            success: function(data) {
                                table.draw()
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
                                    title: data.message
                                });
                            },
                            error: function(err) {
                                console.log(err);
                            }
                        })
                    }
                });
            })
        })
    </script>
@endpush