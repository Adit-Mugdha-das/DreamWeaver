<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Dreams PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; }
        h2 { text-align: center; color: #6b21a8; margin-bottom: 20px; }
        .dream {
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .emotion {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #6b21a8;
        }
        .short {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #1e3a8a;
        }
    </style>
</head>
<body>
    <h2>{{ $user->name }}'s Dream Journal</h2>

    @foreach ($dreams as $dream)
        <div class="dream">
            <div class="title">{{ $dream->title }}</div>
            <div>{{ $dream->content }}</div>

            @if ($dream->emotion_summary)
                <div class="emotion">Emotion: {{ $dream->emotion_summary }}</div>
            @endif

            @if ($dream->short_interpretation)
                <div class="short"><strong>Short Interpretation:</strong> {{ $dream->short_interpretation }}</div>
            @endif
        </div>
    @endforeach
</body>
</html>
