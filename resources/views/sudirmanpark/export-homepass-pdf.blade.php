<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Homepass Sudirman Park</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #555;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h3>Data Homepass Sudirman Park</h3>
    <table>
        <thead>
            <tr>
                <th>Tower</th>
                <th>Floor</th>
                <th>Unit</th>
                <th>Alamat Lengkap</th>
                <th>Jumlah Customer</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($homepasses as $h)
                <tr>
                    <td>{{ $h->tower }}</td>
                    <td>{{ $h->floor }}</td>
                    <td>{{ $h->unit }}</td>
                    <td>{{ $h->full_address }}</td>
                    <td>{{ $h->jumlah_customer ?? 0 }}</td>
                    <td>{{ $h->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    <td>{{ $h->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
