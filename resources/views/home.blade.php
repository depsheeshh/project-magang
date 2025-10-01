@extends('layouts.app')

@section('title', 'Buku Tamu Digital')

@section('content')
    <x-hero />
    @include('partials.fitur')
    @include('partials.alur-penggunaan')

    @include('partials.about')
@endsection
