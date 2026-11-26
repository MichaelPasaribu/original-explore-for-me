@extends('layouts.app')
@section('title', 'Home')

@section('content')
<section class="hero">
    <div class="hero-text card">
    <p class="eyebrow">Selamat datang</p>
    <h1>Tempat kata &amp; nada berpendar pelan seperti aurora</h1>
    <p class="lead">Kumpulan cerpen, novel, dan lagu yang kutulis dengan sepenuh hati. Putar sebuah lagu, lalu lihat langit di belakang ikut bergerak mengikuti iramanya.</p>
    <a href="{{ route('profile') }}" class="btn">Kenali penulisnya</a>
</div>

    <div class="collage">
        <div class="ph"><img src="{{ asset('images/hero-1.jpg') }}" alt="" onerror="this.remove()"></div>
        <div class="ph"><img src="{{ asset('images/hero-2.jpg') }}" alt="" onerror="this.remove()"></div>
        <div class="ph"><img src="{{ asset('images/hero-3.jpg') }}" alt="" onerror="this.remove()"></div>
    </div>
</section>

<section class="portals">
    <a href="{{ route('stories.index') }}" class="portal" style="--img:url('{{ asset('images/books.jpg') }}')">
        <span class="badge">{{ $storyCount }} karya</span>
        <h2>Cerpen &amp; Novel</h2>
        <p>Baca cerita yang sudah dipublikasikan.</p>
    </a>
    <a href="{{ route('songs.index') }}" class="portal" style="--img:url('{{ asset('images/music.jpg') }}')">
        <span class="badge">{{ $songCount }} lagu</span>
        <h2>Lagu</h2>
        <p>Dengarkan lagu-lagu yang sudah dirilis.</p>
    </a>
</section>

<section class="center">
    <a href="{{ route('suggestions.index') }}" class="btn ghost">Kirim saran &amp; masukan</a>
</section>
@endsection