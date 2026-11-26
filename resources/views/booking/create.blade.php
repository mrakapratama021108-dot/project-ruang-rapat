@extends('layouts.app')

@section('content')
@php
    $slotJamMulai = [
        '08:00', '08:30', '09:00', '09:30',
        '10:00', '10:30', '11:00', '11:30',
        '12:00', '12:30', '13:00', '13:30',
        '14:00', '14:30', '15:00', '15:30',
    ];

    $slotJamSelesai = [
        '08:30', '09:00', '09:30', '10:00',
        '10:30', '11:00', '11:30', '12:00',
        '12:30', '13:00', '13:30', '14:00',
        '14:30', '15:00', '15:30', '16:00',
    ];

    $selectedMulai = request('jam_mulai') ? substr(request('jam_mulai'), 0, 5) : '';
    $selectedSelesai = request('jam_selesai') ? substr(request('jam_selesai'), 0, 5) : '';
@endphp

<div class="mb-6">
    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Booking Ruang Rapat</h2>
    <p class="text-sm text-zinc-500 mt-0.5">Tentukan waktu rapat (operasional 08:00 – 16:00) untuk melihat ruangan yang kosong.</p>
</div>

<div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-xs mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="w-8 h-8 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
        <h3 class="text-base font-bold text-zinc-900">Pilih Tanggal & Jam Rapat</h3>
    </div>

    <form method="GET" action="{{ route('booking.create') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Tanggal Rapat</label>
            <input type="date" name="tanggal_rapat" value="{{ request('tanggal_rapat', date('Y-m-d')) }}" required
                   onclick="try{this.showPicker()}catch(e){}"
                   class="w-full cursor-pointer bg-zinc-50 hover:bg-zinc-100 border border-zinc-300 rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Jam Mulai</label>
            <select name="jam_mulai" required
                    class="w-full cursor-pointer bg-zinc-50 hover:bg-zinc-100 border border-zinc-300 rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <option value="" disabled {{ $selectedMulai === '' ? 'selected' : '' }}>Pilih jam mulai</option>
                @foreach($slotJamMulai as $jam)
                    <option value="{{ $jam }}" {{ $selectedMulai === $jam ? 'selected' : '' }}>{{ $jam }} WIB</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Jam Selesai</label>
            <select name="jam_selesai" required
                    class="w-full cursor-pointer bg-zinc-50 hover:bg-zinc-100 border border-zinc-300 rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <option value="" disabled {{ $selectedSelesai === '' ? 'selected' : '' }}>Pilih jam selesai</option>
                @foreach($slotJamSelesai as $jam)
                    <option value="{{ $jam }}" {{ $selectedSelesai === $jam ? 'selected' : '' }}>{{ $jam }} WIB</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="w-full cursor-pointer bg-zinc-900 hover:bg-zinc-800 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            Cek Ketersediaan
        </button>
    </form>
</div>

@if($checked)
    @if($availableRooms->isEmpty())
        <div class="bg-amber-50 border border-amber-200 text-amber-900 p-5 rounded-2xl flex items-start gap-3.5">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
            </svg>
            <div>
                <p class="font-bold text-sm">Semua ruangan penuh pada jam tersebut</p>
                <p class="text-xs text-amber-700 mt-1">Silakan ubah tanggal atau geser jam rapat di atas untuk mencari slot yang kosong.</p>
            </div>
        </div>
    @else
        <div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-xs">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-8 h-8 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                <h3 class="text-base font-bold text-zinc-900">Pilih Ruangan & Lengkapi Detail</h3>
            </div>

            <form method="POST" action="{{ route('booking.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="tanggal_rapat" value="{{ request('tanggal_rapat') }}">
                <input type="hidden" name="jam_mulai" value="{{ $selectedMulai }}">
                <input type="hidden" name="jam_selesai" value="{{ $selectedSelesai }}">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-2.5">
                        Ruangan Tersedia ({{ $availableRooms->count() }} Pilihan)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($availableRooms as $index => $room)
                        @php
                            $isTerpadu = stripos($room->nama_ruangan, 'Terpadu') !== false;
                            $gambarRuangan = '';
                            if (!$isTerpadu) {
                                if (stripos($room->nama_ruangan, 'Cerdas') !== false) {
                                    $gambarRuangan = asset('foto/sidang-cerdas.jpg');
                                } elseif (stripos($room->nama_ruangan, 'Ceria') !== false) {
                                    $gambarRuangan = asset('foto/sidang-ceria.jpg');
                                } else {
                                    $gambarRuangan = asset('foto/sidang-sehat.jpg');
                                }
                            } elseif (
                                stripos($room->nama_ruangan, 'Sehat') !== false &&
                                stripos($room->nama_ruangan, 'Cerdas') !== false &&
                                stripos($room->nama_ruangan, 'Ceria') === false
                            ) {
                                $gambarRuangan = asset('foto/sidang-sehat-cerdas.jpg');
                            }
                        @endphp
                        <label class="cursor-pointer block">
                            <div class="p-4 rounded-xl border-2 border-zinc-200 bg-zinc-50/50 hover:border-indigo-600 transition h-full flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2">
                                            <input type="radio" name="room_id" value="{{ $room->id }}"
                                                   data-nama="{{ $room->nama_ruangan }}"
                                                   data-kapasitas="Maks. {{ $room->kapasitas }} org"
                                                   data-fasilitas="{{ $room->fasilitas }}"
                                                   data-gambar="{{ $gambarRuangan }}"
                                                   {{ (old('room_id') ? old('room_id') == $room->id : $index === 0) ? 'checked' : '' }}
                                                   required class="w-4 h-4 mt-0.5 cursor-pointer shrink-0 room-radio">
                                            <span class="font-bold text-sm text-zinc-900 leading-snug">{{ $room->nama_ruangan }}</span>
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-white border border-zinc-200 text-zinc-700 shrink-0">
                                            Maks. {{ $room->kapasitas }} org
                                        </span>
                                    </div>
                                    <p class="text-xs text-zinc-500 mt-2 leading-relaxed">{{ $room->fasilitas }}</p>
                                </div>
                                <div class="mt-3 pt-2 border-t border-zinc-200/60 flex items-center justify-between text-xs">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                    </span>
                                    @if($isTerpadu)
                                        <span class="px-2 py-0.5 rounded font-semibold bg-indigo-50 text-indigo-600">Ruang Gabungan</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <!-- KOTAK PREVIEW RUANGAN TERPILIH -->
                    <div class="mt-4 p-4 rounded-2xl border border-zinc-200 bg-zinc-50/70" style="display: flex; align-items: center; gap: 18px; flex-wrap: wrap;">
                        <div id="preview-gambar-wrapper" style="width: 240px; height: 145px; flex-shrink: 0; overflow: hidden; border-radius: 12px; border: 1px solid #e4e4e7; background-color: #e4e4e7;">
                            <img id="preview-gambar"
                                 src="{{ asset('foto/sidang-sehat.jpg') }}"
                                 alt="Preview Ruangan"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 0.2s;">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full mb-1.5">
                                Preview Ruangan Terpilih
                            </span>
                            <h4 id="preview-nama" class="text-base font-bold text-zinc-900"></h4>
                            <p id="preview-kapasitas" class="text-xs font-semibold text-zinc-700 mt-0.5"></p>
                            <p id="preview-fasilitas" class="text-xs text-zinc-500 mt-1.5 leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Judul / Agenda Rapat</label>
                        <input type="text" name="judul_rapat" value="{{ old('judul_rapat') }}" required placeholder="Contoh: Rapat Evaluasi Bulanan"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Penanggung Jawab</label>
                        <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required placeholder="Nama penanggung jawab"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Jumlah Peserta</label>
                        <input type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" min="1" required placeholder="Sesuai kapasitas ruangan"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-1.5">Fasilitas Tambahan (Opsional)</label>
                        <input type="text" name="fasilitas_tambahan" value="{{ old('fasilitas_tambahan') }}" placeholder="Contoh: Kabel HDMI, Mic Wireless, Makanan Berat"
                               class="w-full bg-zinc-50 border border-zinc-300 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-xl shadow-sm transition">
                        Konfirmasi & Simpan Booking
                    </button>
                </div>
            </form>
        </div>
    @endif
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.room-radio');
        const previewWrapper = document.getElementById('preview-gambar-wrapper');
        const previewImg = document.getElementById('preview-gambar');
        const previewNama = document.getElementById('preview-nama');
        const previewKapasitas = document.getElementById('preview-kapasitas');
        const previewFasilitas = document.getElementById('preview-fasilitas');

        function updatePreview(radio) {
            if (!radio) return;
            previewNama.textContent = radio.dataset.nama;
            previewKapasitas.textContent = 'Kapasitas: ' + radio.dataset.kapasitas;
            previewFasilitas.textContent = 'Fasilitas: ' + radio.dataset.fasilitas;

            if (radio.dataset.gambar) {
                previewWrapper.style.display = 'block';
                previewImg.style.opacity = '0.4';
                setTimeout(() => {
                    previewImg.src = radio.dataset.gambar;
                    previewImg.style.opacity = '1';
                }, 100);
            } else {
                previewWrapper.style.display = 'none';
            }
        }

        const checkedRadio = document.querySelector('.room-radio:checked');
        updatePreview(checkedRadio);

        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                updatePreview(this);
            });
        });
    });
</script>
@endsection