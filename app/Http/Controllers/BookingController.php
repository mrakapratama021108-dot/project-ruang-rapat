<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $checked = false;
        $availableRooms = collect();

        if ($request->filled(['tanggal_rapat', 'jam_mulai', 'jam_selesai'])) {
            $tanggal = $request->tanggal_rapat;
            $mulai = substr($request->jam_mulai, 0, 5);
            $selesai = substr($request->jam_selesai, 0, 5);

            if ($mulai < '08:00' || $selesai > '16:00') {
                return redirect()->route('booking.create', ['tanggal_rapat' => $tanggal])
                    ->with('error', 'Jam operasional ruang rapat hanya berlaku pukul 08:00 hingga 16:00.');
            }

            if ($selesai <= $mulai) {
                return redirect()->route('booking.create', ['tanggal_rapat' => $tanggal])
                    ->with('error', 'Jam selesai harus lebih akhir dari jam mulai.');
            }

            $checked = true;

            $bookedRoomIds = Booking::whereDate('tanggal_rapat', $tanggal)
                ->where('jam_mulai', '<', $selesai)
                ->where('jam_selesai', '>', $mulai)
                ->pluck('room_id')
                ->unique();

            $bookedRooms = Room::whereIn('id', $bookedRoomIds)->get();
            $allRooms = Room::all();

            $blockedRoomIds = collect();
            foreach ($bookedRooms as $bookedRoom) {
                $blockedRoomIds = $blockedRoomIds->merge($this->getBentrokRoomIds($bookedRoom, $allRooms));
            }

            $availableRooms = $allRooms->whereNotIn('id', $blockedRoomIds->unique())->values();
        }

        return view('booking.create', compact('availableRooms', 'checked'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tanggal_rapat' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'judul_rapat' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:1',
            'fasilitas_tambahan' => 'nullable|string|max:255',
        ]);

        $jamMulai = substr($request->jam_mulai, 0, 5);
        $jamSelesai = substr($request->jam_selesai, 0, 5);

        if ($jamMulai < '08:00' || $jamSelesai > '16:00') {
            return back()->withInput()->with('error', 'Jam operasional ruang rapat hanya berlaku pukul 08:00 hingga 16:00.');
        }

        if ($jamSelesai <= $jamMulai) {
            return back()->withInput()->with('error', 'Jam selesai harus lebih akhir dari jam mulai.');
        }

        $room = Room::findOrFail($request->room_id);

        if ($request->jumlah_peserta > $room->kapasitas) {
            return back()->withInput()->with(
                'error',
                "Jumlah peserta ({$request->jumlah_peserta} org) melebihi kapasitas maksimal {$room->nama_ruangan} ({$room->kapasitas} org)."
            );
        }

        $relatedRoomIds = $this->getBentrokRoomIds($room, Room::all());

        $bookingBentrok = Booking::with('room')
            ->whereIn('room_id', $relatedRoomIds)
            ->whereDate('tanggal_rapat', $request->tanggal_rapat)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->first();

        if ($bookingBentrok) {
            $namaRuangBentrok = $bookingBentrok->room->nama_ruangan ?? 'ruangan terkait';
            return back()->withInput()->with(
                'error',
                "Jadwal bentrok! {$namaRuangBentrok} sudah dibooking pada tanggal dan jam tersebut."
            );
        }

        Booking::create([
            'room_id' => $request->room_id,
            'tanggal_rapat' => $request->tanggal_rapat,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'judul_rapat' => $request->judul_rapat,
            'penanggung_jawab' => $request->penanggung_jawab,
            'jumlah_peserta' => $request->jumlah_peserta,
            'fasilitas_tambahan' => $request->fasilitas_tambahan,
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking selesai! Jadwal ruang rapat berhasil dikonfirmasi.');
    }

    // Simpan / Update Tautan, Kode, dan Password Zoom dari Dashboard
    public function updateZoom(Request $request, Booking $booking)
    {
        $request->validate([
            'tautan_zoom' => 'nullable|string|max:500',
            'kode_zoom' => 'nullable|string|max:100',
            'password_zoom' => 'nullable|string|max:100',
        ]);

        $booking->update($request->only([
            'tautan_zoom',
            'kode_zoom',
            'password_zoom',
        ]));

        return redirect()->route('dashboard', request()->only('minggu'))
            ->with('success', "Detail Zoom untuk rapat \"{$booking->judul_rapat}\" berhasil disimpan.");
    }

    /**
     * Mendeteksi ID ruangan mana saja yang saling beririsan fisik (Sehat, Cerdas, Ceria).
     */
    private function getBentrokRoomIds(Room $targetRoom, $allRooms): array
    {
        $getUnitFisik = function (string $nama): array {
            $units = [];
            if (stripos($nama, 'sehat') !== false) {
                $units[] = 'sehat';
            }
            if (stripos($nama, 'cerdas') !== false) {
                $units[] = 'cerdas';
            }
            if (stripos($nama, 'ceria') !== false) {
                $units[] = 'ceria';
            }
            return $units;
        };

        $targetUnits = $getUnitFisik($targetRoom->nama_ruangan);

        if (empty($targetUnits)) {
            return [$targetRoom->id];
        }

        return $allRooms->filter(function ($room) use ($targetUnits, $getUnitFisik) {
            $roomUnits = $getUnitFisik($room->nama_ruangan);
            return count(array_intersect($targetUnits, $roomUnits)) > 0;
        })->pluck('id')->all();
    }
}