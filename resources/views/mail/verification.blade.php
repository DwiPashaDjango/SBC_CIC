<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Jadwal Penjualan Mahasiswa Di SBC</title>
    <link rel="stylesheet" href="{{ asset('') }}modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('') }}modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('') }}modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="{{ asset('') }}modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('') }}css/style.css">
    <link rel="stylesheet" href="{{ asset('') }}css/components.css">
</head>
<body>
    <p>Yth. {{$datas['name']}}</p>
    <br>
    <p>
        Sehubungan dengan kegiatan penjualan di SBC(Studen Bussines Corner) UCIC, kami perlu memastikan kesediaan Anda untuk berjualan pada tanggal {{\Carbon\Carbon::parse($datas['tgl_penjualan'])->translatedFormat('l, d F Y')}}. Untuk itu, kami memohon konfirmasi dari Anda mengenai hal tersebut.
    </p>
    <p>
        Detail Kegiatan
    </p>
    <br>
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <td style="width: 20%">Nama Lengkap</td>
            <td>:</td>
            <td style="width: 80%">{{$datas['name']}}</td>
        </tr>
        <tr>
            <td style="width: 20%">Nomor Induk Mahasiswa</td>
            <td>:</td>
            <td style="width: 80%">{{$datas['nim']}}</td>
        </tr>
        <tr>
            <td style="width: 20%">Tanggal & Waktu</td>
            <td>:</td>
            <td style="width: 80%">{{\Carbon\Carbon::parse($datas['tgl_penjualan'])->translatedFormat('l, d F Y')}}</td>
        </tr>
    </table>
    <br>
    <p>
        Mohon berikan konfirmasi kehadiran Anda paling lambat pada jam 3 sore. Apabila Anda berhalangan, kami juga mohon informasi agar kami dapat mencari solusi atau pengganti.
    </p>
    <p>
        Terima kasih atas perhatian dan kerjasamanya.
    </p>

    <br>
    <br>

    <p>
        Admin SBC UCIC
    </p>

    <script src="{{ asset('') }}modules/jquery.min.js"></script>
    <script src="{{ asset('') }}modules/popper.js"></script>
    <script src="{{ asset('') }}modules/tooltip.js"></script>
    <script src="{{ asset('') }}modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('') }}modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="{{ asset('') }}modules/moment.min.js"></script>
    <script src="{{ asset('') }}js/stisla.js"></script>
    
    <!-- JS Libraies -->
    <script src="{{ asset('') }}modules/datatables/datatables.min.js"></script>
    <script src="{{ asset('') }}modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>