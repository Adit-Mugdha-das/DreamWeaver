<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Edit Profile | DreamWeaver</title>
  @vite('resources/css/app.css')

  <style>
    :root {
      --bg-1: #0a0e27;
      --bg-2: #1a1f3a;
      --bg-3: #2a2f4a;
      --glass: rgba(255, 255, 255, 0.05);
      --glass-hover: rgba(255, 255, 255, 0.08);
      --glass-border: rgba(255, 255, 255, 0.1);
      --glass-border-hover: rgba(255, 255, 255, 0.15);
      --muted: #a0aec0;
      --text: #f7fafc;
      --brand-1: #8b5cf6;
      --brand-2: #a78bfa;
      --brand-3: #c4b5fd;
      --focus-glow: rgba(139, 92, 246, 0.3);
      --shadow-glass: 0 8px 32px rgba(0, 0, 0, 0.37);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    html, body { height: 100%; overflow-x: hidden; }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      color: var(--text);
      background:
        radial-gradient(ellipse 1600px 1000px at 15% 10%, rgba(139, 92, 246, 0.15) 0%, transparent 55%),
        radial-gradient(ellipse 1400px 900px at 85% 90%, rgba(96, 165, 250, 0.12) 0%, transparent 55%),
        radial-gradient(circle 800px at 50% 50%, rgba(168, 85, 247, 0.08) 0%, transparent 60%),
        linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-3) 100%);
      background-attachment: fixed;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      position: relative;
    }

    /* Animated Glass Orbs */
    body::before,
    body::after {
      content: '';
      position: fixed;
      border-radius: 50%;
      filter: blur(100px);
      opacity: 0.25;
      animation: float 25s infinite ease-in-out;
      z-index: 0;
      pointer-events: none;
    }
    body::before { width: 700px; height: 700px; background: radial-gradient(circle, rgba(139, 92, 246, 0.5), transparent 70%); top: -250px; right: -150px; animation-delay: -7s; }
    body::after  { width: 600px; height: 600px; background: radial-gradient(circle, rgba(96, 165, 250, 0.4), transparent 70%); bottom: -200px; left: -120px; animation-delay: -14s; }
    @keyframes float { 0%,100%{transform:translate(0,0) scale(1) rotate(0)}25%{transform:translate(60px,-60px) scale(1.05) rotate(90deg)}50%{transform:translate(-40px,40px) scale(.95) rotate(180deg)}75%{transform:translate(40px,60px) scale(1.02) rotate(270deg)} }

    .wrap { min-height: 100vh; position: relative; z-index: 1; display: grid; grid-template-rows: auto 1fr; }

    .header { padding: 48px 20px 24px; display: grid; justify-items: center; }

    .page-title {
      font-size: clamp(36px, 5vw, 56px);
      font-weight: 700;
      letter-spacing: -0.04em;
      background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 30%, var(--brand-3) 60%, var(--brand-2) 100%);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      margin: 0 0 14px; text-align: center;
      text-shadow: 0 0 40px rgba(139, 92, 246, 0.3);
    }
    .subtitle { text-align: center; color: var(--muted); font-size: 1.05rem; margin: 0 20px 0; max-width: 720px; line-height: 1.7; }

    /* Center the card perfectly */
    .container { display: grid; place-items: center; padding: 28px 20px 60px; }

    .card {
      width: min(950px, 100%);
      background: var(--glass);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      box-shadow: var(--shadow-glass);
      backdrop-filter: blur(24px) saturate(180%);
      padding: clamp(28px, 4vw, 48px);
      transition: transform .4s ease, box-shadow .4s ease, border-color .4s ease;
    }
    .card:hover { transform: translateY(-4px); box-shadow: 0 16px 48px rgba(0,0,0,.45); border-color: var(--glass-border-hover); }

    .section + .section { margin-top: 40px; padding-top: 40px; border-top: 1px solid var(--glass-border); }

    .section-title { font-size: 1.35rem; font-weight: 600; color: var(--brand-3); margin: 0 0 24px; letter-spacing: -0.02em; display: flex; align-items: center; gap: 12px; }
    .section-title::before { content: ''; width: 4px; height: 28px; background: linear-gradient(180deg, var(--brand-1), var(--brand-2)); border-radius: 2px; box-shadow: 0 0 12px rgba(139,92,246,.5); }

    .grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
    @media (min-width: 720px) { .grid.two { grid-template-columns: repeat(2, minmax(0, 1fr)); } }

    .field { display: grid; gap: 10px; }
    .label { font-size: .92rem; color: #cbd5e1; font-weight: 600; letter-spacing: .01em; }

    .input, .textarea {
      width: 100%; padding: 14px 16px; border-radius: 14px;
      border: 1px solid var(--glass-border);
      background: rgba(255,255,255,.03); backdrop-filter: blur(10px);
      color: var(--text); font-size: .95rem; transition: all .3s ease;
    }
    .textarea { min-height: 120px; resize: vertical; font-family: inherit; }
    .input:focus, .textarea:focus { outline: none; border-color: var(--brand-2); background: rgba(255,255,255,.05); box-shadow: 0 0 0 4px var(--focus-glow), 0 0 20px rgba(139,92,246,.2); }
    .input:disabled { opacity: .6; cursor: not-allowed; }

    .help { color: var(--muted); font-size: .825rem; font-style: italic; }
    .error { color: #fca5a5; font-size: .825rem; font-weight: 500; }

    .toolbar { display: grid; grid-template-columns: 1fr; gap: 14px; margin-top: 24px; }
    @media (min-width: 640px) { .toolbar { grid-template-columns: 1fr 1fr; } }

    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; height: 48px; padding: 0 24px; border-radius: 14px; font-weight: 600; font-size: .95rem; border: 1px solid var(--glass-border); background: rgba(255,255,255,.06); backdrop-filter: blur(12px); color: var(--text); cursor: pointer; transition: all .3s ease; user-select: none; text-decoration: none; }
    .btn:hover { background: rgba(255,255,255,.1); border-color: var(--glass-border-hover); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.3); }
    .btn:active { transform: translateY(0); }
    .btn:focus-visible { outline: none; box-shadow: 0 0 0 4px var(--focus-glow); }

    .btn-primary { background: rgba(139,92,246,.15); border-color: rgba(139,92,246,.3); color: var(--brand-3); }
    .btn-primary:hover { background: rgba(139,92,246,.25); border-color: rgba(139,92,246,.5); box-shadow: 0 0 24px rgba(139,92,246,.4); }

    .btn-muted { background: rgba(255,255,255,.04); border-color: var(--glass-border); color: #cbd5e1; }
    .btn-muted:hover { background: rgba(255,255,255,.08); border-color: var(--glass-border-hover); }

    .btn-danger { background: rgba(248,113,113,.12); border-color: rgba(248,113,113,.3); color: #fca5a5; }
    .btn-danger:hover { background: rgba(248,113,113,.2); border-color: rgba(248,113,113,.5); box-shadow: 0 0 20px rgba(248,113,113,.3); }

    .alert { margin: 0 0 20px; padding: 14px 18px; border-radius: 14px; font-size: .92rem; border: 1px solid transparent; backdrop-filter: blur(12px); }
    .alert-success { background: rgba(16,185,129,.12); border-color: rgba(16,185,129,.3); color: #6ee7b7; }

    .divider { height: 1px; background: var(--glass-border); margin: 32px 0; border: 0; opacity: .6; }

    .back { position: fixed; left: 20px; top: 20px; z-index: 20; display: inline-flex; align-items: center; gap: 10px; height: 44px; padding: 0 18px; color: var(--text); text-decoration: none; font-weight: 600; border: 1px solid var(--glass-border); border-radius: 14px; background: rgba(15,23,42,.4); backdrop-filter: blur(16px); transition: all .3s ease; }
    .back:hover { transform: translateX(-4px); background: rgba(15,23,42,.6); border-color: var(--glass-border-hover); box-shadow: 0 8px 20px rgba(0,0,0,.3); }

    .avatar-block { display: grid; place-items: center; text-align: center; padding: 20px 0 12px; border-bottom: 1px solid var(--glass-border); margin-bottom: 24px; }
    .avatar { width: 156px; height: 156px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(139,92,246,.4); box-shadow: 0 0 0 1px rgba(255,255,255,.1), 0 16px 40px rgba(139,92,246,.3); transition: transform .3s ease, box-shadow .3s ease; }
    .avatar:hover { transform: scale(1.05); box-shadow: 0 0 0 1px rgba(255,255,255,.2), 0 20px 50px rgba(139,92,246,.5); }
    .avatar-fallback { width: 156px; height: 156px; border-radius: 50%; display: grid; place-items: center; background: linear-gradient(135deg, rgba(139,92,246,.6), rgba(168,85,247,.6)); backdrop-filter: blur(10px); color: #fff; font-size: 3.5rem; font-weight: 800; border: 4px solid rgba(139,92,246,.4); box-shadow: 0 0 0 1px rgba(255,255,255,.1), 0 16px 40px rgba(139,92,246,.3); }

    .upload { display: grid; gap: 12px; margin: 16px 0 12px; }
    .file-label { display: inline-flex; align-items: center; justify-content: center; gap: 10px; height: 44px; padding: 0 20px; border-radius: 12px; border: 1px solid rgba(139,92,246,.3); background: rgba(139,92,246,.12); backdrop-filter: blur(12px); color: var(--brand-3); font-weight: 600; cursor: pointer; transition: all .3s ease; }
    .file-label:hover { background: rgba(139,92,246,.2); border-color: rgba(139,92,246,.5); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(139,92,246,.3); }

    .file-input { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
    .file-note { font-size: .8rem; color: var(--muted); font-style: italic; }

    .char { text-align: right; font-size: .8rem; color: var(--muted); font-weight: 500; }

    @media (prefers-reduced-motion: reduce) {
      .btn, .back, .avatar { transition: none; }
      body::before, body::after { animation: none; }
    }
  </style>
</head>
<body>
  <a href="{{ route('dreams.shared') }}" class="back" aria-label="Back to Shared Realm">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
    <span>Back to Shared Realm</span>
  </a>

  <div class="wrap">
    <header class="header">
      <div>
        <h1 class="page-title">Edit Your Profile</h1>
        <p class="subtitle">Update your public information for the DreamWeaver community.</p>
      </div>
    </header>

    <main class="container">
      <section class="card" aria-labelledby="profile-form-title">
        @if(session('success'))
          <div class="alert alert-success" role="status" aria-live="polite">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm" novalidate>
          @csrf
          @method('PUT')

          <div class="avatar-block">
            @if($user->profile_picture)
              <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile picture" class="avatar" id="profilePicPreview" />
            @else
              <div class="avatar-fallback" aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif

            <div class="upload">
              <label for="profile_picture" class="file-label">Choose Picture</label>
              <input type="file" class="file-input" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(this)" />
              <span class="file-note">JPG, PNG, or WebP. Max 5 MB.</span>
              @if($user->profile_picture)
                <button type="button" class="btn btn-danger" onclick="deleteProfilePicture()">Delete Picture</button>
              @endif
            </div>
          </div>

          <div class="section" id="profile-form">
            <h2 class="section-title" id="profile-form-title">Basic Information</h2>
            <div class="grid two">
              <div class="field">
                <label for="name" class="label">Display Name <span aria-hidden="true">*</span></label>
                <input type="text" class="input" name="name" id="name" value="{{ old('name', $user->name) }}" required aria-required="true" autocomplete="name" />
                @error('name')
                  <p class="error">{{ $message }}</p>
                @enderror
              </div>
              <div class="field">
                <label for="location" class="label">Location</label>
                <input type="text" class="input" name="location" id="location" value="{{ old('location', $user->location) }}" placeholder="e.g., Dhaka, Bangladesh" autocomplete="off" />
                @error('location')
                  <p class="error">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="field">
              <label for="bio" class="label">Bio</label>
              <textarea class="textarea" name="bio" id="bio" maxlength="500" placeholder="A short description about you." oninput="updateCharCount()">{{ old('bio', $user->bio) }}</textarea>
              <div class="char" id="charCount">{{ strlen($user->bio ?? '') }}/500</div>
              @error('bio')
                <p class="error">{{ $message }}</p>
              @enderror
            </div>

            <div class="field">
              <label for="website" class="label">Website</label>
              <input type="url" class="input" name="website" id="website" value="{{ old('website', $user->website) }}" placeholder="https://yourwebsite.com" inputmode="url" />
              @error('website')
                <p class="error">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <hr class="divider" />

          <div class="section">
            <h2 class="section-title">Contact</h2>
            <div class="grid two">
              <div class="field">
                <label class="label">Login Email</label>
                <input type="email" class="input" value="{{ $user->email }}" disabled aria-disabled="true" />
                <p class="help">Your @dream.com login email cannot be changed.</p>
              </div>
              <div class="field">
                <label for="recovery_email" class="label">Recovery Email</label>
                <input type="email" class="input" name="recovery_email" id="recovery_email" value="{{ old('recovery_email', $user->recovery_email) }}" placeholder="your.email@gmail.com" inputmode="email" autocomplete="email" />
                @error('recovery_email')
                  <p class="error">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <div class="toolbar">
            <button type="submit" class="btn btn-primary">Save Profile Changes</button>
            <a href="{{ route('dreams.shared') }}" class="btn btn-muted" role="button">Cancel</a>
          </div>
        </form>

        <hr class="divider" />

        <form method="POST" action="{{ route('profile.password.update') }}" novalidate>
          @csrf
          @method('PUT')

          <div class="section">
            <h2 class="section-title">Change Password</h2>
            <div class="grid two">
              <div class="field">
                <label for="current_password" class="label">Current Password</label>
                <input type="password" class="input" name="current_password" id="current_password" autocomplete="current-password" required />
                @error('current_password')
                  <p class="error">{{ $message }}</p>
                @enderror
              </div>
              <div class="field">
                <label for="password" class="label">New Password</label>
                <input type="password" class="input" name="password" id="password" autocomplete="new-password" required />
                @error('password')
                  <p class="error">{{ $message }}</p>
                @enderror
              </div>
            </div>
            <div class="field">
              <label for="password_confirmation" class="label">Confirm New Password</label>
              <input type="password" class="input" name="password_confirmation" id="password_confirmation" autocomplete="new-password" required />
            </div>

            <div class="toolbar" style="grid-template-columns: 1fr;">
              <button type="submit" class="btn btn-muted">Update Password</button>
            </div>
          </div>
        </form>
      </section>
    </main>
  </div>

  <script>
    function previewImage(input) {
      if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 5 * 1024 * 1024) {
          alert('Please choose an image smaller than 5 MB.');
          input.value = '';
          return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
          const preview = document.getElementById('profilePicPreview');
          if (preview) {
            preview.src = e.target.result;
          } else {
            const section = document.querySelector('.avatar-block');
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'avatar';
            img.id = 'profilePicPreview';
            const fallback = section.querySelector('.avatar-fallback');
            if (fallback) fallback.replaceWith(img);
            else section.prepend(img);
          }
        };
        reader.readAsDataURL(file);
      }
    }

    async function deleteProfilePicture() {
      if (!confirm('Delete your profile picture?')) return;
      try {
        const response = await fetch('{{ route("profile.picture.delete") }}', {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          }
        });
        const data = await response.json();
        if (data.success) {
          location.reload();
        } else {
          alert('Failed to delete profile picture.');
        }
      } catch (err) {
        console.error(err);
        alert('An error occurred while deleting the picture.');
      }
    }

    function updateCharCount() {
      const bio = document.getElementById('bio');
      const charCount = document.getElementById('charCount');
      if (!bio || !charCount) return;
      charCount.textContent = `${bio.value.length}/500`;
    }
  </script>
</body>
</html>
