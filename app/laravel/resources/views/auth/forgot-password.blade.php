<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('styles.css') }}" />
    <title>Forgot Password</title>
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
        display: flex;
        justify-content: center;
        align-items: center;
      }
    </style>
  </head>
  <body>
    <form method="POST" action="{{ route('password.email') }}" id="ForgotPasswordForm">
      @csrf
      <img src="{{ asset('img/logo_projecta.png') }}" alt="logo de TicketFlow" />
      <h1>Enter your email to receive a reset link</h1>

      @if (session('status'))
        <div class="error-text">{{ session('status') }}</div>
      @endif

      <label for="usernameInput">Email :</label>
      <input
        type="email"
        name="email"
        id="usernameInput"
        placeholder="john@gmail.com"
        value="{{ old('email') }}"
        required
      />
      <div id="mailError" class="error-text titanic">
        Email format should be "johndoe@gmail.com"
      </div>
      @error('email')
        <div class="error-text">{{ $message }}</div>
      @enderror

      <button type="submit">Send reset link</button>

      <div class="code-inputs">
        <label for="code1">Enter code :</label>
        <div class="code-group">
          <input type="text" id="code1" name="code1" maxlength="1" inputmode="numeric" pattern="[0-9]" />
          <input type="text" id="code2" name="code2" maxlength="1" inputmode="numeric" pattern="[0-9]" />
          <input type="text" id="code3" name="code3" maxlength="1" inputmode="numeric" pattern="[0-9]" />
          <input type="text" id="code4" name="code4" maxlength="1" inputmode="numeric" pattern="[0-9]" />
          <input type="text" id="code5" name="code5" maxlength="1" inputmode="numeric" pattern="[0-9]" />
          <input type="text" id="code6" name="code6" maxlength="1" inputmode="numeric" pattern="[0-9]" />
        </div>
        <div id="codeError" class="error-text titanic">
          Code should be '222222'
        </div>
        @error('code')
          <div class="error-text">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit">Validate</button>
    </form>
    <script src="{{ asset('script.js') }}"></script>
  </body>
</html>
