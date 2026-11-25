@extends('layouts.app')
@section('title', 'Saran & Masukan')

@section('content')
<h1 class="page-title">💬 Saran &amp; Masukan</h1>

<div class="grid-2">
    <form method="POST" action="{{ route('suggestions.store') }}" class="card form" data-pjax>
        @csrf
        <label>Nama <input type="text" name="name" value="{{ old('name') }}" required></label>
        <label>Pesan <textarea name="message" rows="6" required>{{ old('message') }}</textarea></label>

        @if($errors->any())
            <div class="error">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif

        <button class="btn" type="submit">Kirim</button>
    </form>

    <div class="comments">
        @forelse($suggestions as $s)
            <div class="card comment">
                <strong>{{ $s->name }}</strong>
                <small class="muted">{{ $s->created_at->diffForHumans() }}</small>
                <p>{{ $s->message }}</p>
            </div>
        @empty
            <p class="muted">Belum ada masukan. Jadilah yang pertama!</p>
        @endforelse
    </div>
</div>
@endsection