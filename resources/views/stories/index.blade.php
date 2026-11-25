@extends('layouts.app')
@section('title', 'Cerpen & Novel')

@section('content')
<h1 class="page-title">📖 Cerpen &amp; Novel</h1>

<div class="filters">
    <a href="{{ route('stories.index') }}" class="chip {{ request('type') ? '' : 'active' }}">Semua</a>
    <a href="{{ route('stories.index', ['type' => 'cerpen']) }}" class="chip {{ request('type') === 'cerpen' ? 'active' : '' }}">Cerpen</a>
    <a href="{{ route('stories.index', ['type' => 'novel']) }}" class="chip {{ request('type') === 'novel' ? 'active' : '' }}">Novel</a>
</div>

<div class="cards">
    @forelse($stories as $story)
        <a href="{{ route('stories.show', $story) }}" class="card story-card">
            @if($story->cover_path)
                <img src="{{ asset($story->cover_path) }}" alt="{{ $story->title }}" class="story-cover">
            @else
                <div class="story-cover fallback">{{ mb_strtoupper(mb_substr($story->title, 0, 1)) }}</div>
            @endif
            <div class="card-body">
                <span class="tag">{{ ucfirst($story->type) }}{{ $story->genre ? ' · '.$story->genre : '' }}</span>
                <h3>{{ $story->title }}</h3>
                <p class="muted">{{ \Illuminate\Support\Str::limit($story->synopsis ?? $story->content, 110) }}</p>
                <small class="muted">{{ $story->created_at->translatedFormat('d F Y') }}</small>
            </div>
        </a>
    @empty
        <p class="muted">Belum ada karya yang dipublikasikan.</p>
    @endforelse
</div>
@endsection