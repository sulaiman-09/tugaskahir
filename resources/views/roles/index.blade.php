@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="container">
    <h2>Role Management</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Role ID</th>
                <th>Role Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role['id'] }}</td>
                    <td>{{ $role['name'] }}</td>
                    <td>{{ $role['description'] }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
