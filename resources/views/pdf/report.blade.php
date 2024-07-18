<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Bulan {{$month}} Tahun {{$year}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center
        }
        
        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        #customers tr:hover {
            background-color: #ddd;
        }
        
        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #6777ef;
            color: white;
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
    <img src="{{public_path('kop_surat.jpg')}}" alt="">
    <hr>
    <h3 style="text-align: center; padding-top: 20px">Laporan Penjualan Mahasiswa Bulan {{$month}} Tahun {{$year}}</h3>
    <table id="customers">
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: center;">Mahasiswa</th>
            <th style="text-align: center;">Tanggal Laporan</th>
            <th style="text-align: center;">Nama Produk</th>
            <th style="text-align: center;">Stock</th>
            <th style="text-align: center;">Harga Jual</th>
            <th style="text-align: center;">Terjual</th>
            <th style="text-align: center;">Pendapatan</th>
            <th style="text-align: center;">Sisa Stock</th>
        </tr>
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
                <td colspan="9">Tidak Ada Laporan Penjualan Mahasiswa {{$month}} Tahun {{$year}}</td>
            </tr>
        @endforelse
    </table>

    <div class="footer">
        &copy; Copyright {{date('Y')}} - Student Bussines Corner Universitas - Catur Insan Cendikia.
    </div>
</body>

</html>