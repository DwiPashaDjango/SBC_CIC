@extends('layouts.app')

@section('title')
    Laporan Penjualan Harian - {{Auth::user()->name}}
@endsection

@push('js')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <a href="javascript:void(0)" class="btn btn-primary add"><i class="fas fa-plus"></i> Buat Laporan Baru</a>
            <a href="{{route('mhs.laporan.generatePdf')}}" target="_blank" class="btn btn-danger ml-3"><i class="fas fa-file-pdf"></i> Unduh Laporan</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Tanggal Laporan</th>
                            <th class="text-white text-center">Judul Laporan</th>
                            <th class="text-white text-center">Nama Product</th>
                            <th class="text-white text-center">Stock Barang</th>
                            <th class="text-white text-center">Harga Jual</th>
                            <th class="text-white text-center">Terjual</th>
                            <th class="text-white text-center">Pendapatan</th>
                            <th class="text-white text-center">Sisa Stock</th>
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
        <form action="{{route('mhs.laporan.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Tanggal</label>
                    <input type="text" name="" id="" class="form-control" value="{{\Carbon\Carbon::now()->translatedFormat('l, d-F-Y')}}" readonly>
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Judul Laporan</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{old('title')}}">
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Product Yang Di Jual</label>
                            <select name="products_id" id="products_id" class="form-control">
                                <option value="">- Pilih -</option>
                                @foreach ($product as $pd)
                                    <option value="{{$pd->id}}" {{$pd->id == old('products_id') ? 'selected' : ''}}>{{$pd->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Stock Product</label>
                            <input type="text" name="stock_product" id="stock_product" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Harga Jual</label>
                            <input type="text" name="harga_jual" id="harga_jual" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Product Terjual</label>
                            <input type="number" name="product_terjual" id="product_terjual" class="form-control" value="{{old('product_terjual')}}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Sisa Stock</label>
                            <input type="number" name="sisa_stock" id="sisa_stock" class="form-control" value="{{old('sisa_stock')}}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Keuntungan</label>
                            <input type="text" name="pendapatan" id="pendapatan" class="form-control" value="{{old('pendapatan')}}">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Foto Kegiatan</label>
                    <input type="file" name="image[]" id="image" class="form-control" multiple="">
                </div>
    
                <div id="showingImage" class="d-none">
                    <table id="imageTable" class="table table-bordered text-center table-sm" style="width: 30%">
                        <thead class="bg-primary"> 
                            <tr>
                                <th class="text-white">Foto</th>
                                <th class="text-white">#</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" id="submit" class="btn btn-primary"></button>
            </div>
        </form>
    </x-modal> 
    <x-modal id="showImage" size="lg">
        <div class="modal-body">
            <div class="row" id="image-append">
                
            </div>
        </div>
    </x-modal> 
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('')}}modules/datatables/datatables.min.js"></script>
    <script src="{{asset('')}}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        function inputRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

            if(ribuan){
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }

        $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{url('mahasiswa/laporans/list')}}",
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "tgl_laporan"},
                    {data: "title"},
                    {data: "product"},
                    {data: "stock"},
                    {data: "harga_jual"},
                    {data: "terjual"},
                    {data: "pendapatan"},
                    {data: "sisa_stock"},
                    {data: "action"},
                ]
            });

            $(".add").click(function(e) {
                e.preventDefault();
                $("#action").modal('show');
                $("#actionLabel").html('Buat Laporan Harian');
                $("#submit").addClass('store');
                $("#submit").html('Simpan');
                $("#submit").removeClass('update');
            })

            $('#pendapatan').on('keyup', function() {
                $(this).val(inputRupiah($(this).val(), 'Rp. '));
            });

            $("#product_terjual").prop('disabled', true);
            $("#sisa_stock").prop('disabled', true);
            $("#pendapatan").prop('disabled', true);
            $("#image").prop('disabled', true);

            $("#products_id").change(function(e) {
                e.preventDefault();

                let products_id = $(this).val();

                $.ajax({
                    url: "{{route('mhs.laporan.getProducts')}}",
                    method: 'POST',
                    data: {products_id: products_id},
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);

                        $("#product_terjual").prop('disabled', false);
                        $("#sisa_stock").prop('disabled', false);
                        $("#pendapatan").prop('disabled', false);
                        $("#image").prop('disabled', false);

                        $("#stock_product").val(data.data.qty);
                        $("#harga_jual").val(data.data.harga_jual);

                        $("#product_terjual").keyup(function(e) {
                            let value = $(this).val()
                            if (value !== '') {
                                if (value > data.data.qty) {
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
                                        icon: "warning",
                                        title: "Product Yang Terjual Tidak Bisa Lebih Besar Dari Jumlah Stock Product Yang Ada."
                                    });                                 
                                } else {
                                    $('#sisa_stock').val(data.data.qty - value)
                                }
                            } else {
                                $('#sisa_stock').val('')
                            }
                        })
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })

            var fileList = [];
            $("#image").change(function() {
                var files = this.files;
                var newFiles = Array.from(files);

                if (newFiles.length > 0) {
                    $('#showingImage').removeClass('d-none')
                    newFiles.forEach(function(file) {
                        fileList.push(file);
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var row = $('<tr>');
                            var img = $('<img class="img-thumbnail">').attr('src', e.target.result).css('width', '70px');
                            var removeButton = $('<button class="btn btn-danger btn-sm mt-2">').text('Batal').click(function() {
                                var index = fileList.indexOf(file);
                                if (index !== -1) {
                                    fileList.splice(index, 1);
                                    row.remove();
                                    updateFileInput();
                                }
                            });

                            row.append($('<td class="p-2">').append(img));
                            row.append($('<td>').append(removeButton));
                            $("#imageTable tbody").append(row);
                        };

                        reader.readAsDataURL(file);
                    });

                    updateFileInput();
                } else {
                    $("#showingImage").addClass('d-none');
                }
            });

            function updateFileInput() {
                var dataTransfer = new DataTransfer();

                fileList.forEach(function(file) {
                    dataTransfer.items.add(file);
                });

                $("#image")[0].files = dataTransfer.files;
            }
            
            $(document).on('click', '.show-image', function(e) {
                e.preventDefault();
                let public = $(this).data('public');
                let image = $(this).data('image');
                let imageArray = image.split("|");

                $("#showImage").modal('show');
                $("#showImageLabel").html('Foto Kegiatan/Penjualan')

                let html = '';
                $.each(imageArray, function(index, value) {
                    html += `<div class="col-lg-6 mb-2">
                                <img src="${public}/${value}" class="img-thumbnail" alt="">
                            </div>`;
                })
                $("#image-append").html(html)
            })

            $(document).on('click', '.destroy', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: "Warning !",
                    text: "Anda yakin ingin menghapus laporan ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{url('mahasiswa/laporans/destroy')}}/" + id,
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
                                    title: data.message
                                });
                                table.draw()
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