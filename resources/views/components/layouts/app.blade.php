@props(['title' => null])

@extends('layouts.app')

@if($title)
@section('title', $title . ' - SkinTemple')
@endif

@section('content')
    {{ $slot }}
@endsection
