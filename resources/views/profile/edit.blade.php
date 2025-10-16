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
      --bg-1: #0f172a;
      --bg-2: #1e1b4b;
      --bg-3: #312e81;
      --card: rgba(255, 255, 255, 0.03);
      --card-hover: rgba(255, 255, 255, 0.05);
      --border: rgba(255, 255, 255, 0.06);
      --muted: #94a3b8;
      --text: #f1f5f9;
      --brand-1: #8b5cf6;
      --brand-2: #a78bfa;
      --success: #10b981;
      --danger: #ef4444;
      --focus-ring: rgba(139, 92, 246, 0.4);
      --shadow-sm: 0 4px 20px rgba(0, 0, 0, 0.15);
      --shadow-lg: 0 25px 60px rgba(0, 0, 0, 0.4);
    }

    * { 
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html, body { 
      height: 100%; 
      overflow-x: hidden;
    }

    body {
      font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
      color: var(--text);
      background: 
        radial-gradient(ellipse 1400px 900px at 20% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
        radial-gradient(ellipse 1200px 800px at 80% 100%, rgba(168, 85, 247, 0.12) 0%, transparent 50%),
        radial-gradient(circle 600px at 50% 50%, rgba(56, 189, 248, 0.08) 0%, transparent 50%),
        linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-3) 100%);
      background-attachment: fixed;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      position: relative;
    }

    /* 3D Floating Shapes Background */
    body::before,
    body::after {
      content: '';
      position: fixed;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.3;
      animation: float 20s infinite ease-in-out;
      z-index: 0;
    }

    body::before {
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(139, 92, 246, 0.4), transparent 70%);
      top: -200px;
      right: -100px;
      animation-delay: -5s;
    }

    body::after {
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.3), transparent 70%);
      bottom: -150px;
      left: -100px;
      animation-delay: -10s;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0) scale(1); }
      33% { transform: translate(50px, -50px) scale(1.1); }
      66% { transform: translate(-30px, 30px) scale(0.9); }
    }

    .wrap {
      min-height: 100vh;
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
    }

    .header {
      padding: 40px 20px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .page-title {
      font-size: clamp(32px, 4vw, 48px);
      font-weight: 700;
      letter-spacing: -0.03em;
      background: linear-gradient(135deg, #f1f5f9 0%, #c7d2fe 40%, var(--brand-2) 80%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin: 0 0 12px;
      text-align: center;
    }

    .subtitle {
      text-align: center;
      color: var(--muted);
      font-size: 1rem;
      font-weight: 400;
      margin: 0 20px 30px;
      max-width: 600px;
      line-height: 1.6;
    }

    .container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 20px 16px 80px;
    }

    .card {
      width: min(900px, 100%);
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      box-shadow: var(--shadow-lg);
      backdrop-filter: saturate(150%) blur(20px);
      padding: clamp(24px, 3vw, 40px);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
    }

    .section + .section { 
      margin-top: 36px;
      padding-top: 36px;
      border-top: 1px solid var(--border);
    }

    .section-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--brand-2);
      margin: 0 0 20px;
      letter-spacing: -0.01em;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title::before {
      content: '';
      width: 4px;
      height: 24px;
      background: linear-gradient(180deg, var(--brand-1), var(--brand-2));
      border-radius: 2px;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
    }

    @media (min-width: 720px) {
      .grid.two { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    .field { display: grid; gap: 8px; }

    .label {
      font-size: 0.9rem;
      color: #cbd5e1;
      font-weight: 600;
    }

    .input, .textarea {
      width: 100%;
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: rgba(17, 24, 39, 0.55);
      color: var(--text);
      transition: border-color 160ms ease, box-shadow 160ms ease, background 180ms ease;
    }

    .textarea { min-height: 112px; resize: vertical; }

    .input:focus, .textarea:focus {
      outline: none;
      border-color: var(--brand-1);
      box-shadow: 0 0 0 4px var(--focus-ring);
      background: rgba(17, 24, 39, 0.7);
    }

    .help { color: var(--muted); font-size: 12px; }

    .error { color: #fda4af; font-size: 12px; }

    .toolbar {
      display: grid;
      grid-template-columns: 1fr;
      gap: 12px;
      margin-top: 18px;
    }
    @media (min-width: 640px) {
      .toolbar { grid-template-columns: 1fr 1fr; }
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      height: 44px;
      padding: 0 18px;
      border-radius: 12px;
      font-weight: 600;
      border: 1px solid transparent;
      cursor: pointer;
      transition: transform 120ms ease, box-shadow 160ms ease, background 160ms ease, border-color 160ms ease;
      user-select: none;
    }
    .btn:focus-visible { outline: none; box-shadow: 0 0 0 4px var(--focus-ring); }

    .btn-primary {
      background: linear-gradient(180deg, #6d28d9, #7c3aed);
      border-color: rgba(124, 58, 237, 0.25);
      color: #fff;
    }
    .btn-primary:hover { transform: translateY(-1px); }

    .btn-muted {
      background: rgba(255, 255, 255, 0.06);
      border-color: var(--border);
      color: #e5e7eb;
    }
    .btn-muted:hover { background: rgba(255, 255, 255, 0.08); }

    .btn-danger {
      background: rgba(239, 68, 68, 0.9);
      border-color: rgba(239, 68, 68, 0.25);
      color: #fff;
    }
    .btn-danger:hover { filter: brightness(1.04); }

    .alert {
      margin: 0 0 16px;
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 0.92rem;
      border: 1px solid transparent;
    }
    .alert-success { background: rgba(34, 197, 94, 0.16); border-color: rgba(34, 197, 94, 0.35); color: #bbf7d0; }

    .divider { height: 1px; background: var(--border); margin: 26px 0; border: 0; }

    /* Back link */
    .back {
      position: fixed; left: 18px; top: 18px; z-index: 20;
      display: inline-flex; align-items: center; gap: 10px;
      height: 40px; padding: 0 14px;
      color: #e5e7eb; text-decoration: none; font-weight: 600;
      border: 1px solid var(--border); border-radius: 12px;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(6px);
      transition: transform 120ms ease, background 160ms ease, border-color 160ms ease;
    }
    .back:hover { transform: translateX(-2px); background: rgba(15, 23, 42, 0.75); }

    .avatar-block {
      display: grid; place-items: center; text-align: center;
      padding: 16px 0 8px; border-bottom: 1px solid var(--border);
      margin-bottom: 18px;
    }

    .avatar {
      width: 144px; height: 144px; border-radius: 50%; object-fit: cover;
      border: 4px solid rgba(124, 58, 237, 0.45);
      box-shadow: 0 12px 32px rgba(124, 58, 237, 0.28);
    }
    .avatar-fallback {
      width: 144px; height: 144px; border-radius: 50%;
      display: grid; place-items: center;
      background: linear-gradient(180deg, #6d28d9, #7c3aed);
      color: #fff; font-size: 3rem; font-weight: 800;
      border: 4px solid rgba(124, 58, 237, 0.45);
      box-shadow: 0 12px 32px rgba(124, 58, 237, 0.28);
    }

    .upload { display: grid; gap: 10px; margin: 12px 0 10px; }

    .file-label {
      display: inline-flex; align-items: center; justify-content: center; gap: 10px;
      height: 40px; padding: 0 14px;
      border-radius: 10px; border: 1px solid rgba(124, 58, 237, 0.35);
      background: rgba(124, 58, 237, 0.18); color: #ede9fe; font-weight: 600;
      cursor: pointer; transition: background 160ms ease, transform 120ms ease;
    }
    .file-label:hover { background: rgba(124, 58, 237, 0.26); }
    .file-input { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
    .file-note { font-size: 12px; color: var(--muted); }

    .char { text-align: right; font-size: 12px; color: var(--muted); }

    @media (prefers-reduced-motion: reduce) {
      .btn, .back { transition: none; }
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
