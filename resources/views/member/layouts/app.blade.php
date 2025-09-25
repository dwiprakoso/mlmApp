<!DOCTYPE html>
<html lang="id">

<head>
    <base href="{{ url('/') }}/" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ $appLogo ?? 'https://via.placeholder.com/40' }}">
    <title>{{ $appName ?? 'My Application' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="mobile-container">
        <div class="hero-card">
            @include('member.components.header')

            @yield('content')
        </div>
        @include('member.components.bottombar')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
