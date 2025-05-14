<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dreamy Night Login</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&family=Playfair+Display:wght@700&display=swap');

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Playfair Display', serif;
      overflow: hidden;
      background-color: #0b0f1a;
      -webkit-font-smoothing: antialiased;
      text-rendering: optimizeLegibility;
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
      width: 90%;
      max-width: 420px;
      padding: 2rem;
      margin: auto;
      top: 50%;
      transform: translateY(-50%);
      border-radius: 1.2rem;
      background: rgba(5, 8, 20, 0.96);
      border: 2px solid #00bcd4;
      box-shadow: 0 0 40px rgba(0, 188, 212, 0.3);
      /* animation removed for font clarity */
    }

    h2 {
      text-align: center;
      font-family: 'UnifrakturCook', cursive;
      font-size: 2.2rem;
      color: #ffe066;
      text-shadow: 2px 2px 10px #000;
      margin-bottom: 1.5rem;
    }

    input, button {
      width: 100%;
      padding: 12px;
      margin-top: 14px;
      font-size: 1rem;
      border-radius: 8px;
      border: 1px solid #4cc9f0;
      background: rgba(0, 0, 0, 0.6);
      color: #fff;
      transition: all 0.3s ease;
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

    button {
      background: linear-gradient(to right, #2196f3, #ffe066);
      color: #000;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 0 15px #ffe06655;
    }

    button:hover {
      transform: scale(1.03);
      box-shadow: 0 0 25px #ffe066cc;
    }

    .register-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
      color: #fbc4ab;
      text-decoration: none;
      transition: 0.3s;
    }

    .register-link:hover {
      color: #ffffff;
      text-shadow: 0 0 10px #ffd6ff;
    }

    .error-message, .success-message {
      text-align: center;
      margin-bottom: 1rem;
      font-size: 0.875rem;
    }

    .error-message {
      color: #ff6b6b;
    }

    .success-message {
      color: #06d6a0;
    }
  </style>
</head>
<body>

  <div id="background"></div>

  <div class="form-box">
    <h2>Dreamy Night Login</h2>

    @if(session('success'))
      <div class="success-message">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="error-message">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <input type="email" name="email" placeholder="Email" required autocomplete="email" value="{{ old('email') }}">
      <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
      <button type="submit">Login</button>
    </form>

    <a href="{{ route('register') }}" class="register-link">Don't have an account? Register</a>
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
      speed: 1.5,
      zoom: 1.1
    })
  </script>
</body>
</html>
