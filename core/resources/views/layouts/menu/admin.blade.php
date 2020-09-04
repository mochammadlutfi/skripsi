<ul class="nav-main">
    <li>
        <a class="{{ Request::is('beranda') ? 'active' : null }}" href="{{ route('beranda') }}">
            <i class="fa fa-home"></i>
            <span class="sidebar-mini-hide">Beranda</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('produk.tambah') ? 'active' : null }}" href="{{ route('produk.tambah') }}">
            <i class="si si-plus"></i>
            <span class="sidebar-mini-hide">Tambah Produk</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('produk') ? 'active' : null }}" href="{{ route('produk') }}">
            <i class="fa fa-boxes"></i>
            <span class="sidebar-mini-hide">Kelola Produk</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('kategori') ? 'active' : null }}" href="{{ route('kategori') }}">
            <i class="fa fa-th-large"></i>
            <span class="sidebar-mini-hide">Kelola Kategori</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('merk') ? 'active' : null }}" href="{{ route('merk') }}">
            <i class="fa fa-tags"></i>
            <span class="sidebar-mini-hide">Kelola Merk</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('satuan') ? 'active' : null }}" href="{{ route('satuan') }}">
            <i class="si si-cup"></i>
            <span class="sidebar-mini-hide">Kelola Satuan</span>
        </a>
    </li>
    <li class="{{ Request::is('penjualan/*', 'penjualan', 'pelanggan', 'pelanggan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="fa fa-arrow-circle-up"></i>
            <span class="sidebar-mini-hide">Penjualan</span>
        </a>
        <ul>
            <li>
                <a class="{{ Request::is('penjualan') ? 'active' : null }}" href="{{ route('penjualan') }}">Tambah Penjualan</a>
            </li>
            <li>
                <a class="{{ Request::is('penjualan/riwayat') ? 'active' : null }}" href="{{ route('penjualan.riwayat') }}">Riwayat Penjualan</a>
            </li>
            <li>
                <a class="{{ Request::is('pelanggan', 'pelanggan/*') ? 'active' : null }}" href="{{ route('pelanggan') }}">Pelanggan</a>
            </li>
        </ul>
    </li>
</ul>
