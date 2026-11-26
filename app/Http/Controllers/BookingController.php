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
            $checked = true;
            $tanggal = $request->tanggal_rapat;
            $mulai = $request->jam_mulai;
            $selesai = $request->jam_selesai;

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

        if ($request->jam_selesai <= $request->jam_mulai) {
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
            ->where('jam_mulai', '<', $request->jam_selesai)
            ->where('jam_selesai', '>', $request->jam_mulai)
            ->first();

        if ($bookingBentrok) {
            $namaRuangBentrok = $bookingBentrok->room->nama_ruangan ?? 'ruangan terkait';
            return back()->withInput()->with(
                'error',
                "Jadwal bentrok! {$namaRuangBentrok} sudah dibooking pada tanggal dan jam tersebut."
            );
        }

        Booking::create($request->only([
            'room_id',
            'tanggal_rapat',
            'jam_mulai',
            'jam_selesai',
            'judul_rapat',
            'penanggung_jawab',
            'jumlah_peserta',
            'fasilitas_tambahan',
        ]));

        return redirect()->route('dashboard')->with('success', 'Booking selesai! Jadwal ruang rapat berhasil dikonfirmasi.');
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