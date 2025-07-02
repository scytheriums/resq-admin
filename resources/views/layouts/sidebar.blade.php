<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
        <span class="app-brand-logo demo">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 1080 1080" xml:space="preserve">
            <desc>Created with Fabric.js 5.2.4</desc>
            <defs>
            </defs>
            <rect x="0" y="0" width="100%" height="100%" fill="transparent"></rect>
            <g transform="matrix(1 0 0 1 540 540)" id="05742ba6-fd56-4e66-990a-23ccfae29ae0">
              <rect style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1; visibility: hidden;" vector-effect="non-scaling-stroke" x="-540" y="-540" rx="0" ry="0" width="1080" height="1080" />
            </g>
            <g transform="matrix(1 0 0 1 540 540)" id="259ba07e-397f-409c-a028-d73470b85558">
            </g>
            <g transform="matrix(-0.33 0 0 -0.33 446.06 522.91)" id="b30b9f43-1e4b-4903-9776-f34bdc6cc470">
              <path style="stroke: rgb(0,0,0); stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(203,0,16); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke" transform=" translate(-1624.92, -1624.44)" d="M 1466 3213 C 1471 3192 1474 3147 1473 3113 C 1472 3003 1450 2972 993 2413 C 763 2132 560 1876 541 1845 C 522 1814 494 1752 478 1708 C 450 1628 424 1456 435 1422 C 442 1397 601 1380 915 1370 C 1209 1360 1258 1349 1337 1273 C 1431 1184 1457 1078 1466 730 C 1472 521 1469 493 1429 336 L 1416 287 L 1462 245 C 1563 154 1760 0 1775 0 C 1788 0 1791 6 1786 25 C 1761 126 1777 232 1831 308 C 1847 332 2041 572 2261 841 C 2481 1110 2678 1357 2699 1390 C 2839 1606 2856 1887 2744 2130 C 2671 2287 2646 2311 2048 2798 C 1745 3044 1489 3246 1478 3248 C 1461 3251 1460 3248 1466 3213 z M 1583 2204 C 1646 2183 1712 2122 1741 2058 L 1765 2005 L 1770 1455 C 1774 960 1777 901 1792 866 C 1802 845 1807 824 1805 819 C 1796 806 1729 790 1679 790 C 1651 790 1615 799 1583 816 C 1541 836 1526 851 1506 893 L 1480 944 L 1480 1437 L 1480 1930 L 1278 1932 L 1075 1935 L 1072 2005 C 1067 2111 1093 2167 1165 2204 C 1208 2227 1516 2227 1583 2204 z M 2178 2095 C 2174 1928 2182 1935 1994 1935 C 1853 1935 1848 1936 1829 1959 C 1819 1972 1810 1992 1810 2005 C 1810 2046 1750 2151 1709 2179 C 1688 2193 1671 2208 1670 2213 C 1670 2217 1785 2220 1926 2220 L 2182 2220 L 2178 2095 z" stroke-linecap="round" />
            </g>
          </svg>
        </span>
        <span class="app-brand-text demo menu-text fw-bold">{{ env('APP_NAME') }}</span>
      </a>
  
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
      </a>
    </div>
  
    <div class="menu-inner-shadow"></div>
  
    <ul class="menu-inner py-1">
      <li class="menu-item {{ request()->is('admin/dashboard*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-smart-home"></i>
          <div>Dasbor</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
              <div>Analitik</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Core</span>
      </li>

      <li class="menu-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
        <a href="{{ route('admin.orders.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
          <div>Pesanan</div>
        </a>
      </li>

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Data Master</span>
      </li>

      <li class="menu-item {{ request()->is('admin/drivers*') ? 'active' : '' }}">
        <a href="{{ route('admin.drivers.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-user"></i>
          <div>Pengemudi</div>
        </a>
      </li>

      <li class="menu-item {{ request()->is('admin/ambulance-types*') ? 'active' : '' }}">
        <a href="{{ route('admin.ambulance-types.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-car"></i>
          <div>Tipe Ambulance</div>
        </a>
      </li>

      <li class="menu-item {{ request()->is('admin/purposes*') ? 'active' : '' }}">
        <a href="{{ route('admin.purposes.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-target"></i>
          <div>Tujuan</div>
        </a>
      </li>

      <li class="menu-item {{ request()->is('admin/additional-services*') ? 'active' : '' }}">
        <a href="{{ route('admin.additional-services.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-box"></i>
          <div>Layanan Tambahan</div>
        </a>
      </li>

      <li class="menu-item {{ request()->is('admin/destinations*') ? 'active' : '' }}">
        <a href="{{ route('admin.destinations.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-map"></i>
          <div>Destinasi</div>
        </a>
      </li>

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Manajemen Pengguna</span>
      </li>
      <li class="menu-item {{ request()->is('admin/users*') ? 'active' : '' }}">
        <a href="{{ route('admin.users.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-user"></i>
          <div>Pengguna</div>
        </a>
      </li>

      <li class="menu-item">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons ti ti-settings"></i>
              <div>Roles & Permissions</div>
          </a>
          <ul class="menu-sub">
              <li class="menu-item">
                  <a href="{{ route('admin.roles.index') }}" class="menu-link">
                      <div>Roles</div>
                  </a>
              </li>
              <li class="menu-item">
                  <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                      <div>Permissions</div>
                  </a>
              </li>
          </ul>
      </li>

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Pengaturan</span>
      </li>

      <li class="menu-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
        <a href="{{ route('admin.settings.index') }}" class="menu-link">
          <i class="menu-icon tf-icons ti ti-settings"></i>
          <div>Pengaturan</div>
        </a>
      </li>
    </ul>
</aside>