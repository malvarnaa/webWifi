<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pelanggan Aktif</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        h2, p { text-align: center; margin: 0; }
    </style>
</head>
<body>

    <h2>Data Pelanggan Aktif</h2>
    <p>Periode: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>ID Pelanggan</th>
                <th>NIK</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Paket</th>
                <th>Tanggal Diterima</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_cust }}</td>
                    <td>{{ $item->user->id_pelanggan ?? '-' }}</td>
                    <td>{{ $item->nik ?? '-' }}</td>
                    <td>{{ $item->nomor_hp }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->alamat_lengkap }}, {{ $item->kec->nama_kec ?? '-' }}, {{ $item->kab->nama_kab ?? '-' }}, {{ $item->prov->nama_prov ?? '-' }}</td>
                    <td>{{ $item->paket->nama_paket ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_diterima)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
