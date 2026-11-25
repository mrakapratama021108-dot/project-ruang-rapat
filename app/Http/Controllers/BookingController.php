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

            $availableRooms = Room::whereDoesntHave('bookings', function ($query) use ($tanggal, $mulai, $selesai) {
                $query->whereDate('tanggal_rapat', $tanggal)
                    ->where('jam_mulai', '<', $selesai)
                    ->where('jam_selesai', '>', $mulai);
            })->get();
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

        $isBentrok = Booking::where('room_id', $request->room_id)
            ->whereDate('tanggal_rapat', $request->tanggal_rapat)
            ->where('jam_mulai', '<', $request->jam_selesai)
            ->where('jam_selesai', '>', $request->jam_mulai)
            ->exists();

        if ($isBentrok) {
            return back()->withInput()->with('error', 'Ruangan sudah terisi pada tanggal dan jam tersebut.');
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
}