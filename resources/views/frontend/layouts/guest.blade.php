<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MindHaven')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F5FAFC] text-slate-900 antialiased">

    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const invalidInputs = document.querySelectorAll('input[required]');

            invalidInputs.forEach(function (input) {
                input.addEventListener('invalid', function () {
                    if (input.name === 'email') {
                        input.setCustomValidity('Email wajib diisi.');
                    }

                    if (input.name === 'password') {
                        input.setCustomValidity('Password wajib diisi.');
                    }
                });

                input.addEventListener('input', function () {
                    input.setCustomValidity('');
                });
            });
        });
    </script>

</body>
</html>