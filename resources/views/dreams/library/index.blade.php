<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Library</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="//unpkg.com/alpinejs" defer></script>

  <!-- AOS -->
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
</head>
<body x-data="{ modalOpen: false, modalImage: '' }" @keydown.escape="modalOpen = false"
      class="bg-[#0a0c1b] text-white min-h-screen font-sans px-6 py-10">

  <!-- Home Button Top Left -->
  <a href="{{ url('/welcome') }}"
   class="absolute top-6 left-6 text-base px-5 py-2.5 rounded-lg font-semibold text-indigo-300 hover:text-white bg-indigo-900/40 hover:bg-indigo-700/60 border border-indigo-400/20 shadow-md backdrop-blur transition duration-300">
  ← Home
</a>




  <!-- Centered Title -->
  <h1 class="text-4xl font-extrabold mb-10 text-purple-300 tracking-wider text-center"> Dream Library</h1>

  @php $grouped = $texts->groupBy('type'); @endphp

  @foreach(['poem' => 'Poems ✨', 'story' => 'Stories 📖', 'myth' => 'Myths 🐉'] as $key => $label)
    @if($grouped->has($key))
      <h2 class="text-2xl font-bold text-fuchsia-300 mt-10 mb-4">{{ $label }}</h2>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($grouped[$key] as $index => $text)
          <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}"
               class="p-5 rounded-2xl bg-black/40 border border-fuchsia-400/10 hover:shadow-[0_0_15px_#a78bfa55] transition-all group">

            @php
              $imgMap = [
                'Dreams' => ['Langston Hughes', 'langston_dreams.png'],
                'Kubla Khan' => ['Samuel Taylor Coleridge', 'kubla_khan.png'],
                'A Dream Within a Dream' => ['Edgar Allan Poe', 'a_dream_within_a_dream.png'],
                'The Dream of a Ridiculous Man' => ['Fyodor Dostoevsky', 'dostoevsky_dream.png'],
                'Chuang Tzus Butterfly Dream' => ['Chuang Tzu (Zhuangzi)', 'chuang_tzu_butterfly.png'],
                'The Stone That Sang' => ['Kaelin Mora', 'the_stone_that_sang.png'],
                'The Dreamkeepers Feather' => ['Niran Solis', 'the_dreamkeepers_feather.png'],
                'The Forgotten Window' => ['Elara S. Wynn', 'the_forgotten_window.png'],
                'The Silence Between Stars' => ['Lysander Vale', 'the_silence_between_stars.png']
              ];

              $imgPath = null;
              foreach($imgMap as $t => [$a, $f]) {
                if($text->title === $t && $text->author === $a) $imgPath = $f;
              }
            @endphp

            @if($imgPath)
              <img
                @click="modalImage = '{{ asset('images/' . $imgPath) }}'; modalOpen = true"
                src="{{ asset('images/' . $imgPath) }}"
                alt="{{ $text->title }} by {{ $text->author }}"
                class="cursor-zoom-in w-full aspect-[3/2] object-cover rounded-xl mb-4 shadow-md border border-purple-800/30">
            @else
              <div class="w-full h-40 bg-gradient-to-br from-purple-800 to-black rounded-md mb-4 flex items-center justify-center text-3xl text-fuchsia-300 font-bold opacity-20">
                {{ strtoupper(substr($text->title, 0, 1)) }}
              </div>
            @endif

            <h3 class="text-2xl font-bold text-purple-200 group-hover:text-purple-300">{{ $text->title }}</h3>
            <p class="text-base italic text-gray-400 mb-2">by {{ $text->author }}</p>
            <p class="text-sm text-gray-500 mb-4">Est. Reading Time: ~2 min</p>

            <div class="flex justify-between items-center">
              <span class="text-xs font-semibold px-3 py-1 rounded-full bg-fuchsia-700 text-white uppercase tracking-wider">
                {{ strtoupper($text->type) }}
              </span>
              <a href="{{ route('library.show', $text->id) }}"
                 class="text-fuchsia-300 text-sm hover:underline">
                Read More →
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  @endforeach

  <!-- Modal Viewer -->
  <div
    x-show="modalOpen"
    x-transition
    @click.self="modalOpen = false"
    class="fixed inset-0 z-50 bg-black/80 backdrop-blur flex items-center justify-center px-4"
    style="display: none;"
  >
    <img :src="modalImage" class="max-w-full max-h-[90vh] rounded-lg border border-purple-800 shadow-xl">
  </div>

</body>
</html>
