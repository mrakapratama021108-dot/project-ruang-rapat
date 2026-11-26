<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrasi — Manajemen Ruang Rapat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 text-zinc-900 antialiased">
    <nav class="bg-white border-b border-zinc-200/80 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 font-bold text-lg tracking-tight text-zinc-900">
                <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm font-extrabold">A</span>
                Administrasi - BookingRuangRapat
            </a>
            <div class="flex items-center gap-1 sm:gap-2 text-sm font-medium">
                <a href="{{ route('dashboard') }}"
                   class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-zinc-600 hover:bg-zinc-100' }}">
                    Dashboard
                </a>
                <a href="{{ route('booking.create') }}"
                   class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('booking.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-zinc-600 hover:bg-zinc-100' }}">
                    Booking Ruangan
                </a>
                <a href="{{ route('riwayat.index') }}"
                   class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('riwayat.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-zinc-600 hover:bg-zinc-100' }}">
                    Riwayat & Laporan
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        @if(session('success'))
            <div id="flash-alert" style="transition: opacity 0.4s ease;"
                 class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl text-sm font-medium flex items-center justify-between gap-4">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="closeFlashAlert()" class="cursor-pointer text-emerald-600 hover:text-emerald-900 font-bold text-base leading-none">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div id="flash-alert" style="transition: opacity 0.4s ease;"
                 class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-xl text-sm font-medium flex items-center justify-between gap-4">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="closeFlashAlert()" class="cursor-pointer text-red-600 hover:text-red-900 font-bold text-base leading-none">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function closeFlashAlert() {
            const alert = document.getElementById('flash-alert');
            if (!alert) return;
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }

        setTimeout(closeFlashAlert, 3500);

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                const alert = document.getElementById('flash-alert');
                if (alert) alert.remove();
            }
        });
    </script>
</body>
</html>