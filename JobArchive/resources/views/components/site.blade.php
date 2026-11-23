@props(['title' => 'Jobarkiv'])

@php
    $menu = [
        'Find job',
        'Få karriereråd',
        'Udforsk arbejdspladser',
        'Find graduateforløb',
        'Senkarriere',
    ];
@endphp

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <header class="bar">
        <div class="links">
            <a href="{{ route('login') }}">Log ind</a>
            <a href="{{ route('register') }}">Opret profil</a>
            <a href="#">In English</a>
        </div>
        <a href="{{ route('home') }}"><strong>Jobarkiv</strong></a>
    </header>

    <nav class="nav">
        <div class="links">
            @foreach ($menu as $item)
                <a href="#">{{ $item }}</a>
            @endforeach
        </div>
        <a href="#" class="cta">Er du arbejdsgiver?</a>
    </nav>

    {{ $slot }}
</body>
</html>