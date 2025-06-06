<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🌙 Shared Dreams | DreamWeaver</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="//unpkg.com/alpinejs" defer></script>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    document.addEventListener('alpine:init', () => {
      AOS.init({ once: true, duration: 800, easing: 'ease-out-cubic' });
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
  <a href="{{ url('/welcome') }}" class="fixed top-4 left-4 text-white bg-fuchsia-700 hover:bg-fuchsia-600 px-4 py-2 rounded-lg transition shadow-lg">
    ⬅ Back
  </a>

  <!-- Heading -->
  <div class="text-center mb-12" data-aos="fade-down">
    <h1 class="text-5xl font-bold text-violet-400 mb-2 flex justify-center items-center gap-2">
      <span class="text-4xl">🪐</span> Explore Shared Dreams
    </h1>
    <p class="text-gray-400 text-sm md:text-base">Discover how others have wandered through their subconscious.</p>
  </div>

  <!-- Feed Section -->
  <div class="space-y-10 max-w-3xl mx-auto">
    @forelse ($dreams as $dream)
    <div class="bg-[#111827] border border-violet-500/10 rounded-2xl p-6 shadow-md hover:shadow-violet-600/10 transition" data-aos="fade-up" x-data="{ expanded: false, showComments: false }">
      
      <!-- User Info -->
      <div class="flex items-center gap-4 mb-4">
        <div class="w-12 h-12 bg-fuchsia-700/80 rounded-full flex items-center justify-center text-white font-bold text-lg">
          {{ strtoupper(substr($dream->user->name, 0, 1)) }}
        </div>
        <div>
          <p class="font-semibold text-white text-base">{{ $dream->user->name }}</p>
          <p class="text-xs text-gray-400">Shared • {{ $dream->created_at->diffForHumans() }}</p>
        </div>
      </div>

      <!-- Title -->
      <h2 class="text-xl font-bold text-fuchsia-400 mb-2">{{ $dream->title }}</h2>

      <!-- Content with Expand Option -->
      <p class="text-gray-300 text-sm leading-relaxed mb-2" x-show="expanded">
        {{ $dream->content }}
      </p>
      <p class="text-gray-300 text-sm leading-relaxed mb-2" x-show="!expanded">
        {{ Str::limit($dream->content, 300, '...') }}
      </p>
      <button @click="expanded = !expanded" class="text-xs text-violet-400 hover:underline transition">
        <span x-show="!expanded">Read more</span>
        <span x-show="expanded">Show less</span>
      </button>

      <!-- Interaction Buttons -->
      <div class="flex items-center gap-6 mt-4 text-sm text-gray-400">
        <button class="hover:text-fuchsia-400 transition">💜 Like</button>
        <button @click="showComments = !showComments" class="hover:text-fuchsia-400 transition">💬 Comment</button>
        <button class="hover:text-fuchsia-400 transition">🔗 Share</button>
      </div>

      <!-- Comments Section -->
      <div x-show="showComments" class="mt-6 border-t border-gray-700 pt-4 space-y-4">
        <input type="text" placeholder="Write a comment..." class="w-full px-4 py-2 bg-[#1f2937] text-white text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-600">
        
        <!-- Sample comment -->
        <div class="flex items-start gap-3 text-sm text-gray-300">
          <div class="w-8 h-8 bg-fuchsia-600/70 rounded-full flex items-center justify-center font-bold text-white">A</div>
          <div>
            <p><span class="font-semibold text-white">Aria:</span> This dream feels so familiar... beautiful!</p>
          </div>
        </div>
      </div>

    </div>
    @empty
      <p class="text-center text-gray-400 mt-20">No dreams have been shared yet.</p>
    @endforelse
  </div>

</body>
</html>
