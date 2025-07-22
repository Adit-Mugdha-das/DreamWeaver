<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Dreams PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            background-color: #fff;
        }

        h2 {
            text-align: center;
            color: #6b21a8;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .dream {
            margin-bottom: 35px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f5ff;
        }

        .title {
            font-weight: bold;
            font-size: 18px;
            color: #6b21a8;
            margin-bottom: 8px;
        }

        .content {
            font-size: 14px;
            color: #111827;
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            color: #4c1d95;
            margin-top: 8px;
        }

        .text {
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <h2>{{ $user->name }}'s Dream Journal</h2>

    @foreach ($dreams as $dream)
        <div class="dream">
            <div class="title">{{ $dream->title }}</div>
            <div class="content">{{ $dream->content }}</div>

            @if ($dream->emotion_summary)
                <div class="label">💫 Emotion:</div>
                <div class="text">{{ $dream->emotion_summary }}</div>
            @endif

            @if ($dream->short_interpretation)
                <div class="label"> Short Interpretation:</div>
                <div class="text">{{ $dream->short_interpretation }}</div>
            @endif

            @if ($dream->story_generation)
                <div class="label">📖 Story Generation:</div>
                <div class="text">{{ $dream->story_generation }}</div>
            @endif

            @if ($dream->long_narrative)
                <div class="label">🪄 Long Narrative:</div>
                <div class="text">{{ $dream->long_narrative }}</div>
            @endif
        </div>
    @endforeach

</body>
</html>
