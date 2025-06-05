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

  <style>
    .scroll-container {
      display: flex;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
      gap: 1.5rem;
      padding-bottom: 1rem;
    }

    .scroll-item {
      flex: 0 0 auto;
      width: auto;
      scroll-snap-align: start;
    }

    .scroll-container::-webkit-scrollbar {
      display: none;
    }
  </style>
</head>
<body x-data="{ modalOpen: false, modalImage: '' }" @keydown.escape="modalOpen = false"
      class="bg-[#0a0c1b] text-white min-h-screen font-sans px-6 py-10">

  <!-- Home Button -->
  <a href="{{ url('/welcome') }}"
     class="absolute top-6 left-6 text-base px-5 py-2.5 rounded-lg font-semibold text-indigo-300 hover:text-white bg-indigo-900/40 hover:bg-indigo-700/60 border border-indigo-400/20 shadow-md backdrop-blur transition duration-300">
    ← Home
  </a>

  <!-- Title -->
  <h1 class="text-4xl font-extrabold mb-10 text-purple-300 tracking-wider text-center"> Dream Library</h1>

  @php $grouped = $texts->groupBy('type'); @endphp

  @foreach([
    'poem' => 'Poems ✨',
    'story' => 'Stories 📖',
    'myth' => 'Myths 🐉',
    'echo' => 'Echoes 🌀'
  ] as $key => $label)
    @if($grouped->has($key))
      <h2 class="text-2xl font-bold text-fuchsia-300 mt-10 mb-4">{{ $label }}</h2>
      <div class="{{ in_array($key, ['poem', 'story', 'myth', 'echo']) ? 'scroll-container' : 'grid' }}">
        @foreach($grouped[$key] as $index => $text)
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
              'The Silence Between Stars' => ['Lysander Vale', 'the_silence_between_stars.png'],
              'Whispers of the Dreaming Tree' => ['Eira Solwind', 'whispers_dreaming_tree.png'],
              'Echoes Beneath the Moon' => ['Liora Wren', 'echoes_beneath_the_moon.png'],
              'Lanterns of the Sleeping Sea' => ['Aveline Shore', 'lanterns_sleeping_sea.png'],
              'The Last Lightkeeper' => ['Solene Marelle', 'the_last_lightkeeper.png'],
              'When the Clock Forgets to Tick' => ['Ren Arden', 'when_the_clock_forgets_to_tick.png'],
              'The Library Beneath the Lake' => ['Nessa Thorne', 'the_library_beneath_the_lake.png'],
              'Paper Wings and Midnight Wind' => ['Ilya Fenwood', 'paper_wings_and_midnight_wind.png'],
              'The Mirror That Dreamed' => ['Kael Riven', 'the_mirror_that_dreamed.png'],
              'The Clockmakers Visitor' => ['Marin Hollow', 'the_clockmakers_visitor.png'],
              'The Boy Who Bottled Rain' => ['Iven Lark', 'the_boy_who_bottled_rain.png'],
              'The Lantern Below the Lake' => ['Cira Wren', 'the_lantern_below_the_lake.png'],
              'The Staircase in the Field' => ['Nora Ellin', 'the_staircase_in_the_field.png'],
              'The Dream Collector' => ['Orrin Vale', 'the_dream_collector.png'],
              'The Island That Waits' => ['Seren Dusk', 'the_island_that_waits.png'],
              'The Weaver of Skies' => ['Thalen Myrr', 'the_weaver_of_skies.png'],
              'The Curse of La Llorona' => ['Isadora Reyes', 'the_curse_of_la_llorona.png'],
              'The Watcher Beneath the Banyan' => ['Raihan Bose', 'the_watcher_beneath_the_banyan.png'],
              'The Lighthouse That Listens' => ['Selene Arora', 'the_lighthouse_that_listens.png'],
              'The Dream-Eater of Noktara' => ['Aarav Sen', 'the_dream_eater_of_noktara.png'],
              'The Garden of First Dreams' => ['Lina Aster', 'the_garden_of_first_dreams.png'],
              'The Star That Slept Too Long' => ['Ember Kavir', 'the_star_that_slept_too_long.png'],
              'The Voice in the Hollow' => ['Noor Elen', 'the_voice_in_the_hollow.png'],
              'Echoes of the Astral Thread' => ['Seraph Elion', 'echoes_of_the_astral_thread.png'],
              'The Whisper Clock' => ['Mira Solene', 'the_whisper_clock.png'],
              'The Breath Between Pages' => ['Elen Raye', 'the_breath_between_pages.png'],
              'The Room That Remembers Sounds' => ['Cael Merrin', 'the_room_that_remembers_sounds.png'],
              'The Mirror Between Moments' => ['Nyra Lune', 'the_mirror_between_moments.png'],
              'Where the Letters Never Sent Go' => ['Jalen Rhys', 'where_the_letters_never_sent_go.png'],
              'The Star That Hears Your Name' => ['Elira Vane', 'the_star_that_hears_your_name.png'],
              'The Room with No Corners' => ['Arien Dusk', 'the_room_with_no_corners.png'],
              'The Window That Opens Inward' => ['Virel Thorne', 'the_window_that_opens_inward.png'],

              'The Clock That Counted Memories' => ['Orla Mire', 'the_clock_that_counted_memories.png'],




            ];
            $imgPath = null;
            foreach($imgMap as $t => [$a, $f]) {
              if($text->title === $t && $text->author === $a) $imgPath = $f;
            }
          @endphp

          <div class="scroll-item rounded-2xl bg-black/30 border border-fuchsia-400/10 hover:shadow-[0_0_12px_#a78bfa66] transition-all group max-w-[320px]" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
            @if($imgPath)
              <img
                @click="modalImage = '{{ asset('images/' . $imgPath) }}'; modalOpen = true"
                src="{{ asset('images/' . $imgPath) }}"
                alt="{{ $text->title }} by {{ $text->author }}"
                class="w-full object-contain rounded-t-2xl cursor-zoom-in">
            @endif

            <div class="p-4">
              <h3 class="text-[1.4rem] font-bold text-purple-200 group-hover:text-purple-300">{{ $text->title }}</h3>
              <p class="text-base italic text-gray-400 mb-1">by {{ $text->author }}</p>
              @php
                $readingTimeMap = [
                  'poem' => '3 min',
                  'story' => '5 min',
                  'myth'  => '7 min',
                  'echo'  => '4 min',
                ];
                $readingTime = $readingTimeMap[$text->type] ?? '4 min';
              @endphp
              <p class="text-sm text-gray-500 mb-3">Est. Reading Time: ~{{ $readingTime }}</p>


              <div class="flex justify-between items-center">
                <span class="text-sm font-semibold px-3 py-1 rounded-full bg-fuchsia-700 text-white uppercase tracking-wider">
                  {{ strtoupper($text->type) }}
                </span>
                <a href="{{ route('library.show', $text->id) }}"
                   class="text-fuchsia-300 text-sm hover:underline">
                  Read More →
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  @endforeach

  <!-- Modal -->
  <div
    x-show="modalOpen"
    x-transition
    @click.self="modalOpen = false"
    class="fixed inset-0 z-50 bg-black/80 backdrop-blur flex items-center justify-center px-4"
    style="display: none;">
    <img :src="modalImage" class="max-w-full max-h-[90vh] rounded-lg border border-purple-800 shadow-xl">
  </div>

</body>
</html>
