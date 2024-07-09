@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
@endpush

@section('title')
    Laporan Penjualan Mahasiswa
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-body">
            <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                <thead class="bg-primary">
                    <tr>
                        <th class="text-white text-center">No</th>
                        <th class="text-white text-center">Nomor Induk Mahasiswa</th>
                        <th class="text-white text-center">Nama Mahasiswa</th>
                        <th class="text-white text-center">Kios Mahasiswa</th>
                        <th class="text-white text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('')}}modules/datatables/datatables.min.js"></script>
    <script src="{{asset('')}}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('admin.laporan.index')}}",
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "nim"},
                    {data: "name"},
                    {data: "kios"},
                    {data: "action"},
                ]
            })
        })
    </script>
@endpush