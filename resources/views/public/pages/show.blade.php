@extends('layouts.app')

@php
    $page = $page ?? null;
@endphp

@if($page)
    @section('title', $page->title . ' - SkinTemple')
    
    @push('meta')
        <meta name="description" content="{{ $page->meta_description ?: Str::limit(strip_tags($page->content), 160) }}">
        <meta property="og:title" content="{{ $page->meta_title ?: $page->title }}">
        <meta property="og:description" content="{{ $page->meta_description ?: Str::limit(strip_tags($page->content), 160) }}">
    @endpush
@endif

@section('content')
<div class="min-h-screen bg-surface">
    @if($page)
        <!-- Breadcrumb -->
        <x-public.container class="pt-8">
            <x-public.breadcrumb :items="[['label' => $page->title]]" />
        </x-public.container>

        <!-- Renderizza blocchi CMS dinamici -->
        @php 
            $blocks = $page->blocks()->active()->orderBy('sort_order')->get();
        @endphp

        @if($blocks->count() > 0)
            @foreach($blocks as $block)
                <x-public.block-renderer :block="$block" />
            @endforeach
        @else
            <!-- Fallback se nessun blocco -->
            <x-public.section>
                <x-public.container max="3xl">
                    <article class="prose prose-lg prose-neutral max-w-none">
                        <h1>{{ $page->title }}</h1>
                        
                        @if($page->excerpt)
                            <p class="lead">{{ $page->excerpt }}</p>
                        @endif
                        
                        {!! $page->content !!}
                        
                        @if($page->updated_at)
                            <hr>
                            <p class="text-sm text-neutral-500">
                                Ultimo aggiornamento: {{ $page->updated_at->format('d/m/Y') }}
                            </p>
                        @endif
                    </article>
                </x-public.container>
            </x-public.section>
        @endif
        
    @else
        <!-- Page not found -->
        <x-public.section>
            <x-public.container>
                <div class="text-center py-16">
                    <h1 class="text-2xl font-semibold text-neutral-900 mb-4">Pagina non trovata</h1>
                    <p class="text-neutral-600 mb-8">La pagina che stai cercando non esiste o è stata rimossa.</p>
                    <a href="/" class="btn-primary">Torna alla home</a>
                </div>
            </x-public.container>
        </x-public.section>
    @endif
</div>
@endsection