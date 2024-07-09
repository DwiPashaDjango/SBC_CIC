@extends('layouts.app')

@section('title')
    {{$product->name}}
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/summernote/summernote-bs4.css">
@endpush

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <a href="{{route('mhs.product.index')}}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{route('mhs.product.update', ['id' => $product->id])}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="row">
                <div class="col-12">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto Product</label>
                        <div class="col-sm-6 col-md-7">
                            <div class="row">
                                <div class="col-6">
                                    <div id="image-preview" class="image-preview w-100">
                                        <label for="image-upload" id="image-label">Choose File</label>
                                        <input type="file" name="image" id="image-upload" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="" class="image-preview w-100">
                                        <img src="{{asset('storage/products/' . $product->image)}}" style="width: 100%" alt="">
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <span class="text-danger">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Product</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" value="{{$product->name}}" class="form-control @error('name') is-invalid @enderror" name="name" id="name">
                            @error('name')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jumlah Product</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty" name="qty" value="{{$product->qty}}">
                            @error('qty')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Harga Jual</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" value="{{$product->harga_jual}}" class="form-control @error('harga_jual') is-invalid @enderror" name="harga_jual" id="harga_jual">
                            @error('harga_jual')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keterangan</label>
                        <div class="col-sm-12 col-md-7">
                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" cols="30" rows="10">{{$product->keterangan}}</textarea>
                            @error('keterangan')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script src="{{asset('')}}modules/summernote/summernote-bs4.js"></script>
    <script src="{{asset('')}}modules/jquery-selectric/jquery.selectric.min.js"></script>
    <script src="{{asset('')}}modules/upload-preview/assets/js/jquery.uploadPreview.min.js"></script>
    <script src="{{asset('')}}modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="{{asset('')}}js/page/features-post-create.js"></script>
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
            $('#harga_jual').on('keyup', function() {
                $(this).val(inputRupiah($(this).val(), 'Rp. '));
            });
        });
    </script>
@endpush