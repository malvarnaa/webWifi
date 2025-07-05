<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pelanggan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Data Pelanggan Aktif</h2>
    <p>Periode: {{ $start }} - {{ $end }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Tgl Diterima</th>
                <th>Jatuh Tempo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggans as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nama_cust }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->nomor_hp }}</td>
                    <td>{{ $p->nik }}</td>
                    <td>{{ $p->alamat_lengkap }}</td>
                    <td>{{ $p->status_kepelangganan }}</td>
                    <td>{{ $p->tanggal_diterima }}</td>
                    <td>{{ $p->jatuh_tempo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
