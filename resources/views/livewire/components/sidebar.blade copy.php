<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    {{-- Menu tanpa children menu --}}
                    <a href="#" class="waves-effect ">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>


                </li>
                <li>
                    {{-- Parent menu dengan dropdown --}}
                    <a class="has-arrow waves-effect">
                        <i class="ri-currency-line"></i>
                        <span>Transaksi</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li>
                            <a href="#"><i class="ri-inbox-line"></i>Stock</a>
                        </li>
                        <li>
                            <a href="#"><i class="ri-inbox-archive-line"></i>Stock Masuk</a>
                        </li>
                        <li>
                            <a href="#"><i class="ri-inbox-unarchive-line"></i>Stock Keluar</a>
                        </li>
                        <li>
                            <a href="#"><i class="ri-survey-line"></i>Form Tidak SNP</a>
                        </li>
                        <li>
                            <a href="#"><i class="ri-survey-line"></i>Form CSO</a>
                        </li>
                        <li>
                            <a href="#"><i class="ri-survey-line"></i>Form Scrabing</a>
                        </li>

                    </ul>
                </li>
                <li>
                    {{-- Parent menu dengan dropdown --}}
                    <a class="has-arrow waves-effect">
                        <i class="ri-database-2-line"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li>
                            <a href="#"><i class="ri-archive-line"></i>Master Produk</a>
                        </li>
                        <li>
                            <a href="#"><i class="ri-group-line"></i>Master Customer</a>
                        </li>
                        <li>
                            <a href="#"><i class="ri-fridge-line"></i>Master Rak</a>
                        </li>


                    </ul>
                </li>
                <li>
                    {{-- Parent menu dengan dropdown --}}
                    <a class="has-arrow waves-effect">
                        <i class="ri-user-line"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li>
                            <a href="#" class="ri-user-line">Add
                                User</a>
                        </li>

                    </ul>
                </li>

                {{-- @foreach ($menus as $menu)
                    @if ($menu->children->count())
                        <li> --}}
                {{-- Parent menu dengan dropdown --}}
                {{-- <a class="has-arrow waves-effect">
                                <i class="{{ $menu->icon }}"></i>
                                <span>{{ $menu->name }}</span> --}}
                {{-- </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @foreach ($menu->children as $child)
                                    <li>
                                        <a href="{{ route($child->route_name) }}"
                                            class="{{ request()->routeIs($child->route_name) ? 'active' : '' }}">{{ $child->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li> --}}
                {{-- Menu tanpa children menu --}}
                {{-- <a href="{{ route($menu->route_name) }}"
                                class="waves-effect {{ request()->routeIs($menu->route_name) ? 'active' : '' }}">
                                <i class="{{ $menu->icon }}"></i>
                                <span>{{ $menu->name }}</span>
                            </a> --}}

                {{-- <a href='{{ route($menu->route_name) }}' class="waves-effect">
                                <i class="{{ $menu->icon }}"></i>
                                <span>{{ $menu->name }}</span>
                            </a>

                        </li>
                    @endif
                @endforeach --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
