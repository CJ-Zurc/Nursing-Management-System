<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>

    {{-- Bootstrap via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <div class="container mt-12" bg-primary bg-gradient >
        @yield('content')
    </div>

</body>
</html>
