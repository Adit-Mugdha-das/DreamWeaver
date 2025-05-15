<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - DreamWeaver</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&family=Playfair+Display:wght@700&display=swap');

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      font-family: 'Playfair Display', serif;
      background-color: #0b0f1a;
      -webkit-font-smoothing: antialiased;
      text-rendering: optimizeLegibility;
      color: white;
    }

    #background {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .form-box {
      position: relative;
      z-index: 1;
      width: 95%;
      max-width: 500px;
      padding: 3.5rem 2.5rem;
      margin: auto;
      top: 50%;
      transform: translateY(-50%);
      border-radius: 1.2rem;
      background: rgba(18, 24, 42, 0.6);
      backdrop-filter: blur(10px);
      border: 2px solid #4f46e5;
      box-shadow: 0 0 40px rgba(79, 70, 229, 0.35);
      animation: fadeInUp 1s ease-out;
      opacity: 0;
      animation-fill-mode: forwards;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(-40%) scale(0.95); }
      to   { opacity: 1; transform: translateY(-50%) scale(1); }
    }

    h2 {
      text-align: center;
      font-family: 'UnifrakturCook', cursive;
      font-size: 2.2rem;
      color: #b3bcf5;
      text-shadow: 2px 2px 10px #1e1b4b;
      margin-bottom: 1.5rem;
    }

    input {
      display: block;
      width: 100%;
      padding: 12px;
      margin-top: 14px;
      font-size: 1rem;
      border-radius: 8px;
      border: 1px solid #4cc9f0;
      background: rgba(0, 0, 0, 0.6);
      color: #fff;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }

    input::placeholder {
      color: #aaa;
      font-style: italic;
    }

    input:focus {
      outline: none;
      border-color: #4cc9f0;
      box-shadow: 0 0 10px #4cc9f099;
    }

    .strength-bar {
      height: 6px;
      margin-top: 4px;
      background: #444;
      border-radius: 4px;
      overflow: hidden;
    }

    .strength-bar-fill {
      height: 100%;
      width: 0%;
      background: red;
      transition: width 0.3s ease;
    }

    .password-wrapper {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.1rem;
      color: #ccc;
      cursor: pointer;
    }

    button {
      display: block;
      width: 100%;
      padding: 12px;
      margin-top: 14px;
      font-size: 1rem;
      border-radius: 8px;
      border: none;
      background: linear-gradient(to right, #2196f3, #ffe066);
      color: #000;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 0 15px #ffe06655;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }

    button:hover {
      transform: scale(1.03);
      box-shadow: 0 0 25px #ffe066cc;
    }

    .link {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
    }

    .link a {
      color: #fbc4ab;
      text-decoration: none;
    }

    .link a:hover {
      color: #fff;
      text-shadow: 0 0 10px #ffd6ff;
    }

    .error-message, .status-message {
      text-align: center;
      font-size: 0.875rem;
      margin-bottom: 1rem;
    }

    .error-message { color: #ff6b6b; }
    .status-message { color: #06d6a0; }
  </style>
</head>
<body>

<div id="background"></div>

<div class="form-box">
  <h2>Set New Password</h2>

  @if ($errors->any())
    <div class="error-message">{{ $errors->first() }}</div>
  @endif

  @if (session('status'))
    <div class="status-message">{{ session('status') }}</div>
  @endif

  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="email" name="email" placeholder="Your @dream.com email" required value="{{ old('email') }}">

    <div class="password-wrapper">
      <input type="password" name="password" id="password" placeholder="New Password" required>
      <span class="password-toggle" onclick="togglePassword()">👁️</span>
    </div>
    <div class="strength-bar"><div id="strengthFill" class="strength-bar-fill"></div></div>

    <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
    <button type="submit">Reset Password</button>
  </form>

  <div class="link">
    <a href="{{ route('login') }}">← Back to Login</a>
  </div>
</div>

<!-- Vanta Fog Background -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>
<script>
  VANTA.FOG({
    el: "#background",
    mouseControls: true,
    touchControls: true,
    gyroControls: false,
    highlightColor: 0x3f51b5,
    midtoneColor: 0x1a237e,
    lowlightColor: 0x0b0f1a,
    baseColor: 0x000000,
    blurFactor: 0.5,
    speed: 1.2,
    zoom: 1.0
  });

  function togglePassword() {
    const passInput = document.getElementById("password");
    passInput.type = passInput.type === "password" ? "text" : "password";
  }

  document.getElementById("password").addEventListener("input", function () {
    const strengthFill = document.getElementById("strengthFill");
    const val = this.value;
    let strength = 0;
    if (val.length >= 6) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;
    const width = ['0%', '30%', '60%', '90%', '100%'][strength];
    const colors = ['red', 'orange', 'yellow', 'lightgreen', 'green'];
    strengthFill.style.width = width;
    strengthFill.style.background = colors[strength];
  });
</script>

</body>
</html>
