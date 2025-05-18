<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $dream->title }}</title>
    <style>
        @page { margin: 40px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            background-color: #fff;
        }

        h2 {
            text-align: center;
            color: #9333ea;
            font-size: 26px;
            margin-bottom: 35px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .section {
            margin-bottom: 25px;
        }

        .label {
            color: #7e22ce;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 3px;
        }

        .value {
            font-size: 14px;
            color: #374151;
            line-height: 1.6;
        }

        .card {
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background-color: #f9f5ff;
            box-shadow: 0 0 6px rgba(147, 51, 234, 0.12);
        }
    </style>
</head>
<body>

    <h2>{{ $user->name }}'s Dream Entry</h2>

    <div class="card">
        <div class="section">
            <div class="label">🌙 Title:</div>
            <div class="value">{{ $dream->title }}</div>
        </div>

        <div class="section">
            <div class="label">📝 Content:</div>
            <div class="value">{{ $dream->content }}</div>
        </div>

        @if ($dream->emotion_summary)
        <div class="section">
            <div class="label">💫 Emotion:</div>
            <div class="value">{{ $dream->emotion_summary }}</div>
        </div>
        @endif

        @if ($dream->short_interpretation)
        <div class="section">
            <div class="label">🔮 Short Interpretation:</div>
            <div class="value">{{ $dream->short_interpretation }}</div>
        </div>
        @endif
    </div>

</body>
</html>
