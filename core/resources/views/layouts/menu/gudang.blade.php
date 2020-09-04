<ul class="nav-main">
    <li>
        <a class="{{ Request::is('beranda') ? 'active' : null }}" href="{{ route('beranda') }}">
            <i class="fa fa-home"></i>
            <span class="sidebar-mini-hide">Beranda</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('pembelian/draft') ? 'active' : null }}" href="{{ route('pembelian.draft') }}">
            <i class="fa fa-check-circle"></i>
            <span class="sidebar-mini-hide">Konfirmasi Pembelian</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('pembelian/riwayat') ? 'active' : null }}" href="{{ route('pembelian.riwayat') }}">
            <i class="fa fa-arrow-circle-down"></i>
            <span class="sidebar-mini-hide">Riwayat Pembelian</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('retur-pembelian') ? 'active' : null }}" href="{{ route('returbeli') }}">
            <i class="fa fa-undo"></i>
            <span class="sidebar-mini-hide">Retur Pembelian</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('penjualan/riwayat') ? 'active' : null }}" href="{{ route('penjualan.riwayat') }}">
            <i class="fa fa-arrow-circle-up"></i>
            <span class="sidebar-mini-hide">Riwayat Penjualan</span>
        </a>
    </li>
</ul>
