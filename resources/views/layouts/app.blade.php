<!DOCTYPE html>
<html lang="it" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SkinTemple') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full">
    <x-public.announcement-bar />
    
    <x-public.header />
    
    <main class="min-h-screen">
        {{ $slot }}
    </main>
    
    <x-public.footer />
    
    <x-public.mobile-bottom-bar />
    
    @livewireScripts
    @stack('scripts')
</body>
</html>