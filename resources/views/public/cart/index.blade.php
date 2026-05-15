@extends('layouts.app')

@section('title', 'Carrello - SkinTemple')

@section('content')
<x-public.container class="py-10">
    <div class="mb-8">
        <x-public.breadcrumb :items="[['label' => 'Carrello']]" />
    </div>

    <livewire:public.cart.cart-page />
</x-public.container>
@endsection
