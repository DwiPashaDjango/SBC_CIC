<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="#">
            <img src="{{asset('logo.jpg')}}" class="mt-3" width="100" alt="">
        </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="#">
            SBC
        </a>
    </div>
    <hr class="divide">
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="{{request()->is('dashboard') ? 'active' : ''}}"><a class="nav-link" href="{{route('dashboard')}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
        @role('Admin')
            <li class="menu-header">Master Data</li>
            <li class="{{request()->is('admin/jadwals*') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.jadwal.index')}}"><i class="fas fa-calendar"></i> <span>Jadwal Penjualan</span></a></li>
            <li class="{{request()->is('admin/stands*') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.stand.index')}}"><i class="fas fa-list"></i> <span>Data Stand</span></a></li>
            <li class="{{request()->is('admin/reports*') ? 'active' : ''}}"><a class="nav-link" href="{{route('admin.laporan.index')}}"><i class="fas fa-file"></i> <span>Laporan Penjualan</span></a></li>
        @endrole
        @role('Mahasiswa')
            <li class="menu-header">Menu</li>
            <li class="{{request()->is('mahasiswa/jadwals*') ? 'active' : ''}}"><a class="nav-link" href="{{route('mhs.jadwals.index')}}"><i class="fas fa-calendar"></i> <span>Jadwal Penjualan</span></a></li>
            <li class="{{request()->is('mahasiswa/products*') ? 'active' : ''}}"><a class="nav-link" href="{{route('mhs.product.index')}}"><i class="fas fa-shopping-cart"></i> <span>Data Product</span></a></li>
            <li class="{{request()->is('mahasiswa/laporans*') ? 'active' : ''}}"><a class="nav-link" href="{{route('mhs.laporan.index')}}"><i class="fas fa-file"></i> <span>Laporan Penjualan</span></a></li>
        @endrole
    </ul>
</aside>