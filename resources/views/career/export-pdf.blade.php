<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Career Export PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Career List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Education Level</th>
                <th>Location</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($careers as $career)
            <tr>
                <td>{{ $career->id }}</td>
                <td>{{ $career->title }}</td>
                <td>{{ $career->type }}</td>
                <td>{{ $career->education_level }}</td>
                <td>{{ $career->location }}</td>
                <td>{{ $career->is_active ? 'Active' : 'Inactive' }}</td>
                <td>{{ $career->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
