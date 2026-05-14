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
<body class="h-full bg-brand-surface-soft">
    <div class="min-h-screen flex flex-col">
        <header class="py-8">
            <x-public.container>
                <div class="text-center">
                    <a href="{{ route('home') }}">
                        <x-public.brand-logo class="h-12 mx-auto" />
                    </a>
                </div>
            </x-public.container>
        </header>
        
        <main class="flex-1 flex items-center justify-center">
            {{ $slot }}
        </main>
        
        <footer class="py-6">
            <x-public.container>
                <div class="text-center text-sm text-brand-muted">
                    <p>&copy; {{ date('Y') }} SkinTemple &middot; P.IVA 11863510019</p>
                </div>
            </x-public.container>
        </footer>
    </div>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>