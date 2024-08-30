@extends('layouts.app')

@section('title')
    List Pengajuan Jadwal Penjualan 
@endsection

@push('js')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="{{asset('')}}modules/bootstrap-daterangepicker/daterangepicker.css">
@endpush

@section('content')
    <div id="msg">
                
    </div>
    <div class="card card-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <input type="month" name="start_date" id="start_date" class="form-control date">
                </div>
                <div>
                    <select name="status" id="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="completed">Bisa Berjualan</option>
                        <option value="paid">Menunggu Konfirmasi</option>
                        <option value="tidak">Tidak Bisa Berjualan</option>
                    </select>
                </div>
            </div>
            <hr class="divide">
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Nama</th>
                            <th class="text-white text-center">Nim</th>
                            <th class="text-white text-center">Kategori Makanan</th>
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
            <input type="hidden" name="old_jadwals_id" id="old_jadwals_id">
            <input type="hidden" name="new_jadwals_id" id="new_jadwals_id">
            <input type="hidden" name="tgl_penjualan" id="tgl_penjualan">
            <input type="hidden" name="kategori_products_id" id="kategori_products_id">
            <div id="msg_modal">
                
            </div>
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped text-center" id="table-switch" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">#</th>
                            <th class="text-white text-center">Nama</th>
                            <th class="text-white text-center">Nim</th>
                            <th class="text-white text-center">Kategori Makanan</th>
                            <th class="text-white text-center">Tanggal Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" id="submit" class="btn btn-primary">Switch Penjualan</button>
        </div>
    </x-modal> 

    <x-modal id="setStand" size="md">
        <input type="hidden" name="id" id="id">
        <div class="modal-body">
            <div id="msg_stand">

            </div>
            <div class="form-group mb-3">
                <label for="" class="mb-2">Nomor Induk Mahasiswa</label>
                <input type="text" disabled class="form-control name">
            </div>
            <div class="form-group mb-3">
                <label for="" class="mb-2">Nama Mahasiswa</label>
                <input type="text" disabled class="form-control nim">
            </div>
            <div class="form-group mb-3">
                <label for="" class="mb-2">Kios</label>
                <input type="text" disabled class="form-control kios">
            </div>
            <div class="form-group mb-3">
                <label for="" class="mb-2">Kategori Makanan Yang Di Jual</label>
                <input type="text" disabled class="form-control kategori">
            </div>
            <div class="form-group mb-3">
                <label for="" class="mb-2">Stand/Both</label>
                <select name="stands_id" id="stands_id" class="form-control">
                    
                </select>
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
    <script src="{{asset('')}}js/page/auth-register.js"></script>
    <script src="{{asset('')}}modules/datatables/datatables.min.js"></script>
    <script src="{{asset('')}}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('')}}modules/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{asset('')}}modules/jquery-selectric/jquery.selectric.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('admin.jadwal.index')}}",
                    method: "GET",
                    data: function(d) {
                        d.start_date = $("#start_date").val(),
                        d.status = $("#status").val()
                    }
                },
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "nama"},
                    {data: "nim"},
                    {data: "kategori"},
                    {data: "tgl_penjualan"},
                    {data: "stand"},
                    {data: "status"},
                    {data: "action"},
                ]
            });

            $("#start_date").change(function() {
                table.draw()
            })

            $("#status").change(function() {
                table.draw()
            })

            $(document).on('click', '#confirm', function(e) {
                e.preventDefault();
                let jadwals_id = $(this).data('id');
                let email = $(this).data('email');

                $("#msg").html(`<div class="alert alert-primary">Mengirim Email Ke ${email}</div>`);

                $.ajax({
                    url: "{{route('admin.jadwal.sendVerification')}}",
                    method: 'POST',
                    data: {
                        jadwals_id: jadwals_id,
                    },
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
                            title: "Berhasil Mengirimkan Email"
                        });
                        $("#message").html('');
                        table.draw()
                        $("#msg").html('')
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $(document).on('click', '#repeat', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: "{{url('admin/jadwals/list')}}/" + id,
                    method: 'GET',
                    success: function(data) {
                        $("#action").modal('show');
                        $("#actionLabel").html('Switch Penjualan');

                        $("#old_jadwals_id").val(data.id);
                        $("#tgl_penjualan").val(data.tgl_penjualan);
                        $("#kategori_products_id").val(data.kategori_products_id);

                        $("#table-switch").DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{route('admin.jadwal.getJadwalNotDate')}}",
                            columns: [
                                {data: "action"},
                                {data: "nama"},
                                {data: "nim"},
                                {data: "kategori"},
                                {data: "tgl_penjualan"},
                            ],
                            bDestroy: true
                        });
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $(document).on('change', '#value_id', function(e) {
                e.preventDefault();
                if ($(this).is(':checked')) {
                    $("#new_jadwals_id").val($(this).val())
                } else {
                    $("#new_jadwals_id").val('')
                }
            });

            $('#submit').click(function(e) {
                e.preventDefault();
                let old_jadwals_id = $("#old_jadwals_id").val();
                let new_jadwals_id = $("#new_jadwals_id").val();
                let tgl_penjualan = $("#tgl_penjualan").val();

                $("#msg_modal").html(`<div class="alert alert-primary">Menyingkronkan Data....</div>`);

                $.ajax({
                    url: "{{route('admin.jadwal.switchPenjualan')}}",
                    method: 'POST',
                    data: {
                        old_jadwals_id: old_jadwals_id,
                        new_jadwals_id: new_jadwals_id,
                        tgl_penjualan: tgl_penjualan
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.errors) {
                            $("#msg_modal").html(``);
                            $.each(data.errors, function(index, value) {
                                $("#msg_modal").html(`<div class="alert alert-danger">Pilih Salah Satu Penjual Untuk Di Switch</div>`);
                                setTimeout(() => {
                                    $("#msg_modal").html(``);
                                }, 3000);
                            })
                        } else {
                            $("#msg_modal").html(``);
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
                                title: "Sinkronisasi Berhasil."
                            });
                            $("#action").modal('hide');
                            table.draw();
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            $(document).on('click', '#show', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                let nim = $(this).data('nim');
                let kios = $(this).data('kios');
                let kategori = $(this).data('kategori');


                $("#setStand").modal('show');
                $("#setStandLabel").html('Set Stand/Both');

                $("#id").val(id);
                $(".name").val(name);
                $(".nim").val(nim);
                $(".kios").val(kios);
                $(".kategori").val(kategori);

                $.ajax({
                    url: "{{route('admin.jadwal.getStand')}}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let html = '';
                        html += '<option value="">- Pilih -</option>';
                        $.each(data, function(index, value) {
                            html += `<option value="${value.id}">${value.name}</option>`
                        })
                        $("#stands_id").html(html)
                    },
                    error: function(err) {
                        console.log(data);
                    }
                })
            })

            $("#save").click(function(e) {
                e.preventDefault();
                $("#save").addClass('disabled', true);
                $("#msg_stand").html(`<div class="alert alert-primary">Menyingkronkan Data....</div>`);

                let id = $("#id").val();
                let stands_id = $("#stands_id").val();

                $.ajax({
                    url: "{{url('admin/jadwals/update/stand')}}/" + id,
                    method: 'PUT',
                    data: {
                        stands_id: stands_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.errors) {
                            $("#msg_stand").html(``);
                            $.each(data.errors, function(index, value) {
                                $("#msg_stand").html(`<div class="alert alert-danger">Pilih Stand Untuk Berjualan</div>`);
                                setTimeout(() => {
                                    $("#msg_stand").html(``);
                                    $("#save").removeClass('disabled', false);
                                }, 3000);
                            })
                        } else {
                            $("#msg_stand").html(``);
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
                                title: "Sinkronisasi Berhasil."
                            });
                            $("#setStand").modal('hide');
                            $("#save").removeClass('disabled', false);
                            table.draw();
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
@endpush