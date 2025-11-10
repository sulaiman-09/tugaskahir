<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background-color: #f3f3f3; }
    </style>
</head>
<body>
    <h3 style="text-align:center;">Customer Report</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Product</th>
                <th>Category</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->customer_name }}</td>
                    <td>{{ $c->customer_phone }}</td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->product->product_name ?? '-' }}</td>
                    <td>{{ $c->productCategory->name ?? '-' }}</td>
                    <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
