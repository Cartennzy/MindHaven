<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MindHaven')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- GOOGLE FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    {{-- FONT AWESOME - FIX ICON --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          referrerpolicy="no-referrer" />

    <style>
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
        }

        [x-cloak]{
            display:none !important;
        }

        ::-webkit-scrollbar{
            width:8px;
            height:8px;
        }

        ::-webkit-scrollbar-thumb{
            background:#cbd5e1;
            border-radius:999px;
        }

        ::-webkit-scrollbar-track{
            background:transparent;
        }

        .shadow-soft{
            box-shadow:
                0 10px 25px rgba(15, 23, 42, 0.05),
                0 4px 10px rgba(15, 23, 42, 0.03);
        }

        .form-label{
            display:block;
            margin-bottom:10px;
            font-size:14px;
            font-weight:800;
            color:#1e293b;
        }

        .form-input{
            width:100%;
            border-radius:20px;
            border:1px solid #dbe4ee;
            background:#ffffff;
            padding:16px 18px;
            font-size:14px;
            font-weight:600;
            color:#0f172a;
            outline:none;
            transition:all .3s ease;
        }

        .form-input:focus{
            border-color:#01588E;
            box-shadow:0 0 0 4px rgba(1,88,142,.10);
        }

        .btn-primary{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:18px;
            background:#01588E;
            padding:14px 24px;
            font-size:14px;
            font-weight:800;
            color:#ffffff;
            transition:all .3s ease;
        }

        .btn-primary:hover{
            background:#01446e;
            transform:translateY(-2px);
        }

        .btn-success{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:18px;
            background:linear-gradient(135deg,#41AD01 0%,#56c414 100%);
            padding:14px 24px;
            font-size:14px;
            font-weight:800;
            color:#ffffff;
            transition:all .3s ease;
            box-shadow:0 10px 20px rgba(65,173,1,.18);
        }

        .btn-success:hover{
            transform:translateY(-2px);
            box-shadow:0 15px 25px rgba(65,173,1,.25);
        }

        .card{
            border-radius:32px;
            background:#ffffff;
            padding:28px;
            box-shadow:
                0 10px 30px rgba(15,23,42,.05),
                0 4px 12px rgba(15,23,42,.03);
        }

        .table-head{
            background:#f8fafc;
            color:#475569;
        }

        .badge-success{
            border-radius:999px;
            background:#dcfce7;
            padding:8px 14px;
            font-size:12px;
            font-weight:800;
            color:#15803d;
        }

        .badge-primary{
            border-radius:999px;
            background:#dbeafe;
            padding:8px 14px;
            font-size:12px;
            font-weight:800;
            color:#1d4ed8;
        }

        .badge-warning{
            border-radius:999px;
            background:#fef3c7;
            padding:8px 14px;
            font-size:12px;
            font-weight:800;
            color:#b45309;
        }

        .badge-danger{
            border-radius:999px;
            background:#fee2e2;
            padding:8px 14px;
            font-size:12px;
            font-weight:800;
            color:#b91c1c;
        }
    </style>
</head>

<body class="bg-[#F4F8FB] text-slate-900 antialiased">

    <div id="mobileSidebarOverlay"
         class="fixed inset-0 z-40 hidden bg-black/50 backdrop-blur-sm lg:hidden">
    </div>

    {{-- SIDEBAR --}}
    @include('frontend.partials.pasien.sidebar')

    <main class="min-h-screen lg:pl-72">

        {{-- NAVBAR PASIEN --}}
        @include('frontend.partials.pasien.navbar')

        {{-- CONTENT --}}
        <section class="relative overflow-hidden p-4 sm:p-6 lg:p-8">

            {{-- BACKGROUND EFFECT --}}
            <div class="pointer-events-none absolute inset-0 overflow-hidden">

                <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-[#01588E]/5 blur-3xl"></div>

                <div class="absolute bottom-0 left-0 h-72 w-72 rounded-full bg-[#41AD01]/5 blur-3xl"></div>

            </div>

            <div class="relative">
                @yield('content')
            </div>

        </section>

    </main>

    {{-- ALPINE JS --}}
    <script defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>