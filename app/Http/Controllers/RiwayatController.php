<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    // Filter & Cari Data Rapat
    public function index(Request $request)
    {
        $query = Booking::with('room');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_rapat', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_rapat', 'like', "%{$search}%")
                  ->orWhere('penanggung_jawab', 'like', "%{$search}%")
                  ->orWhereHas('room', fn($r) => $r->where('nama_ruangan', 'like', "%{$search}%"));
            });
        }

        $riwayat = $query->orderByDesc('tanggal_rapat')->orderByDesc('jam_mulai')->get();

        return view('riwayat.index', compact('riwayat'));
    }

    // Lihat Detail Rapat
    public function show(Booking $booking)
    {
        $booking->load('room');
        return view('riwayat.show', compact('booking'));
    }

    // Batalkan Booking Ruangan
    public function destroy(Booking $booking)
    {
        $namaRuangan = $booking->room->nama_ruangan ?? 'Ruangan';
        $judulRapat = $booking->judul_rapat;

        $booking->delete();

        return redirect()->route('riwayat.index')
            ->with('success', "Booking \"{$judulRapat}\" dibatalkan. {$namaRuangan} kembali tersedia.");
    }

    // Export / Cetak Laporan ke File Excel (.xls)
    public function cetak(Request $request)
    {
        $query = Booking::with('room');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_rapat', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_rapat', 'like', "%{$search}%")
                  ->orWhere('penanggung_jawab', 'like', "%{$search}%")
                  ->orWhereHas('room', fn($r) => $r->where('nama_ruangan', 'like', "%{$search}%"));
            });
        }

        $riwayat = $query->orderBy('tanggal_rapat')->orderBy('jam_mulai')->get();

        $namaFile = 'Laporan_Ruang_Rapat_' . date('Y-m-d_His') . '.xls';

        return response()
            ->view('riwayat.cetak', compact('riwayat'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}