<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export PDF Banner</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
    <h3>Data Banner</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Web Image</th>
                <th>Mobile Image</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $i)
                <tr>
                    <td>{{ $i->id }}</td>
                    <td>{{ $i->name }}</td>
                    <td>{{ $i->web_image }}</td>
                    <td>{{ $i->mobile_image }}</td>
                    <td>{{ $i->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
