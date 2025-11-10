<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Tower</th>
            <th>Package</th>
            <th>Status</th>
            <th>Dibuat Pada</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->name }}</td>
                <td>{{ $c->phone }}</td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->tower }}</td>
                <td>{{ $c->package }}</td>
                <td>{{ $c->status }}</td>
                <td>{{ $c->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
