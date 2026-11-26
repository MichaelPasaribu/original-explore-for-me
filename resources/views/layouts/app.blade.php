<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#060a1a">
    <title>@yield('title', 'Aurora Corner')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@300;400;500&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/aurora.css') }}">
</head>
<body>
    <canvas id="aurora" aria-hidden="true"></canvas>
    <div class="veil" aria-hidden="true"></div>

    <header class="topbar">
        <button id="menu-btn" class="icon-btn" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="drawer">
            <span></span><span></span><span></span>
        </button>
        <a href="{{ route('home') }}" class="brand">Aurora Corner</a>
    </header>

    <div id="scrim" class="scrim"></div>

    <aside id="drawer" class="drawer" aria-hidden="true" aria-label="Menu samping">
        <div class="drawer-profile">
            @include('partials.avatar')
            <h3>{{ config('profile.name') }}</h3>
            <p>{{ config('profile.role') }}</p>
            <a href="{{ route('profile') }}" class="btn small">Lihat profil</a>
        </div>

        <nav class="menu">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('profile') }}">Profil</a>
            <a href="{{ route('stories.index') }}">Cerpen &amp; Novel</a>
            <a href="{{ route('songs.index') }}">Lagu</a>
            <a href="{{ route('suggestions.index') }}">Saran &amp; Masukan</a>
        </nav>
    </aside>

    <main id="page" class="container" tabindex="-1">
        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="footer">© {{ date('Y') }} Aurora Corner</footer>

    {{-- Pemutar lagu: berada di luar #page agar tidak terhenti saat pindah halaman --}}
    <div id="player" class="player" hidden>
        <div id="ly-panel" class="ly-panel" hidden>
            <div id="ly-lines" class="ly-lines"></div>
        </div>

        <div class="pl-track">
            <div class="pl-cover"><img id="pl-cover" alt="" hidden></div>
            <div class="pl-info">
                <strong id="pl-title">-</strong>
                <span id="pl-artist">-</span>
            </div>
        </div>

        <div class="pl-controls">
            <button id="pl-prev" class="pl-btn" type="button" aria-label="Sebelumnya">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M6 6h2v12H6zm3.5 6 8.5 6V6z"/></svg>
            </button>
            <button id="pl-play" class="pl-btn main" type="button" aria-label="Putar / jeda">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            </button>
            <button id="pl-next" class="pl-btn" type="button" aria-label="Berikutnya">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M16 6h2v12h-2zM6 18l8.5-6L6 6z"/></svg>
            </button>
            <button id="pl-lyrics" class="pl-btn txt" type="button" aria-label="Tampilkan lirik" hidden>Lirik</button>
        </div>

        <div class="pl-seek">
            <span id="pl-cur">0:00</span>
            <input id="pl-seek" type="range" min="0" max="100" step="0.1" value="0" aria-label="Posisi lagu">
            <span id="pl-dur">0:00</span>
        </div>

        <audio id="audio" preload="metadata"></audio>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>