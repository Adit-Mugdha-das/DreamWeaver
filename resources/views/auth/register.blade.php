<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - DreamWeaver</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&family=Playfair+Display:wght@700&display=swap');

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      font-family: 'Playfair Display', serif;
      background-color: #000;
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
      width: 90%;
      max-width: 420px;
      padding: 2rem;
      margin: auto;
      top: 50%;
      transform: translateY(-50%);
      border-radius: 1.2rem;
      background: rgba(15, 15, 25, 0.85);
      border: 1px solid rgba(100, 200, 255, 0.2);
      box-shadow: 0 0 20px rgba(0, 150, 200, 0.15);
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
      background: rgba(5, 5, 10, 0.6);
      color: #fff;
      transition: all 0.3s ease;
    }

    input::placeholder {
      color: #bbb;
      font-style: italic;
    }

    input:focus {
      outline: none;
      border-color: #4cc9f0;
      box-shadow: 0 0 10px #4cc9f099;
    }

    button {
      background: linear-gradient(to right, #1376d1, #ffe066);
      color: #000;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 0 12px #ffe06655;
    }

    button:hover {
      transform: scale(1.03);
      box-shadow: 0 0 20px #ffe066aa;
    }

    .login-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
      color: #fbc4ab;
      text-decoration: none;
      transition: 0.3s;
    }

    .login-link:hover {
      color: #ffffff;
      text-shadow: 0 0 10px #ffd6ff;
    }

    .error-message {
      color: #ff6b6b;
      font-size: 0.875rem;
      text-align: center;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <div id="background"></div>

  <div class="form-box">
    <h2>Register to DreamWeaver</h2>

    @if ($errors->any())
      <div class="error-message">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
      @csrf
      <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
      <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>

    <a href="{{ route('login') }}" class="login-link">Already have an account? Login</a>
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
      highlightColor: 0x2c2c2c,
      midtoneColor: 0x0d0d1a,
      lowlightColor: 0x000000,
      baseColor: 0x000000,
      blurFactor: 0.6,
      speed: 1.2,
      zoom: 1.0
    });
  </script>
</body>
</html>
