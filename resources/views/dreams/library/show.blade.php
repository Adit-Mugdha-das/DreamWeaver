<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $text->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0a0c1b] text-white min-h-screen font-sans px-4 md:px-12 py-8">

    <div class="max-w-4xl mx-auto bg-black/30 p-6 md:p-10 rounded-xl border border-purple-400/10 shadow-md">
        <h1 class="text-3xl font-extrabold text-purple-200 mb-2">{{ $text->title }}</h1>
        <p class="text-sm text-gray-400 mb-6 italic">by {{ $text->author }} ({{ ucfirst($text->type) }})</p>

        <div class="whitespace-pre-wrap text-base leading-7 text-gray-200">
            {!! nl2br(e($text->content)) !!}
        </div>
    </div>

    <div class="text-center mt-10">
        <a href="{{ route('library.index') }}" class="text-purple-300 hover:underline">← Back to Library</a>
    </div>

</body>
</html>
