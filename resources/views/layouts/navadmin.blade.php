<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

        <title>Website UMKM Puti Mungka</title>

        <!-- Styles all in -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
      
        <!-- Scripts all in -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        
        <!-- Styles detail produk -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <!-- Scripts detail produk -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- chart/grafik -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                <img src="{{asset("img/logo.png")}}" alt="Logo" width="60" height="60" class="d-inline-block">
                Puti Mungka
                </a>
            </div>
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('dashboardadmin')" :active="request()->routeIs('dashboardadmin')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('katalogadmin')" :active="request()->routeIs('katalogadmin')">
                                {{ __('Katalog') }}
                            </x-nav-link>
                            <x-nav-link :href="route('pengguna')" :active="request()->routeIs('pengguna')">
                                {{ __('Pelanggan') }}
                            </x-nav-link>
                            <x-nav-link :href="route('keranjangadmin', ['id' => Auth::user()->id])" :active="request()->routeIs('keranjangadmin')">
                                {{ __('Keranjang') }}
                            </x-nav-link>
                            <x-nav-link :href="route('pesananadmin')" :active="request()->routeIs('pesananadmin')">
                                {{ __('Pesanan') }}
                            </x-nav-link>
                            <x-nav-link :href="route('laporanpenjualan')" :active="request()->routeIs('laporanpenjualan')">
                                {{ __('Laporan Penjualan') }}
                            </x-nav-link>
                        </div>
                    </div>
        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ml-6 p-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
        </div>
        </nav>    
        {{-- Content Abu-Abu --}}
        <div class="content w-full bg-gray-100 h-full p-1">
            {{-- Isi content --}}
            @yield('content')
        </div> 
    </body>
</html>
