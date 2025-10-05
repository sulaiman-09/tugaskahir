@extends('layouts.app')

@section('title', 'News Management')

@section('content')
<div class="container">
    <h2>News Management</h2>
    <a href="{{ route('news.create') }}" class="btn btn-primary mb-3">Add News</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>News ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Image</th>
                <th>Image App</th>
                <th>Caption</th>
                <th>Created Date</th>
                <th>Admin</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($news as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->news_title }}</td>
                    <td>{{ Str::limit($item->news_content, 50) }}</td>
                    <td>{{ $item->news_image }}</td>
                    <td>{{ $item->news_image_app }}</td>
                    <td>{{ $item->news_image_caption }}</td>
                    <td>{{ $item->news_created_date }}</td>
                    <td>{{ $item->admin }}</td>
                    <td>
                        <a href="{{ route('news.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('news.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this news?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $news->links() }}
</div>
@endsection
