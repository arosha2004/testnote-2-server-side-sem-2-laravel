<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $note->title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #111;
        }
        .meta {
            font-size: 12px;
            color: #777;
        }
        .category {
            display: inline-block;
            background: #eef2ff;
            color: #4f46e5;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            margin-bottom: 10px;
        }
        .content {
            font-size: 14px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($note->categories->isNotEmpty())
            <div class="category">{{ $note->categories->pluck('category_name')->join(', ') }}</div>
        @else
            <div class="category">Uncategorized</div>
        @endif
        <h1 class="title">{{ $note->title }}</h1>
        <div class="meta">
            Created: {{ $note->created_at->format('M d, Y \a\t h:i A') }} <br>
            Updated: {{ $note->updated_at->format('M d, Y \a\t h:i A') }}
        </div>
    </div>
    <div class="content">
        {{ $note->content }}
    </div>
</body>
</html>
