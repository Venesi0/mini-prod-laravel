<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('styles.css') }}" />
    <title>Reset Password</title>
    <link
      rel="icon"
      type="image/x-icon"
      sizes="32x32"
      href="{{ asset('img/planet_logo_32x32.png') }}"
    />
    <style>
      body {
        min-height: 100vh;
        height: 100vh;
        background: linear-gradient(135deg, #f5f7fa, #e4ebf5);
        background-image: url("{{ asset('img/bg_login.jpg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        justify-content: center;
        align-items: center;
      }
    </style>
  </head>
  <body>
    <form method="POST" action="{{ route('password.store') }}" id="resetForm">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">
      <img src="{{ asset('img/logo_projecta.png') }}" alt="logo de TicketFlow" />
      <h1>Enter your new password to connect</h1>

      <label for="usernameInput">Email :</label>
      <input
        type="email"
        name="email"
        id="usernameInput"
        placeholder="john@gmail.com"
        value="{{ old('email', $request->email) }}"
        required
      />
      @error('email')
        <div class="error-text">{{ $message }}</div>
      @enderror

      <label for="newPasswordInput">New Password :</label>
      <input
        type="password"
        name="password"
        id="newPasswordInput"
        placeholder="Enter a new password"
        required
      />
      <div id="passwordError" class="error-text titanic">
        Password must be at least 6 characters long and contain at least one
        letter and one number
      </div>
      @error('password')
        <div class="error-text">{{ $message }}</div>
      @enderror

      <label for="confirmPasswordInput">Confirm Password :</label>
      <input
        type="password"
        name="password_confirmation"
        id="confirmPasswordInput"
        placeholder="Enter the new password"
        required
      />
      <div id="confirmedPasswordError" class="error-text titanic">
        Confirmed password should be the same as the previous field
      </div>

      <button type="submit">Connect</button>
    </form>
    <script src="{{ asset('script.js') }}"></script>
  </body>
</html>
