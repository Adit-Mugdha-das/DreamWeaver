<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $text->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS Animate On Scroll -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- Vanta.js Background -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-color: #0a0c1b;
            color: white;
            overflow-x: hidden;
        }

        .fade-line {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInLine 0.6s ease forwards;
        }

        @keyframes fadeInLine {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .vanta-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>

    @php
        $themeColors = [
            'poem' => [
                'highlight' => '#f472b6', 'midtone' => '#a855f7',
                'btn' => 'bg-pink-600 hover:bg-pink-700',
                'gradient' => 'from-pink-400 to-fuchsia-400',
            ],
            'story' => [
                'highlight' => '#34d399', 'midtone' => '#10b981',
                'btn' => 'bg-emerald-600 hover:bg-emerald-700',
                'gradient' => 'from-emerald-400 to-emerald-300',
            ],
            'myth' => [
                'highlight' => '#f59e0b', 'midtone' => '#eab308',
                'btn' => 'bg-amber-500 hover:bg-amber-600',
                'gradient' => 'from-amber-400 to-yellow-300',
            ],
            'echo' => [
                'highlight' => '#38bdf8', 'midtone' => '#0ea5e9',
                'btn' => 'bg-sky-600 hover:bg-sky-700',
                'gradient' => 'from-sky-400 to-cyan-300',
            ],
        ];

        $type = strtolower($text->type);
        $theme = $themeColors[$type] ?? [
            'highlight' => '#f3a9ff', 'midtone' => '#651fff',
            'btn' => 'bg-fuchsia-600 hover:bg-fuchsia-700',
            'gradient' => 'from-fuchsia-400 to-purple-300',
        ];
    @endphp
</head>
<body class="bg-[#0a0c1b] text-white min-h-screen font-sans px-4 md:px-12 py-8">

    <!-- Vanta Background -->
    <div id="vanta-bg" class="vanta-bg"></div>

    <!-- Back Button Top Left -->
    <div class="fixed top-5 left-5 z-50">
        <a href="{{ route('library.index') }}"
           class="text-white text-sm font-semibold px-4 py-2 rounded-md shadow-lg transition-all {{ $theme['btn'] }}">
            ← Back to Library
        </a>
    </div>

    <!-- Download PDF Button Top Right -->
    <div class="fixed top-5 right-5 z-50">
        <a href="{{ route('library.download', $text->id) }}"
           class="text-white text-sm font-semibold px-4 py-2 rounded-md shadow-lg transition-all {{ $theme['btn'] }}">
            ⬇ Download PDF
        </a>
    </div>

    <!-- Content Container -->
    <div class="relative max-w-4xl mx-auto mt-10">
        <div class="absolute inset-0 blur-xl opacity-30 bg-gradient-to-br from-purple-500/30 to-fuchsia-400/20 rounded-xl"></div>

        <div class="relative z-10 bg-black/50 p-6 md:p-10 rounded-xl border border-purple-400/10 shadow-xl backdrop-blur-md" data-aos="fade-up">
            
            <!-- Title & Author with Animation -->
            <div data-aos="fade-down" data-aos-delay="100">
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r {{ $theme['gradient'] }} mb-2">
                    {{ $text->title }}
                </h1>
                <p class="text-sm text-gray-400 mb-6 italic">
                    by {{ $text->author }} ({{ ucfirst($text->type) }})
                </p>
            </div>

            @php
                $lines = preg_split("/\r\n|\n|\r/", trim($text->content));
            @endphp

            <div class="text-lg md:text-xl leading-8 tracking-wide space-y-2 text-white drop-shadow-lg">
                @foreach ($lines as $i => $line)
                    <div class="fade-line" style="animation-delay: {{ 0.1 * $i }}s">{{ $line }}</div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Init AOS & Vanta -->
    <script>
        AOS.init({ once: true, duration: 800 });

        VANTA.FOG({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            minHeight: 200.00,
            minWidth: 200.00,
            highlightColor: "{{ $theme['highlight'] }}",
            midtoneColor: "{{ $theme['midtone'] }}",
            lowlightColor: 0x0a0c1b,
            baseColor: 0x000000,
            blurFactor: 0.6,
            speed: 1.2,
            zoom: 1.0
        });
    </script>

</body>
</html>
