<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <x-seo::meta />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @seo([
        'title' => 'FluenceGrid',
        'description' => 'Influencers Management Hub',
        'image' => asset('images/login-image.png'),
        'site_name' => config('app.name'),
        'favicon' => asset('images/fav-image.png'),
    ])

    <title>Influence</title>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app-CPajs8vw.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-Dbbx3F5k.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app-CE5Kpj__.css') }}">
    {{-- <script src="{{ asset('build/assets/app-CB21gC7Z.js') }}"></script> --}}
    {{--<link rel="stylesheet" href="{{ asset('build/assets/drivers-DB0Q8XAf.css') }}">
    <script src="{{ asset('build/assets/drivers-80L94_2i.js') }}"></script>--}}


    @yield('styles')

    @livewireStyles
</head>

<body class="h-full">
    <marquee direction="right" scrollamount="60" class="z-50 fixed w-full hidden" id="hiddenLinearPreloader">
        <div class="bg-gradient-to-r from-[#0F1523] from-70%  to-[#B5FFAB] w-[700px] p-1 rounded-full"></div>
    </marquee>
    {{ $slot }}

</body>



<script>
    window.addEventListener('beforeunload', function(event) {
        var hiddenText = document.getElementById('hiddenText');
        hiddenText.classList.remove('hidden');
        hiddenLinearPreloader.classList.remove("hidden");
    });

    document.addEventListener("DOMContentLoaded", function() {
            var hiddenLinearPreloader = document.getElementById("hiddenLinearPreloader");

            hiddenLinearPreloader.classList.remove("hidden");

            setTimeout(function() {
                hiddenLinearPreloader.classList.add("hidden");
            }, 2000);
        });
</script>


@yield('scripts')
@livewireScripts

</html>
