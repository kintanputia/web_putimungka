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
                <a class="navbar-brand">
                <img src="{{asset("img/logo.png")}}" alt="Logo" width="60" height="60" class="d-inline-block">
                Puti Mungka
                </a>
            </div>
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex mr-5">
                            <x-nav-link :href="route('login')">
                                {{ __('Login') }}
                            </x-nav-link>
                            <x-nav-link :href="route('katalogproduktl')">
                                {{ __('Katalog') }}
                            </x-nav-link>
                        </div>
                    </div>
        </nav>    
        {{-- Content Abu-Abu --}}
        <div class="content w-full bg-gray-100 h-full p-1">
            {{-- Isi content --}}
            @yield('content')
        </div> 
    </body>
</html>
