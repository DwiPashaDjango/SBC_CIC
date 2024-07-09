@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endpush

@section('title')
    Laporan Penjualan - {{$data->name}}
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('admin.laporan.generatePdf', ['id' => $data->id])}}" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Unduh Laporan</a>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{\Carbon\Carbon::parse($item->tgl_laporan)->translatedFormat('l, d F Y')}}</td>
                                <td>{{$item->title}}</td>
                                <td>{{$item->product->name}}</td>
                                <td>{{$item->stock}}</td>
                                <td>Rp. {{number_format($item->product->harga_jual, 2)}}</td>
                                <td>{{$item->product_terjual}}</td>
                                <td>Rp. {{number_format($item->pendapatan, 2)}}</td>
                                <td>{{$item->sisa_stock}}</td>
                            </tr>
                        @endforeach
                    </tbody>
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
            $("#table").DataTable();
        })
    </script>
@endpush