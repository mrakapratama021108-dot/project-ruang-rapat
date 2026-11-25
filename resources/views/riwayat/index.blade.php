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
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Riwayat & Laporan Rapat</h2>
        <p class="text-sm text-zinc-500 mt-0.5">Filter data penggunaan ruang rapat dan cetak rekap laporan.</p>
    </div>
    <a href="{{ route('riwayat.cetak', request()->query()) }}" target="_blank"
       class="inline-flex items-center justify-center gap-2 bg-zinc-900 hover:bg-zinc-800 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
        </svg>
        Cetak Laporan
    </a>
</div>

<div class="bg-white p-5 rounded-2xl border border-zinc-200/80 shadow-xs mb-6">
    <form method="GET" action="{{ route('riwayat.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                   onclick="try{this.showPicker()}catch(e){}"
                   class="w-full cursor-pointer bg-zinc-50 border border-zinc-300 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Sampai Tanggal</label>
            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                   onclick="try{this.showPicker()}catch(e){}"
                   class="w-full cursor-pointer bg-zinc-50 border border-zinc-300 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Kata Kunci</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, penanggung jawab, atau ruangan..."
                   class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="w-full cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-xl transition">
                Terapkan Filter
            </button>
            @if(request()->hasAny(['tanggal_mulai', 'tanggal_selesai', 'search']))
                <a href="{{ route('riwayat.index') }}"
                   class="bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-sm font-semibold py-2 px-3 rounded-xl transition flex items-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-zinc-200/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-zinc-50/80 border-b border-zinc-200/70 text-xs uppercase tracking-wider text-zinc-500">
                    <th class="py-3.5 px-6 font-semibold">Tanggal</th>
                    <th class="py-3.5 px-6 font-semibold">Waktu</th>
                    <th class="py-3.5 px-6 font-semibold">Ruangan</th>
                    <th class="py-3.5 px-6 font-semibold">Judul Rapat</th>
                    <th class="py-3.5 px-6 font-semibold">Penanggung Jawab</th>
                    <th class="py-3.5 px-6 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($riwayat as $row)
                <tr class="hover:bg-zinc-50/80 transition">
                    <td class="py-4 px-6 font-medium text-zinc-700 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($row->tanggal_rapat)->format('d M Y') }}
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-zinc-100 text-zinc-700">
                            {{ substr($row->jam_mulai,0,5) }} – {{ substr($row->jam_selesai,0,5) }}
                        </span>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700">
                            {{ $row->room->nama_ruangan }}
                        </span>
                    </td>
                    <td class="py-4 px-6 font-semibold text-zinc-900">{{ $row->judul_rapat }}</td>
                    <td class="py-4 px-6 text-zinc-600">{{ $row->penanggung_jawab }}</td>
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('riwayat.show', $row->id) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50/70 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                Detail &rarr;
                            </a>
                            <form method="POST" action="{{ route('riwayat.destroy', $row->id) }}" class="inline"
                                  onsubmit="return confirm('Batalkan booking \"{{ $row->judul_rapat }}\"? Ruangan akan kembali tersedia.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="cursor-pointer inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-800 bg-red-50/70 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                    Batalkan
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 px-6 text-center text-sm text-zinc-400">
                        Data riwayat rapat tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection