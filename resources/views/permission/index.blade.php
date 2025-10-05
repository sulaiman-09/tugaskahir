@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
<div class="container">
    <h2>Permission Management</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Permission ID</th>
                <th>Permission Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission['id'] }}</td>
                    <td>{{ $permission['name'] }}</td>
                    <td>{{ $permission['description'] }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
