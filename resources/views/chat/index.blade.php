<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Messages</title>
  @vite('resources/css/app.css')
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-slate-100 antialiased">
  <div class="fixed inset-0 -z-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(6,182,212,0.15),transparent_70%)]"></div>
    <div class="absolute inset-0 opacity-[0.06] [mask-image:linear-gradient(to_bottom,black,transparent)]">
      <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg"><defs>
        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
          <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
        </pattern></defs><rect width="100%" height="100%" fill="url(#grid)"/></svg>
    </div>
  </div>

  <main class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Messages</h1>
      <a href="{{ url()->previous() }}" class="text-sm px-3 py-1 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10">Back</a>
    </div>

    @php
      // $convs is a LengthAwarePaginator from ChatController@index
    @endphp

    <div class="space-y-3">
      @forelse($convs as $c)
        @php $other = $c->participants->firstWhere('id', '!=', auth()->id()); @endphp
        <a href="{{ route('chat.open', $other) }}"
           class="block rounded-2xl p-4 bg-slate-900/60 ring-1 ring-white/10 hover:ring-cyan-400/30">
          <div class="font-medium">{{ $other?->name ?? 'Unknown' }}</div>
          <div class="text-sm opacity-75">
            {{ optional($c->messages->first())->body ? Str::limit($c->messages->first()->body, 80) : 'No messages yet' }}
          </div>
        </a>
      @empty
        <p class="opacity-70">No conversations yet.</p>
      @endforelse
    </div>

    <div class="mt-6">
      {{ $convs->links() }}
    </div>
  </main>
</body>
</html>
