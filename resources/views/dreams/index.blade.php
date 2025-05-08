<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dream Diary</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-center text-indigo-600 mb-6">🌙 All Dreams</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @foreach ($dreams as $dream)
            <div class="bg-white rounded-lg shadow-md p-6 mb-5">
                <h3 class="text-xl font-semibold text-indigo-700 mb-2">{{ $dream->title }}</h3>
                <p class="text-gray-700 mb-3">{{ $dream->content }}</p>

                @if ($dream->emotion_summary)
                    <span class="inline-block px-3 py-1 text-sm rounded-full text-white 
                        {{ $dream->emotion_summary === 'Fear' ? 'bg-red-500' : 
                            ($dream->emotion_summary === 'Joy' ? 'bg-green-500' : 'bg-blue-500') }}">
                        Emotion: {{ $dream->emotion_summary }}
                    </span>
                @else
                    <span class="text-gray-400 text-sm">No emotion detected</span>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
