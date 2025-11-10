<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Product PDF</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Product List</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Category</th><th>Speed</th><th>Price</th><th>Description</th><th>Show Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td>{{ $p->speed }}</td>
                    <td>{{ $p->price }}</td>
                    <td>{{ strip_tags($p->description) }}</td>
                    <td>{{ $p->show_price ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
