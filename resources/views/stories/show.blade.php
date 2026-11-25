@extends('layouts.app')
@section('title', $story->title)

@section('content')
<article class="card reader">
    <a href="{{ route('stories.index') }}" class="back">← Kembali</a>
    <span class="tag">{{ ucfirst($story->type) }}{{ $story->genre ? ' · '.$story->genre : '' }}</span>
    <h1>{{ $story->title }}</h1>
    <small class="muted">{{ $story->created_at->translatedFormat('d F Y') }}</small>

    @if($story->cover_path)
        <img src="{{ asset($story->cover_path) }}" class="reader-cover" alt="">
    @endif

    <div class="story-text">{{ $story->content }}</div>
</article>
@endsection