<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - DreamWeaver</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      background: radial-gradient(circle at center, #0b0f1a 0%, #000 100%);
      font-family: 'Inter', sans-serif;
      color: white;
    }

    .form-box {
      position: relative;
      z-index: 1;
      max-width: 400px;
      margin: 100px auto;
      padding: 2rem;
      border-radius: 1rem;
      border: 1px solid #c084fc44;
      background: rgba(0, 0, 0, 0.6);
      box-shadow: 0 0 20px #c084fc33;
    }

    h2 {
      text-align: center;
      font-size: 1.5rem;
      font-weight: bold;
      color: #e879f9;
      margin-bottom: 1.5rem;
    }

    input, button {
      width: 100%;
      padding: 10px;
      margin-top: 12px;
      background: black;
      color: white !important;
      caret-color: white !important;
      border: 1px solid #c084fc;
      border-radius: 8px;
      font-size: 1rem;
    }

    input::placeholder {
      color: #bbb;
    }

    button:hover {
      background-color: #c084fc;
      color: black;
      transition: 0.3s ease;
    }

    .error-message {
      color: #f87171;
      font-size: 0.875rem;
      text-align: center;
      margin-bottom: 1rem;
    }

    .login-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
      color: #c084fc;
      text-decoration: none;
    }

    .login-link:hover {
      color: #e879f9;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="form-box">
    <h2>Register to DreamWeaver</h2>

    @if ($errors->any())
      <div class="error-message">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
      @csrf
      <input
        type="text"
        name="name"
        placeholder="Name"
        value="{{ old('name') }}"
        required
      >
      <input
        type="email"
        name="email"
        placeholder="Email"
        value="{{ old('email') }}"
        required
      >
      <input
        type="password"
        name="password"
        placeholder="Password"
        required
      >

      <button type="submit">Register</button>
    </form>

    <a href="{{ route('login') }}" class="login-link">Already have an account? Login</a>
  </div>
</body>
</html>
