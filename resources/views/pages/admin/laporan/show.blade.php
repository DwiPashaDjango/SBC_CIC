@extends('layouts.app')

@push('css')
@endpush

@section('title')
    Laporan Penjualan - {{$report[0]->users->name}}
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('admin.laporan.generatePdf', ['id' => $report[0]->users->id, 'month' => $month, 'years' => $years])}}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i>
                Unduh PDF
            </a>
        </div>
        <div class="card-body">
            <h5 class="text-center">Laporan Penjualan {{$report[0]->users->name}} Bulan {{$month}} Tahun {{$years}}</h5>
            <hr class="divide">
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white text-center">No</th>
                            <th class="text-white text-center">Tanggal Laporan</th>
                            <th class="text-white text-center">Nama Product</th>
                            <th class="text-white text-center">Stock Barang</th>
                            <th class="text-white text-center">Harga Jual</th>
                            <th class="text-white text-center">Terjual</th>
                            <th class="text-white text-center">Sisa Stock</th>
                            <th class="text-white text-center">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    {{\Carbon\Carbon::parse($item->tgl_laporan)->translatedFormat('d F Y')}}
                                </td>
                                <td>{{$item->product->name}}</td>
                                <td>{{$item->stock}}</td>
                                <td>{{number_format($item->product->harga_jual, 2)}}</td>
                                <td>{{$item->product_terjual}}</td>
                                <td>{{$item->sisa_stock}}</td>
                                <td>{{number_format($item->pendapatan, 2)}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">Tidak Ada Data Laporan Penjualan Di Bulan {{$month}} Tahun {{$years}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-primary">
                        <tr>
                            <th colspan="6" class="text-white">Total Pendapatan</th>
                            <th colspan="2" class="text-white">
                                Rp. {{number_format($report->sum('pendapatan'), 2)}}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush