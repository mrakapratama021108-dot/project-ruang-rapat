<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="8" style="font-size: 16pt; font-weight: bold;">LAPORAN PENGGUNAAN RUANG RAPAT</td>
        </tr>
        <tr>
            <td colspan="8" style="font-size: 10pt; color: #555555;">
                Periode:
                @if(request('tanggal_mulai') && request('tanggal_selesai'))
                    {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d/m/Y') }}
                @else
                    Semua Waktu
                @endif
                @if(request('search'))
                    | Kata Kunci: "{{ request('search') }}"
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
    </table>

    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #1e293b; color: #ffffff; font-weight: bold; text-align: center;">
                <th style="border: 1px solid #000000;">No</th>
                <th style="border: 1px solid #000000;">Tanggal Rapat</th>
                <th style="border: 1px solid #000000;">Jam Mulai</th>
                <th style="border: 1px solid #000000;">Jam Selesai</th>
                <th style="border: 1px solid #000000;">Ruangan</th>
                <th style="border: 1px solid #000000;">Judul / Agenda Rapat</th>
                <th style="border: 1px solid #000000;">Penanggung Jawab</th>
                <th style="border: 1px solid #000000;">Jumlah Peserta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $item)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ \Carbon\Carbon::parse($item->tanggal_rapat)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                <td style="border: 1px solid #000000;">{{ $item->room->nama_ruangan ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $item->judul_rapat }}</td>
                <td style="border: 1px solid #000000;">{{ $item->penanggung_jawab }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item->jumlah_peserta ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="border: 1px solid #000000; text-align: center;">Tidak ada data penggunaan ruang rapat.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="7" style="border: 1px solid #000000; text-align: right;">Total Rapat Tercatat:</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $riwayat->count() }} Rapat</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>