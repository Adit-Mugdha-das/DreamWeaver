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
  width: 95%;
  max-width: 500px;
  padding: 2.5rem 2rem;
  margin: auto;
  top: 50%;
  transform: translateY(-50%);
  border-radius: 1.2rem;
  background: linear-gradient(135deg, rgba(40, 15, 30, 0.9), rgba(10, 5, 15, 0.85));
  border: 1px solid rgba(255, 192, 203, 0.15);
  box-shadow: 0 0 35px rgba(255, 105, 180, 0.15), 0 0 70px rgba(255, 182, 193, 0.05);
  animation: fadeInUp 1.2s ease-out;
  opacity: 0;
  animation-fill-mode: forwards;
}



    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(-40%) scale(0.95); }
      100% { opacity: 1; transform: translateY(-50%) scale(1); }
    }

    h2 {
      text-align: center;
      font-family: 'UnifrakturCook', cursive;
      font-size: 2.2rem;
      color: #ffe066;
      text-shadow: 2px 2px 10px #ff85b3;

      margin-bottom: 1.5rem;
    }

    input, button {
      width: 100%;
      padding: 12px;
      margin-top: 14px;
      font-size: 1rem;
      border-radius: 8px;
      border: 1px solid #ff99cc;
      background: rgba(5, 5, 10, 0.6);
      color: #fff;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }

    input::placeholder {
      color: #bbb;
      font-style: italic;
    }

    input:focus {
    outline: none;
    border-color: #ff4f91;
    box-shadow: 0 0 10px #ff4f9188;
  }


    .password-wrapper {
  width: 100%;
  position: relative;
  display: flex;
  align-items: center;
}

.password-wrapper input {
  width: 100%;
  box-sizing: border-box;
  padding-right: 40px;
  height: 48px; /* Ensures alignment works well */
}

.password-toggle {
  position: absolute;
  right: 12px;
  cursor: pointer;
  font-size: 1.1rem;
  color: #ddd;
  height: 100%;
  display: flex;
  align-items: center;
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

    button {
  background: linear-gradient(to right, #ff4f91, #ffc6b3);
  color: #1a001a;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 0 0 12px #ff7faa88;
  border: 1px solid #ff7faa;
  transition: all 0.3s ease;
}

button:hover {
  transform: scale(1.02);
  box-shadow: 0 0 18px #ff99ccaa;
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

    <form method="POST" action="{{ route('register.submit') }}" onsubmit="return validateEmail()">
      @csrf
      <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" autocomplete="name" required>

      <input type="email" name="email" id="email" placeholder="Email (must end with @dream.com)" value="{{ old('email') }}" autocomplete="email" required>

      <div class="password-wrapper">
        <input type="password" name="password" id="password" placeholder="Password" autocomplete="new-password" required>
        <span class="password-toggle" onclick="togglePassword()">👁️</span>
      </div>
      <div class="strength-bar"><div id="strengthFill" class="strength-bar-fill"></div></div>

      <input type="password" name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password" required>

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
    highlightColor: 0xff85b3,  // Soft rose highlight
    midtoneColor: 0x3a0a1e,    // Dark reddish-purple midtone
    lowlightColor: 0x1e0a14,   // Subtle dark rose
    baseColor: 0x12010a,       // Almost black with warm tint
    blurFactor: 0.7,
    speed: 1.0,
    zoom: 1.2
  });


    function togglePassword() {
      const passField = document.getElementById('password');
      passField.type = passField.type === 'password' ? 'text' : 'password';
    }

    function validateEmail() {
      const email = document.getElementById("email").value;
      const pattern = /^[a-zA-Z0-9._%+-]+@dream\.com$/;
      if (!pattern.test(email)) {
        alert("Only @dream.com emails are allowed.");
        return false;
      }
      return true;
    }

    document.getElementById("password").addEventListener("input", function () {
      const strengthFill = document.getElementById("strengthFill");
      const val = this.value;
      let strength = 0;
      if (val.length >= 6) strength += 1;
      if (/[A-Z]/.test(val)) strength += 1;
      if (/[0-9]/.test(val)) strength += 1;
      if (/[^A-Za-z0-9]/.test(val)) strength += 1;
      const width = ['0%', '30%', '60%', '90%', '100%'][strength];
      const colors = ['red', 'orange', 'yellow', 'lightgreen', 'green'];
      strengthFill.style.width = width;
      strengthFill.style.background = colors[strength];
    });
  </script>
</body>
</html>
