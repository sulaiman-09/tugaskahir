<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Division Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background-color: #f5f5f5; }
        h3 { text-align: center; }
    </style>
</head>
<body>
    <h3>Division Report</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Customer Leads</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($divisions as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->name }}</td>
                    <td>{{ $d->description }}</td>
                    <td>{{ $d->status ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $d->customer_leads }}</td>
                    <td>{{ $d->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
