@extends('layouts.app')
@section('title', 'Profil')

@section('content')
<section class="card profile">
    @include('partials.avatar', ['class' => 'lg'])
    <div class="profile-body">
        <h1>{{ config('profile.name') }}</h1>
        <p class="muted">{{ config('profile.role') }}</p>
        <p class="bio">{{ config('profile.bio') }}</p>

        <ul class="info">
            @foreach(config('profile.details') as $label => $value)
                <li><strong>{{ $label }}</strong> <span>{{ $value }}</span></li>
            @endforeach
        </ul>
    </div>
</section>
@endsection