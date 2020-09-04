<ul class="nav-main">
    <li>
        <a class="{{ Request::is('beranda') ? 'active' : null }}" href="{{ route('beranda') }}">
            <i class="fa fa-home"></i>
            <span class="sidebar-mini-hide">Beranda</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('pengiriman') ? 'active' : null }}" href="{{ route('pengiriman') }}">
            <i class="fa fa-list"></i>
            <span class="sidebar-mini-hide">Daftar Pengiriman</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('pengiriman/riwayat') ? 'active' : null }}" href="{{ route('pengiriman.riwayat') }}">
            <i class="fa fa-truck"></i>
            <span class="sidebar-mini-hide">Riwayat Pengiriman</span>
        </a>
    </li>
    <li>
        <a class="{{ Request::is('kendaraan', 'kendaraan/*') ? 'active' : null }}" href="{{ route('kendaraan') }}">
            <i class="fa fa-car"></i>
            <span class="sidebar-mini-hide">Kelola Kendaraan</span>
        </a>
    </li>
</ul>
