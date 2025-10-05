<!DOCTYPE html>
<html lang="en">
<!-- Vanta.js & Three.js for 3D Background -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>🌙 Shared Dreams | DreamWeaver</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="//unpkg.com/alpinejs" defer></script>
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
      once: false, // animate on every scroll into view
      mirror: true, // re-animate on scroll up
      duration: 1000,
      offset: 120,
      easing: 'ease-in-out',
      anchorPlacement: 'top-bottom'
    });

    // Optional: in case content loads late or gets missed
    setTimeout(() => AOS.refreshHard(), 1000);
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
<body class="text-white font-sans min-h-screen" x-data x-init="initVanta()">
<div id="vanta-bg" class="fixed inset-0 -z-10"></div>
<!-- Notification Bell Button -->
<!-- 🔔 Notification Bell Top Right -->
<!-- 🔔 Glowing Notification Bell Top Right -->
<a href="{{ route('notifications.index') }}"
   class="fixed top-4 right-6 z-50 text-white text-3xl transition duration-200 ease-in-out animate-pulse hover:text-yellow-400 shadow-lg"
   style="text-shadow: 0 0 10px #a855f7, 0 0 20px #a855f7;">
   <span class="relative inline-block">
       🔔
       @if(auth()->check() && auth()->user()->unreadNotifications->count())
       <span class="absolute -top-1 -right-1 text-xs px-1.5 bg-red-600 text-white rounded-full shadow">
         {{ auth()->user()->unreadNotifications->count() }}
       </span>
       @endif
   </span>
</a>



<div class="relative px-4 py-10">


  <!-- Back Button -->
  <a href="{{ url('/welcome') }}"
   class="fixed top-5 left-5 z-10 px-5 py-2 rounded-xl bg-black/80 backdrop-blur border border-white/10 text-white font-semibold hover:bg-white/10 hover:scale-105 transition-all shadow-md flex items-center gap-2">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
  </svg>
  Back
</a>


  <!-- Heading -->
  <div class="text-center mb-12" data-aos="fade-down">
    <h1 class="text-5xl font-bold text-violet-400 mb-2 flex justify-center items-center gap-2">
      <span class="text-4xl">🪐</span> Explore Shared Dreams
    </h1>
    <p class="text-slate-200 text-sm md:text-base">Discover how others have wandered through their subconscious.</p>

  </div>

  <!-- Feed Section -->
  <div class="space-y-10 max-w-3xl mx-auto">
    @forelse ($dreams as $dream)
    @php
      $userLiked = $dream->likes->contains('user_id', auth()->id());
    @endphp
<div
  class="bg-black/80 border border-gray-500/20 backdrop-blur-md rounded-3xl p-8 text-[1.05rem] shadow-md transition duration-300 hover:shadow-[0_0_60px_12px_rgba(147,51,234,0.5)] hover:scale-[1.015]"
  data-aos="fade-up"
  x-data="{ expanded: false, showComments: false }">

      
      <!-- User Info -->
      <div class="flex items-center gap-4 mb-4">
        <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center font-bold text-lg shadow-inner border border-white/10">
  {{ strtoupper(substr($dream->user->name, 0, 1)) }}
</div>

        <div>
          <p class="font-semibold text-white text-base">{{ $dream->user->name }}</p>
          <p class="text-xs text-gray-400">Shared • {{ $dream->created_at->diffForHumans() }}</p>
        </div>
      </div>

      <!-- Title -->
      <h2 class="text-2xl font-semibold text-sky-300 mb-3">{{ $dream->title }}</h2>



      <!-- Content with Expand Option -->
      <p class="text-gray-300 text-[1.1rem] leading-relaxed mb-3" x-show="expanded">


        {{ $dream->content }}
      </p>
      <p class="text-gray-300 text-[1.05rem] leading-relaxed mb-3" x-show="!expanded">

        {{ Str::limit($dream->content, 300, '...') }}
      </p>
      <button @click="expanded = !expanded" class="text-sm font-medium text-violet-400 hover:underline transition">

        <span x-show="!expanded">Read more</span>
        <span x-show="expanded">Show less</span>
      </button>

      <!-- Interaction Buttons -->
      <div class="flex items-center gap-6 mt-5 text-sm">
        <button class="like-btn transition {{ $userLiked ? 'text-fuchsia-500' : 'text-gray-400' }}" data-id="{{ $dream->id }}">
          💜 <span class="like-count">{{ $dream->likes->count() }}</span> Like
        </button>
        <button onclick="showLikes({{ $dream->id }})" class="text-sm text-gray-400 hover:text-fuchsia-400">🧑 View Likers</button>
        <button @click="showComments = !showComments" class="text-gray-400 hover:text-fuchsia-400 transition">💬 Comment</button>
        <button onclick="copyLink({{ $dream->id }})" class="text-gray-400 hover:text-fuchsia-400 transition">🔗 Share</button>

        {{-- Message button beside Like/Comment/Share (hidden on own posts) --}}
        @unless($dream->user_id === auth()->id())
          <a href="{{ route('chat.open', $dream->user_id) }}"
             class="inline-flex items-center text-sm hover:underline">
             💬 Message
          </a>
        @endunless
      </div>

      <!-- Comment Form + List -->
      <div x-show="showComments" class="mt-6 border-t border-gray-700 pt-4 space-y-4">
        <!-- Comment Form -->
        <form class="comment-form" data-id="{{ $dream->id }}">
  <input type="text" class="comment-input w-full px-4 py-2 bg-black/40 backdrop-blur  text-white text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-600" placeholder="Write a comment...">
  <button type="submit" class="hidden"></button> <!-- ✅ This is what you're missing -->
</form>


        <!-- Comment List -->
        <div class="comment-list mt-3">
          @foreach ($dream->comments as $comment)
<div class="flex items-start gap-3 text-sm text-gray-300 mt-2" data-aos="fade-up">
  <div class="w-8 h-8 bg-fuchsia-600/70 rounded-full flex items-center justify-center font-bold text-white">
    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
  </div>
  <div>
    <p><span class="font-semibold text-white">{{ $comment->user->name }}:</span> {{ $comment->content }}</p>
    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>

    <!-- ✅ Add this block inside here -->
    @if ($comment->user_id === auth()->id())
      <div class="flex gap-2 text-xs mt-1">
        <button onclick="editComment({{ $comment->id }}, `{{ addslashes($comment->content) }}`)" class="text-yellow-400 hover:underline">Edit</button>
        <button onclick="deleteComment({{ $comment->id }}, this)" class="text-red-400 hover:underline">Delete</button>
      </div>
    @endif
  </div>
</div>
@endforeach

        </div>
      </div>

    </div>
    @empty
      <p class="text-center text-gray-400 mt-20">No dreams have been shared yet.</p>
    @endforelse
  </div>

<!-- Like & Comment + Share Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', async () => {
      const dreamId = button.dataset.id;
      const res = await fetch(`/dreams/${dreamId}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
      });

      const data = await res.json();
      button.querySelector('.like-count').innerText = data.likes;
      button.classList.toggle('text-fuchsia-500');
      button.classList.toggle('text-gray-400');
    });
  });

  document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const dreamId = form.dataset.id;
  const input = form.querySelector('.comment-input');

  // FIXED: Find correct comment list
  const commentList = form.parentElement.querySelector('.comment-list');

  const res = await fetch(`/dreams/${dreamId}/comment`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ content: input.value })
  });

  const data = await res.json();
  const html = `<div class="flex items-start gap-3 text-sm text-gray-300 mt-2">
    <div class="w-8 h-8 bg-fuchsia-600/70 rounded-full flex items-center justify-center font-bold text-white">${data.user[0]}</div>
    <div><p><span class="font-semibold text-white">${data.user}:</span> ${data.content}</p><p class="text-xs text-gray-500">${data.time}</p></div>
  </div>`;
  commentList.innerHTML += html;
  input.value = '';
    
      AOS.refresh(); // Animate new comment
    });
  });
});

// ✅ Copy link to clipboard
function copyLink(dreamId) {
  const url = `${window.location.origin}/dreams/${dreamId}`;
  navigator.clipboard.writeText(url).then(() => {
    alert("🔗 Link copied to clipboard!");
  });
}

// ✅ Show who liked a dream
function showLikes(dreamId) {
  fetch(`/dreams/${dreamId}/likes`)
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById('like-user-list');
      list.innerHTML = '';
      if (data.users.length === 0) {
        list.innerHTML = '<li>No likes yet.</li>';
      } else {
        data.users.forEach(name => {
          const li = document.createElement('li');
          li.textContent = name;
          list.appendChild(li);
        });
      }
      document.getElementById('like-modal').classList.remove('hidden');
    });
}

function hideLikes() {
  document.getElementById('like-modal').classList.add('hidden');
}
</script>

<script>
function editComment(id, oldContent) {
  const newContent = prompt("Edit your comment:", oldContent);
  if (newContent && newContent.trim() !== '') {
    fetch(`/comments/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ content: newContent })
    }).then(() => location.reload());
  }
}

function deleteComment(id, el) {
  if (confirm("Delete this comment?")) {
    fetch(`/comments/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    }).then(() => el.closest('.flex').remove());
  }
}
</script>


<!-- Modal to show who liked the dream -->
<div id="like-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden justify-center items-center">
  <div class="bg-black/60 backdrop-blur-md p-6 rounded-xl text-white max-w-sm w-full shadow-lg relative">
    <button onclick="hideLikes()" class="absolute top-2 right-3 text-gray-400 hover:text-white text-xl">✖</button>
    <h3 class="text-lg font-semibold mb-4">Liked by</h3>
    <ul id="like-user-list" class="space-y-2 text-sm text-gray-300"></ul>
  </>
</div>
<script>
function initVanta() {
  if (window.VANTA) {
    VANTA.FOG({
      el: "#vanta-bg",
      mouseControls: true,
      touchControls: true,
      minHeight: 200.00,
      minWidth: 200.00,
      highlightColor: 0xffffff,   // white
      midtoneColor: 0xcccccc,     // light gray
      lowlightColor: 0x999999,    // slightly darker gray
      baseColor: 0x000000,        // keep base black for depth
      blurFactor: 0.5,
      speed: 2.00,
      zoom: 1.1
    });
  }
}
</script>

</body>
</html>
