<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $daftarRuangan = Room::all();
        $totalRuangan = $daftarRuangan->count();

        $rapatHariIni = Booking::whereDate('tanggal_rapat', $today)->count();

        // Hitung rentang minggu berdasarkan parameter ?minggu= (0 = minggu ini, 1 = minggu depan, dst.)
        $offsetMinggu = (int) $request->query('minggu', 0);
        $awalMinggu = Carbon::today()->startOfWeek(Carbon::MONDAY)->addWeeks($offsetMinggu);
        $akhirMinggu = $awalMinggu->copy()->endOfWeek(Carbon::SUNDAY);

        // Jika melihat minggu ini, tampilkan mulai hari ini sampai Minggu akhir pekan
        $batasMulai = $offsetMinggu === 0 ? $today : $awalMinggu;

        $jadwalMingguan = Booking::with('room')
            ->whereBetween('tanggal_rapat', [
                $batasMulai->toDateString(),
                $akhirMinggu->toDateString()
            ])
            ->orderBy('tanggal_rapat', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('dashboard', compact(
            'daftarRuangan',
            'totalRuangan',
            'rapatHariIni',
            'jadwalMingguan',
            'offsetMinggu',
            'awalMinggu',
            'akhirMinggu'
        ));
    }
}