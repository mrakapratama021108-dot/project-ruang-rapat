@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('riwayat.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition">
            &larr; Kembali ke Riwayat
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xs overflow-hidden">
        <!-- Card Header -->
        <div class="bg-zinc-900 text-white p-6">
            <div class="flex items-center justify-between gap-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                    {{ $booking->room->nama_ruangan }}
                </span>
                <span class="text-xs text-zinc-400">ID #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h2 class="text-xl font-bold mt-3">{{ $booking->judul_rapat }}</h2>
            <p class="text-sm text-zinc-400 mt-1">
                {{ \Carbon\Carbon::parse($booking->tanggal_rapat)->format('d F Y') }} &bull;
                {{ substr($booking->jam_mulai,0,5) }} – {{ substr($booking->jam_selesai,0,5) }} WIB
            </p>
        </div>

        <!-- Metadata Grid -->
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Penanggung Jawab</p>
                <p class="font-semibold text-zinc-900 mt-1">{{ $booking->penanggung_jawab }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Jumlah Peserta</p>
                <p class="font-semibold text-zinc-900 mt-1">{{ $booking->jumlah_peserta }} Orang</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Fasilitas Bawaan Ruangan</p>
                <p class="text-zinc-700 mt-1">{{ $booking->room->fasilitas }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Kebutuhan Tambahan</p>
                <p class="text-zinc-700 mt-1">{{ $booking->fasilitas_tambahan ?? '-' }}</p>
            </div>
        </div>

        <!-- Card Footer: Tombol Batalkan Booking -->
        <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-200/80 flex items-center justify-between gap-4">
            <p class="text-xs text-zinc-500">Rapat dibatalkan? Kosongkan slot agar ruangan dapat digunakan.</p>
            <form method="POST" action="{{ route('riwayat.destroy', $booking->id) }}"
                  onsubmit="return confirm('Yakin ingin membatalkan booking \"{{ $booking->judul_rapat }}\"? Ruangan akan kembali tersedia pada jam tersebut.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="cursor-pointer inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-xs transition">
                    Batalkan Booking
                </button>
            </form>
        </div>
    </div>
</div>
@endsection