<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Mahasiswa {{$report[0]->users->name}} Bulan {{$month}} Tahun {{$years}}</title>
    <link rel="stylesheet" href="{{public_path('css/style.css')}}">
    <link rel="stylesheet" href="{{public_path('modules/bootstrap/css/bootstrap.min.css')}}">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            border-top: 1px solid #000;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <img src="{{public_path('kop_surat_3.jpg')}}" style="width: 100%">
    <hr class="divide" style="border-top: 3px solid #000;">

    <h5 class="text-center mt-5">Laporan Penjualan Mahasiswa {{$report[0]->users->name}} Bulan {{$month}} Tahun {{$years}}</h5>

     <table class="table table-bordered table-striped text-center mt-3" id="table" style="width: 100%">
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

    <div class="footer">
        &copy; Copyright {{date('Y')}} - Student Bussines Corner Universitas - Catur Insan Cendikia.
    </div>
</body>

</html>