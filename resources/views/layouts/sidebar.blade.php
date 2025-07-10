<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo" style="padding-left: 0 !important">
      <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
        <img src="{{ asset('assets/img/resqin_logo.png') }}" height="40" width="55" alt="">
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

      @if (auth()->user()->can('read-order'))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Main</span>
        </li>

        <li class="menu-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
          <a href="{{ route('admin.orders.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
            <div>Pesanan</div>
          </a>
        </li>
      @endif

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Data Master</span>
      </li>

      @if (auth()->user()->can('read-driver'))
        <li class="menu-item {{ request()->is('admin/drivers*') ? 'active' : '' }}">
          <a href="{{ route('admin.drivers.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-user"></i>
            <div>Pengemudi</div>
          </a>
        </li>
      @endif

      @if (auth()->user()->can('read-ambulance-type'))
        <li class="menu-item {{ request()->is('admin/ambulance-types*') ? 'active' : '' }}">
          <a href="{{ route('admin.ambulance-types.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-car"></i>
            <div>Tipe Ambulance</div>
          </a>
        </li>
      @endif

      @if (auth()->user()->can('read-purpose'))
        <li class="menu-item {{ request()->is('admin/purposes*') ? 'active' : '' }}">
          <a href="{{ route('admin.purposes.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-target"></i>
            <div>Tujuan Pemesanan</div>
          </a>
        </li>
      @endif

      @if (auth()->user()->can('read-additional-service'))
        <li class="menu-item {{ request()->is('admin/additional-services*') ? 'active' : '' }}">
          <a href="{{ route('admin.additional-services.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-box"></i>
            <div>Layanan Tambahan</div>
          </a>
        </li>
      @endif

      @if (auth()->user()->can('read-destination'))
        <li class="menu-item {{ request()->is('admin/destinations*') ? 'active' : '' }}">
          <a href="{{ route('admin.destinations.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-map"></i>
            <div>Pustaka Tempat</div>
          </a>
        </li>
      @endif

      @if (auth()->user()->can('read-users'))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Manajemen Pengguna</span>
        </li>
        <li class="menu-item {{ request()->is('admin/users*') ? 'active' : '' }}">
          <a href="{{ route('admin.users.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-user"></i>
            <div>Pengguna</div>
          </a>
        </li>

        @if (auth()->user()->can('read-roles') || auth()->user()->can('read-permissions'))
          <li class="menu-item {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons ti ti-settings"></i>
                  <div>Roles & Permissions</div>
              </a>
              <ul class="menu-sub">
                  @if (auth()->user()->can('read-roles'))
                  <li class="menu-item {{ request()->is('admin/roles*') ? 'active' : '' }}">
                      <a href="{{ route('admin.roles.index') }}" class="menu-link">
                          <div>Roles</div>
                      </a>
                  </li>
                  @endif
                  @if (auth()->user()->can('read-permissions'))
                  <li class="menu-item {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                      <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                          <div>Permissions</div>
                      </a>
                  </li>
                  @endif
              </ul>
          </li>
        @endif
      @endif

      @if (auth()->user()->can('read-setting'))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">Pengaturan</span>
        </li>

        <li class="menu-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
          <a href="{{ route('admin.settings.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-settings"></i>
            <div>Pengaturan</div>
          </a>
        </li>
      @endif
    </ul>
</aside>