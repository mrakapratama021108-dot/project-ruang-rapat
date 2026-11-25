@extends('layouts.app')

@section('content')
@if(session('success'))
    <div id="alert-success" class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between transition-opacity duration-500">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="document.getElementById('alert-success').remove()" class="text-emerald-600 hover:text-emerald-900 font-bold text-lg leading-none cursor-pointer">&times;</button>
    </div>
    <script>
        setTimeout(() => {
            const alertBox = document.getElementById('alert-success');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3500);
    </script>
@endif

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Ringkasan Operasional</h2>
        <p class="text-sm text-zinc-500 mt-0.5">Pantau ketersediaan ruangan dan jadwal rapat yang akan datang.</p>
    </div>
    <a href="{{ route('booking.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Buat Jadwal Baru
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-xs flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Ruangan yang Tersedia</p>
            <p class="text-3xl font-bold text-zinc-900 mt-2">{{ $totalRuangan }} <span class="text-sm font-medium text-zinc-500">Ruangan</span></p>
            <div class="mt-3 flex flex-wrap gap-1.5">
                @foreach($daftarRuangan as $room)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-zinc-100 text-zinc-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $room->nama_ruangan }}
                    </span>
                @endforeach
            </div>
        </div>
        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/>
            </svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-xs flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Agenda Hari Ini</p>
            <p class="text-3xl font-bold text-zinc-900 mt-2">{{ $rapatHariIni }}</p>
            <p class="text-xs text-zinc-500 mt-1">Rapat terjadwal hari ini</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-xs flex flex-col justify-between">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Arsip & Rekap</p>
                <p class="text-sm font-semibold text-zinc-800 mt-2 leading-relaxed">Cek historis penggunaan ruangan & cetak laporan.</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('riwayat.index') }}"
           class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
            Buka Riwayat Rapat
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </div>
</div>

<!-- JADWAL RAPAT PER MINGGU -->
<div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xs overflow-hidden">
    <div class="px-6 py-4 border-b border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2.5">
                <h3 class="text-base font-bold text-zinc-900">Jadwal Rapat Mendatang</h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                    {{ $jadwalMingguan->count() }} agenda
                </span>
            </div>
            <p class="text-xs text-zinc-500 mt-0.5">
                Periode: <strong class="text-zinc-700">{{ $awalMinggu->format('d M Y') }} – {{ $akhirMinggu->format('d M Y') }}</strong>
                @if($offsetMinggu === 0)
                    <span class="text-emerald-600 font-semibold">(Minggu Ini)</span>
                @elseif($offsetMinggu === 1)
                    <span class="text-indigo-600 font-semibold">(Minggu Depan)</span>
                @endif
            </p>
        </div>

        <!-- Navigasi Per Minggu -->
        <div class="flex items-center gap-1.5">
            @if($offsetMinggu > 0)
                <a href="{{ route('dashboard', ['minggu' => $offsetMinggu - 1]) }}"
                   class="px-3 py-1.5 rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50 text-xs font-semibold text-zinc-700 transition">
                    &larr; Minggu Sebelumnya
                </a>
                <a href="{{ route('dashboard') }}"
                   class="px-3 py-1.5 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-xs font-semibold text-zinc-700 transition">
                    Minggu Ini
                </a>
            @endif
            <a href="{{ route('dashboard', ['minggu' => $offsetMinggu + 1]) }}"
               class="px-3 py-1.5 rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50 text-xs font-semibold text-zinc-700 transition">
                Minggu Berikutnya &rarr;
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-zinc-50/80 border-b border-zinc-200/70 text-xs uppercase tracking-wider text-zinc-500">
                    <th class="py-3.5 px-6 font-semibold">Hari & Tanggal</th>
                    <th class="py-3.5 px-6 font-semibold">Waktu</th>
                    <th class="py-3.5 px-6 font-semibold">Ruangan</th>
                    <th class="py-3.5 px-6 font-semibold">Agenda Rapat</th>
                    <th class="py-3.5 px-6 font-semibold">Penanggung Jawab</th>
                    <th class="py-3.5 px-6 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @php
                    $namaHari = [
                        'Sunday' => 'Minggu',
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                    ];
                @endphp
                @forelse($jadwalMingguan as $item)
                @php
                    $tgl = \Carbon\Carbon::parse($item->tanggal_rapat);
                    $hariIndo = $namaHari[$tgl->format('l')] ?? $tgl->format('l');
                @endphp
                <tr class="hover:bg-zinc-50/80 transition">
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-zinc-900">{{ $hariIndo }},</span>
                            <span class="font-medium text-zinc-600">{{ $tgl->format('d M Y') }}</span>
                            @if($tgl->isToday())
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700">
                                    Hari Ini
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-zinc-100 text-zinc-700">
                            {{ substr($item->jam_mulai,0,5) }} – {{ substr($item->jam_selesai,0,5) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700">
                            {{ $item->room->nama_ruangan }}
                        </span>
                    </td>
                    <td class="py-4 px-6 font-semibold text-zinc-900">{{ $item->judul_rapat }}</td>
                    <td class="py-4 px-6 text-zinc-600">
                        {{ $item->penanggung_jawab }}
                        <span class="text-xs text-zinc-400 ml-1">({{ $item->jumlah_peserta }} org)</span>
                    </td>
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <form method="POST" action="{{ route('riwayat.destroy', $item->id) }}" class="inline"
                              onsubmit="return confirm('Batalkan booking \"{{ $item->judul_rapat }}\"? Ruangan akan kembali tersedia.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="cursor-pointer inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-800 bg-red-50/70 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                Batalkan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 px-6 text-center">
                        <p class="text-sm font-medium text-zinc-500">Tidak ada jadwal rapat pada minggu ini</p>
                        <p class="text-xs text-zinc-400 mt-1">Gunakan tombol navigasi minggu di atas atau klik "Buat Jadwal Baru".</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection