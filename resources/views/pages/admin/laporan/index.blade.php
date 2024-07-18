@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endpush

@section('title')
    Laporan Penjualan Mahasiswa
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.laporan.index')}}" method="GET">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group mb-3">
                            <label for="">Bulan</label>
                            @php
                                $months = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ];
                            @endphp
    
                            <select name="month" id="month" class="form-control">
                                <option value="">- Pilih -</option>
                                @foreach ($months as $number => $name)
                                    <option value="{{ $number }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group mb-3">
                            <label for="">Tahun</label>
                            @php
                                $currentYear = date('Y');
                            @endphp
                            <select name="year" id="year" class="form-control">
                                <option value="">- Pilih -</option>
                                @for ($year = $currentYear; $year > $currentYear - 5; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group mb-3">
                            <label for=""></label>
                            <button type="submit" class="btn btn-primary w-100" style="margin-top: 10px"><i class="fas fa-filter"></i> Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            @if (!empty($month) && !empty($year))
                <a href="{{route('admin.laporan.generatePdf2', ['month' => $month, 'year' => $years])}}" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
            @else
                <a href="{{route('admin.laporan.generatePdf2', ['month' => \Carbon\Carbon::now()->month, 'year' => \Carbon\Carbon::now()->year])}}" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
            @endif
        </div>
        <div class="card-body">
            <div class="mb-3 text-center">
                @if (!empty($month) && !empty($year))
                    <h5>Laporan Penjualan Mahasiswa Bulan {{$month}} Tahun {{$years}}</h5>
                @else
                    <h5>Laporan Penjualan Mahasiswa Bulan {{\Carbon\Carbon::now()->month}} Tahun {{\Carbon\Carbon::now()->year}}</h5>
                @endif
            </div>
            <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                <thead class="bg-primary">
                    <tr>
                        <th class="text-white text-center">No</th>
                        <th class="text-white text-center">Mahasiswa</th>
                        <th class="text-white text-center">Tanggal Laporan</th>
                        <th class="text-white text-center">Nama Produk</th>
                        <th class="text-white text-center">Stock</th>
                        <th class="text-white text-center">Harga Jual</th>
                        <th class="text-white text-center">Terjual</th>
                        <th class="text-white text-center">Pendapatan</th>
                        <th class="text-white text-center">Sisa Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @forelse ($datas->groupBy('users_id') as $userId => $userData)
                        @php $rowspan = count($userData); @endphp
                        @foreach ($userData as $index => $item)
                            <tr>
                                @if ($index == 0)
                                    <td rowspan="{{$rowspan}}">{{$no++}}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $item->users->name }}</td>
                                @endif
                                <td>{{\Carbon\Carbon::parse($item->tgl_laporan)->translatedFormat('l, d F Y')}}</td>
                                <td>{{$item->product->name}}</td>
                                <td>{{$item->stock}}</td>
                                <td>{{number_format($item->product->harga_jual, 2)}}</td>
                                <td>{{$item->product_terjual}}</td>
                                <td>{{number_format($item->pendapatan, 2)}}</td>
                                <td>{{$item->sisa_stock}}</td>
                            </tr>
                        @endforeach
                    @empty 
                        <tr>
                            <td colspan="9">Tidak Ada Laporan Penjualan Mahasiswa Bulan {{$month}} Tahun {{$years}}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('')}}modules/datatables/datatables.min.js"></script>
    <script src="{{asset('')}}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
@endpush