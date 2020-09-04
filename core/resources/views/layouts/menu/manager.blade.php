<ul class="nav-main">
    <li>
        <a class="{{ Request::is('beranda') ? 'active' : null }}" href="{{ route('beranda') }}">
            <i class="fa fa-home"></i>
            <span class="sidebar-mini-hide">Beranda</span>
        </a>
    </li>
    <li class="{{ Request::is('pembelian/*', 'pembelian', 'pelanggan', 'pelanggan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="fa fa-arrow-circle-down"></i>
            <span class="sidebar-mini-hide">Pembelian</span>
        </a>
        <ul>
            <li>
                <a class="{{ Request::is('pembelian/draft') ? 'active' : null }}" href="{{ route('pembelian.draft') }}">Konfirmasi Pembelian</a>
            </li>
            <li>
                <a class="{{ Request::is('pembelian/riwayat') ? 'active' : null }}" href="{{ route('pembelian.riwayat') }}">Riwayat Pembelian</a>
            </li>
        </ul>
    </li>
    <li class="{{ Request::is('penjualan/*', 'penjualan', 'sales', 'sales/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="fa fa-arrow-circle-up"></i>
            <span class="sidebar-mini-hide">Penjualan</span>
        </a>
        <ul>
            <li>
                <a class="{{ Request::is('penjualan/riwayat') ? 'active' : null }}" href="{{ route('penjualan.riwayat') }}">Riwayat Penjualan</a>
            </li>
            <li>
                <a class="{{ Request::is('sales', 'sales/*') ? 'active' : null }}" href="{{ route('sales') }}">Kelola Sales</a>
            </li>
        </ul>
    </li>
    <li>
        <a class="{{ Request::is('pengguna', 'pengguna/*') ? 'active' : null }}" href="{{ route('pengguna') }}">
            <i class="fa fa-users"></i>
            <span class="sidebar-mini-hide">Kelola Pengguna</span>
        </a>
    </li>
</ul>
