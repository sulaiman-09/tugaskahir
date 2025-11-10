<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export News PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>News Export</h3>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Image Caption</th>
                <th>Created By (User ID)</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($newsList as $news)
                <tr>
                    <td>{{ $news->news_title }}</td>
                    <td>{{ $news->news_image_caption }}</td>
                    <td>{{ $news->news_user_id }}</td>
                    <td>{{ $news->news_created_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
