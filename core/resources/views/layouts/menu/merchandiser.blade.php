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
                <a class="{{ Request::is('pembelian') ? 'active' : null }}" href="{{ route('pembelian') }}">Tambah Pembelian</a>
            </li>
            <li>
                <a class="{{ Request::is('pembelian/draft') ? 'active' : null }}" href="{{ route('pembelian.draft') }}">Draft Pembelian</a>
            </li>
            <li>
                <a class="{{ Request::is('pembelian/riwayat') ? 'active' : null }}" href="{{ route('pembelian.riwayat') }}">Riwayat Pembelian</a>
            </li>
        </ul>
    </li>
    <li>
        <a class="{{ Request::is('penjualan/riwayat') ? 'active' : null }}" href="{{ route('penjualan.riwayat') }}">
            <i class="fa fa-arrow-circle-up"></i>
            <span class="sidebar-mini-hide">Riwayat Penjualan</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('supplier', 'supplier/*') ? 'active' : null }}" href="{{ route('supplier') }}">
            <i class="fa fa-industry"></i>
            <span class="sidebar-mini-hide">Kelola Supplier</span>
        </a>
    </li>
</ul>
