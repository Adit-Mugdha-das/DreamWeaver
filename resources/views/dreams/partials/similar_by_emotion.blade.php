@php use Illuminate\Support\Str; @endphp
<div class="space-y-3">
  <h3 class="text-lg font-semibold">
    Dreams with emotion: <span class="uppercase">{{ $emotion }}</span>
  </h3>

  @if($dreams->isEmpty())
    <p class="text-sm opacity-70">No dreams found with this emotion.</p>
  @else
    <ul class="space-y-2">
      @foreach($dreams as $d)
        <li class="p-3 rounded-lg bg-white/5 border border-white/10">
          <div class="text-sm font-medium">{{ $d->title }}</div>
          <div class="text-xs opacity-70">{{ optional($d->created_at)->format('d M, Y') }}</div>

          {{-- Keep the quick summary if you like --}}
          @if(!empty($d->emotion_summary))
            <div class="text-xs mt-1 opacity-80">{{ Str::limit($d->emotion_summary, 140) }}</div>
          @endif

          {{-- NEW: Let the user see the original message they sent to analyze --}}
          @if(!empty($d->content))
            <details class="mt-2">
              <summary class="text-sm underline cursor-pointer">Show original message</summary>
              <div class="mt-2 text-sm leading-relaxed">
                {!! nl2br(e($d->content)) !!}
              </div>
            </details>
          @else
            <div class="mt-2 text-xs opacity-60 italic">No original message stored.</div>
          @endif
        </li>
      @endforeach
    </ul>
  @endif
</div>
