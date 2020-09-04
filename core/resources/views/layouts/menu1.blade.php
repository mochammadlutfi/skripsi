<ul class="nav-main">
    <li>
        <a class="{{ Request::is('mitra/beranda') ? 'active' : null }}" href="{{ route('mitra.beranda') }}"><i class="si si-cup"></i><span class="sidebar-mini-hide">Beranda</span></a>
    </li>
    <li class="{{ Request::is('mitra/produk/*','mitra/produk') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-social-dropbox"></i><span class="sidebar-mini-hide">Produk</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('mitra/produk', 'mitra/produk/edit/*', 'mitra/produk/tambah') ? 'active' : null }}" href="{{ route('mitra.produk') }}">Kelola Produk</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/produk/kategori') ? 'active' : null }}" href="{{ route('mitra.kategori') }}">Kelola Kategori</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/produk/merk') ? 'active' : null }}" href="{{ route('mitra.merk') }}">Kelola Merk</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/produk/pajak') ? 'active' : null }}" href="{{ route('mitra.merk') }}">Pajak</a>
            </li>
        </ul>
    </li>
    <li class="{{ Request::is('mitra/pembelian', 'mitra/pembelian/*', 'mitra/retur-pembelian','mitra/retur-pembelian/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-arrow-circle-down"></i><span class="sidebar-mini-hide">Pembelian</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('mitra/pembelian') ? 'active' : null }}" href="{{ route('mitra.pembelian') }}">Mulai Pembelian</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/pembelian/riwayat') ? 'active' : null }}" href="{{ route('mitra.pembelian.riwayat') }}">Riwayat Pembelian</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/retur-pembelian', 'mitra/retur-pembelian/*') ? 'active' : null }}" href="{{ route('mitra.returbeli') }}">Retur Pembelian</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/pembelian/supplier') ? 'active' : null }}" href="{{ route('mitra.supplier') }}">Supplier</a>
            </li>
        </ul>
    </li>
    <li class="{{ Request::is('mitra/penjualan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            <i class="fa fa-arrow-circle-up"></i>
            <span class="sidebar-mini-hide">Penjualan</span>
        </a>
        <ul>
            <li>
                <a class="{{ Request::is('mitra/pos') ? 'active' : null }}" href="{{ route('mitra.pos') }}">Mulai Berjualan</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/pos/riwayat') ? 'active' : null }}" href="{{ route('mitra.pos.riwayat') }}">Riwayat Penjualan</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/penjualan/pelanggan') ? 'active' : null }}" href="{{ route('mitra.pelanggan') }}">Pelanggan</a>
            </li>
        </ul>
    </li>
    <li class="{{ Request::is('mitra/inventaris/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-boxes"></i><span class="sidebar-mini-hide">Inventaris</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('mitra/inventaris/pindah-stok') ? 'active' : null }}" href="#">Pemindahan Stok</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/inventaris/penyesuaian-stok') ? 'active' : null }}" href="#">Penyesuaian Stok</a>
            </li>
        </ul>
    </li>
    <li>
        <a class="{{ Request::is('mitra/outlet', 'mitra/outlet/*') ? 'active' : null }}" href="{{ route('mitra.outlet') }}"><i class="fa fa-store-alt"></i><span class="sidebar-mini-hide">Outlet</span></a>
    </li>
    <li>
        <a class="{{ Request::is('mitra/pegawai', 'mitra/pegawai/*') ? 'active' : null }}" href="{{ route('mitra.pegawai') }}"><i class="si si-user"></i><span class="sidebar-mini-hide">Pegawai</span></a>
    </li>
    <li class="{{ Request::is('mitra/laporan/*') ? 'open' : null }}">
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-printer"></i><span class="sidebar-mini-hide">Laporan</span></a>
        <ul>
            <li>
                <a class="{{ Request::is('mitra/laporan/penjualan-ringkasan') ? 'active' : null }}" href="#">Ringkasan Penjualan</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/laporan/penjualan-produk') ? 'active' : null }}" href="#">Penjualan Per Produk</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/laporan/penjualan-kategori') ? 'active' : null }}" href="#">Penjualan Per Kategori</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/laporan/penjualan-merk') ? 'active' : null }}" href="#">Penjualan Per Merk</a>
            </li>
            <li>
                <a class="{{ Request::is('mitra/laporan/pegawai') ? 'active' : null }}" href="#">Pegawai</a>
            </li>
        </ul>
    </li>
</ul>
