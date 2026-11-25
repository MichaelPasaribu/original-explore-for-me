@extends('layouts.app')
@section('title', 'Lagu')

@section('content')
<h1 class="page-title">🎵 Lagu</h1>

<div class="song-list">
    @forelse($songs as $song)
        <div class="card song"
             data-src="/{{ ltrim($song->audio_path, '/') }}"
             data-title="{{ $song->title }}"
             data-artist="{{ $song->artist ?? 'Original' }}"
             data-cover="{{ $song->cover_path ? '/'.ltrim($song->cover_path, '/') : '' }}">

            <div class="song-cover">
                @if($song->cover_path)
                    <img src="{{ asset($song->cover_path) }}" alt="">
                @else
                    ♪
                @endif
            </div>

            <div class="song-body">
                <h3>{{ $song->title }}</h3>
                <p class="muted">{{ $song->artist ?? 'Original' }}</p>
                @if($song->lyrics)
                    <details>
                        <summary>Lihat lirik</summary>
                        <div class="lyrics">{{ $song->lyrics }}</div>
                    </details>
                @endif
            </div>

            <button type="button" class="play-btn" aria-label="Putar {{ $song->title }}">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            </button>
        </div>
    @empty
        <p class="muted">Belum ada lagu yang ditambahkan.</p>
    @endforelse
</div>
@endsection