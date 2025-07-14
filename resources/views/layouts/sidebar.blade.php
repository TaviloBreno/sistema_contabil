<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
                    src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}"
                    alt="AdminLTE Logo"
                    class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                    class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="menu"
                    data-accordion="false"
            >
                <li class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-house"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Usuários</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('configuracoes.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>Configurações</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                    <a href="{{ route('empresas.index') }}" class="nav-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-house-up-fill"></i>
                        <p>Empresas</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('obrigacoes.*') ? 'active' : '' }}">
                    <a href="{{ route('obrigacoes.index') }}" class="nav-link {{ request()->routeIs('obrigacoes.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>Gestão de Obrigações</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
                    <a href="{{ route('documentos.index') }}" class="nav-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark"></i>
                        <p>Documentos</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
                    <a href="{{ route('relatorios.index') }}" class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart"></i>
                        <p>Relatórios e Indicadores</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('notas-fiscais.*') ? 'active' : '' }}">
                    <a href="{{ route('notas-fiscais.index') }}" class="nav-link {{ request()->routeIs('notas-fiscais.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>Notas Fiscais</p>
                    </a>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
