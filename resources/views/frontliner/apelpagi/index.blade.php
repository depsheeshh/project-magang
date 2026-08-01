@php
    $today = \Carbon\Carbon::today();
    $thisMonday = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $nextMonday = $thisMonday->copy()->addWeek();
@endphp

@extends('layouts.admin')

@section('title', 'Frontliner Apel Pagi')

@section('content')
    <div class="card card-modern p-4">
        <h4 class="mb-3"><i class="fa-solid fa-users me-2"></i> Daftar Pegawai – QR Apel Pagi</h4>
        {{-- ✅ tampilkan hanya kalau hari ini Senin --}}
        <p class="text-muted">
            Apel Pagi untuk Senin ini: {{ $thisMonday->translatedFormat('l, d F Y') }}<br>
            Senin berikutnya: {{ $nextMonday->translatedFormat('l, d F Y') }}
        </p>

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('frontliner.apelpagi.index') }}" class="mb-3">
            <div class="input-group mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari NIP atau Nama Pegawai...">
            </div>
        </form>

        {{-- Table --}}
        <div id="pegawaiTable">
            @include('frontliner.apelpagi.table', ['pegawai' => $pegawai])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchInput');
            let timer = null;

            searchInput.addEventListener('keyup', function() {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    const query = searchInput.value;

                    fetch("{{ route('frontliner.apelpagi.index') }}?search=" + encodeURIComponent(
                            query), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('pegawaiTable').innerHTML = html;
                        });
                }, 300); // debounce 300ms
            });
        });
    </script>
@endpush
