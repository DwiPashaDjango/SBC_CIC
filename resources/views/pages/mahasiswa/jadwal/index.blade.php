@extends('layouts.app')

@section('title')
    Jadwal Penjualan - {{Auth::user()->name}}
@endsection

@push('js')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/bootstrap-daterangepicker/daterangepicker.css">
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            @php
                use Carbon\Carbon;

                $currentDay = Carbon::now()->translatedFormat('l');
            @endphp
            @if (in_array($currentDay, ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']))
                <a href="javascript:void(0)" class="btn btn-primary add"><i class="fas fa-plus"></i> Ajukam Jadwal Penjualan</a>
            @else
                <div class="alert alert-primary w-100">
                    <b>Pengajuan Jadwal Penjualan Hanya Dimulai Pada Hari Senin - Jumat</b>
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama Kios</th>
                            <th class="text-white text-center">Tanggal Penjualan</th>
                            <th class="text-white text-center">Stand Penjualan</th>
                            <th class="text-white text-center">Status</th>
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
    <x-modal id="action" size="lg">
        <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="kategori_products_id" id="kategori_products_id" value="{{Auth::user()->kategori_products}}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Nomor Induk Mahasiswa</label>
                        <input type="text" disabled class="form-control " value="{{Auth::user()->nim}}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Nama Mahasiswa</label>
                        <input type="text" disabled class="form-control " value="{{Auth::user()->name}}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Kios</label>
                        <input type="text" disabled class="form-control " value="{{Auth::user()->kios}}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="" class="mb-2">Kategori Makanan Yang Di Jual</label>
                        <input type="text" disabled class="form-control " value="{{Auth::user()->kategori_product->name}}">
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="" class="mb-2">Tanggal Penjualan</label>
                <input type="date" name="tgl_penjualan" id="tgl_penjualan" class="form-control tgl_penjualan" min="{{ date('Y-m-d') }}">
                <span class="text-danger error-tgl_penjualan"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" id="submit" class="btn btn-primary"></button>
        </div>
    </x-modal> 
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('')}}modules/datatables/datatables.min.js"></script>
    <script src="{{asset('')}}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('')}}modules/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script>
        let table = $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('mhs.jadwals.index')}}",
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "kios"},
                    {data: "tgl_penjualan"},
                    {data: "stands"},
                    {data: "status"},
                    {data: "action"},
                ]
            });

            $(".add").click(function() {
                $("#action").modal('show');
                $("#actionLabel").html('Ajukan Jadwal Penjualan');
                $("#submit").removeClass('update');
                $("#submit").addClass('submit');
                $("#submit").html('Ajukan');
                $("#tgl_penjualan").val('');
            })

            $("#tgl_penjualan").change(function() {
                let kategori_products_id = $("#kategori_products_id").val();
                let tgl_penjualan = $(this).val();
                let tanggal = new Date(tgl_penjualan);
                let hari = tanggal.getUTCDay(); 

                if (hari !== 1) {
                    Swal.fire({
                        title: "Info",
                        text: "Tanggal Memulai Penjualan Di SBC UCIC Hanya Bisa Dilakukan Pada Hari Senin.",
                        icon: "info"
                    });
                    $(this).val('');
                    $("#submit").addClass('disabled');
                    return;
                }

                let opsi = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                let tanggalIndonesia = tanggal.toLocaleDateString('id-ID', opsi);

                $.ajax({
                    url: "{{route('mhs.chcek.jadwal')}}",
                    method: 'POST',
                    data: {
                        tgl_penjualan: tgl_penjualan,
                        kategori_products_id: kategori_products_id
                    },
                    success: function(data) {
                        if (data.jadwal.length > 0) {
                            Swal.fire({
                                title: "Opppsss",
                                text: tanggalIndonesia 
                                + 
                                ` sudah ada yang mendaftar dengan kategori makanan yang sama. Silakan gunakan tanggal lain.`,
                                icon: "info"
                            });
                            $("#submit").addClass('disabled');
                            $("#submit").removeClass('submit');
                        } else {
                            $("#submit").addClass('submit');
                            $("#submit").removeClass('disabled');
                        }
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
                });
            });

            $(document).on('click', '.submit', function(e) {
                let kategori_products_id = $("#kategori_products_id").val();
                let tgl_penjualan = $("#tgl_penjualan").val();
                $.ajax({
                    url: "{{route('mhs.save.jadwal')}}",
                    method: 'POST',
                    data: {
                        tgl_penjualan: tgl_penjualan,
                        kategori_products_id: kategori_products_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.errors) {
                            $.each(data.errors, function(index, value) {
                                $(".tgl_penjualan").addClass('is-invalid');
                                $(".error-" + index).html(value);
                                setTimeout(() => {
                                    $(".tgl_penjualan").removeClass('is-invalid');
                                    $(".error-" + index).html('');
                                }, 5000);
                            })
                        } else {
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
                                title: "Berhasil Menyimpan Data."
                            });
                            $("#action").modal('hide');
                            $("#tgl_penjualan").val('');
                            table.draw()
                        }
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
            })

            $(document).on('click', '#edit', function(e) {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{url('/mahasiswa/jadwals')}}/" + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $("#action").modal('show');
                        $("#actionLabel").html('Edit Jadwal Penjualan');
                        $("#submit").removeClass('submit');
                        $("#submit").addClass('update');
                        $("#submit").addClass('d-block');
                        $("#submit").html('Ubah');

                        $("#id").val(data.data.id);
                        $("#tgl_penjualan").val(data.data.tgl_penjualan);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $(document).on('click', '.update', function(e) {
                e.preventDefault();
                let id = $("#id").val();
                let tgl_penjualan = $("#tgl_penjualan").val();
                $.ajax({
                    url: "{{url('/mahasiswa/jadwals/update')}}/" + id,
                    method: 'PUT',
                    data: {
                        tgl_penjualan: tgl_penjualan
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.errors) {
                            $.each(data.errors, function(index, value) {
                                $(".tgl_penjualan").addClass('is-invalid');
                                $(".error-" + index).html(value);
                                setTimeout(() => {
                                    $(".tgl_penjualan").removeClass('is-invalid');
                                    $(".error-" + index).html('');
                                }, 5000);
                            })
                        } else {
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
                                title: "Berhasil Mengubah Data."
                            });
                            $("#action").modal('hide');
                            $("#tgl_penjualan").val('');
                            table.draw()
                        }
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
            })

            $(document).on('click', '#delete', function(e) {
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
                            url: "{{url('mahasiswa/jadwals/destroy')}}/" + id,
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
                                    title: "Berhasil Mengubah Data."
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

            $(document).on('click', '#accepted', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: "{{url('mahasiswa/jadwals/accepted')}}/" + id,
                    method: 'PUT',
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
                            title: "Berhasil Menyimpan Data."
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
            })

            $(document).on('click', '#rejected', function(e) {
                let id = $(this).data('id');
                let tgl = $(this).data('tgl');
                Swal.fire({
                    title: "Warning !",
                    text: "Anda yakin tidak bisa berjaulan pada tanggal " + tgl,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{url('mahasiswa/jadwals/rejected')}}/" + id,
                            method: 'PUT',
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
                                    title: "Berhasil Menyimpan Data."
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

            $(document).on('click', '#show', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: "{{url('/mahasiswa/jadwals')}}/" + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $("#action").modal('show');
                        $("#actionLabel").html('Detail Jadwal Penjualan');
                        $("#submit").html('Ubah');

                        $("#id").val(data.data.id);
                        $("#tgl_penjualan").val(data.data.tgl_penjualan);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
@endpush