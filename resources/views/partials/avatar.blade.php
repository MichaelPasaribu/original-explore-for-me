<div class="avatar {{ $class ?? '' }}">
    <span>{{ mb_strtoupper(mb_substr(config('profile.name'), 0, 1)) }}</span>
    <img src="{{ asset(config('profile.photo')) }}" alt="Foto {{ config('profile.name') }}" onerror="this.remove()">
</div>