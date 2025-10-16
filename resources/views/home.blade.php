@extends('layouts.app')

@section('title', 'Buku Tamu Digital')

@section('content')
    @include('components.hero')
    @include('partials.fitur')
    @include('partials.alur-penggunaan')
    @include('partials.about')
@endsection
