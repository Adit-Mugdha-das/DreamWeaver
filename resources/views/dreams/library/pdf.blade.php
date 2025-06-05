<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $text->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #111;
            line-height: 1.6;
            padding: 30px;
        }

        h1 {
            color: #10b981; /* emerald-500 */
            font-size: 26px;
            margin-bottom: 5px;
        }

        p.meta {
            font-style: italic;
            font-size: 14px;
            margin-bottom: 20px;
            color: #555;
        }

        .content {
            white-space: pre-wrap;
            font-size: 16px;
            color: #222;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h1>{{ $text->title }}</h1>
    <p class="meta">by {{ $text->author }} ({{ ucfirst($text->type) }})</p>
    <hr>
    <div class="content">
        {{ $text->content }}
    </div>
</body>
</html>
