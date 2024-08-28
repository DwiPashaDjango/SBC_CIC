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
                        <th class="text-white text-center">Nomor Induk Mahasiswa</th>
                        <th class="text-white text-center">Prodi/Hima</th>
                        <th class="text-white text-center">Kios</th>
                        <th class="text-white text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                {{$item->user->name}}
                            </td>
                            <td>
                                {{$item->user->nim}}
                            </td>
                            <td>
                                {{$item->user->prodi}}
                            </td>
                            <td>
                                {{$item->user->kios}}
                            </td>
                            <td>
                                @if (!empty($month) && !empty($years))
                                    <a href="{{route('admin.laporan.show', ['month' => $month, 'years' => $years, 'id' => $item->user->id])}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                @else
                                    <a href="{{route('admin.laporan.show', ['month' => \Carbon\Carbon::now()->month, 'years' => \Carbon\Carbon::now()->year, 'id' => $item->user->id])}}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Tidak Ada Data</td>
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