<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🌙 Shared Dreams | DreamWeaver</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="//unpkg.com/alpinejs" defer></script>

  <!-- AOS for animations -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    document.addEventListener('alpine:init', () => {
      AOS.init({
        once: true,
        duration: 800,
        easing: 'ease-out-cubic',
      });
    });
  </script>
  <style>
    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-thumb {
      background: #9333ea;
      border-radius: 10px;
    }
  </style>
</head>
<body class="bg-[#0a0c1b] text-white font-sans px-4 py-10 min-h-screen">

  <!-- Back Button -->
  <a href="{{ url('/welcome') }}" class="absolute top-4 left-4 text-white bg-fuchsia-700 hover:bg-fuchsia-600 px-4 py-2 rounded-lg transition">
    ⬅ Back
  </a>

  <!-- Title -->
  <h1 class="text-4xl md:text-5xl text-center font-bold text-fuchsia-400 mb-10" data-aos="fade-down">
    🌐 Explore Shared Dreams
  </h1>

  <!-- Dream Cards -->
  <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3 max-w-7xl mx-auto">
    @forelse ($dreams as $dream)
      <div data-aos="zoom-in" class="bg-black/50 border border-fuchsia-500/20 rounded-xl p-6 shadow-lg hover:shadow-fuchsia-500/30 transition duration-300">
        <h2 class="text-2xl font-semibold text-fuchsia-400 mb-2">{{ $dream->title }}</h2>
        <p class="text-sm text-gray-400 mb-4">👤 Shared by: {{ $dream->user->name }}</p>
        <p class="text-gray-200 leading-relaxed">{{ Str::limit($dream->content, 280, '...') }}</p>
      </div>
    @empty
      <p class="text-center text-gray-400 col-span-full mt-20">No dreams have been shared yet.</p>
    @endforelse
  </div>

</body>
</html>
