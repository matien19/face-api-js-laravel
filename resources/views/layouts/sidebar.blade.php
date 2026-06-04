<nav class="navbar navbar-vertical navbar-expand-lg" style="display:none;">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <div class="nav-item-wrapper">
                    <a class="nav-link {{ Request::is('beranda') ? 'active' : '' }} label-1"
                        href="{{ route('beranda') }}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon">
                                <span data-feather="pie-chart"></span>
                            </span>
                            <span class="nav-link-text-wrapper">
                                <span class="nav-link-text">Beranda</span>
                            </span>
                        </div>
                    </a>
                </div>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Apps</p>
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    @php
                    $rute = [
                        'user*' => 'User',
                        'lokasi*' => 'Lokasi',
                    ];
                    @endphp
                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-CRM"
                            role="button" data-bs-toggle="collapse"
                            aria-expanded="{{ Request::is(array_keys($rute)) ? 'true' : 'false' }}" aria-controls="nv-CRM">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon"><span data-feather="database"></span>
                                </span>
                                <span class="nav-link-text">Master Data</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent {{ Request::is(array_keys($rute)) ? 'show' : '' }}"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-CRM">
                                <li class="collapsed-nav-item-title d-none">Master Data</li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('user*') ? 'active' : '' }}"
                                        href="{{ route('md.user') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">User</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('lokasi*') ? 'active' : '' }}"
                                        href="{{ route('md.lokasi') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Lokasi</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <div class="nav-item-wrapper">
                    <a class="nav-link {{ Request::is('laporan*') ? 'active' : '' }} label-1"
                        href="{{ route('laporan.index') }}" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon">
                                <span data-feather="file-text"></span>
                            </span>
                            <span class="nav-link-text-wrapper">
                                <span class="nav-link-text">Laporan</span>
                            </span>
                        </div>
                    </a>
                </div>
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button
            class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
            <span class="uil uil-left-arrow-to-left fs-8"></span>
            <span class="uil uil-arrow-from-right fs-8"></span>

            <span class="navbar-vertical-footer-text ms-2">Collapsed View</span>
        </button>
    </div>
</nav>