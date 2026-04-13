<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <title>Login Page</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/planet_logo_32x32.png') }}" />
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
  <form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf
    <img src="{{ asset('img/logo_projecta.png') }}" alt="logo de TicketFlow" />
    <h1>
      <span>Log in</span> or
      <span><a href="{{ route('register') }}">Sign Up</a></span> to create your account
    </h1>

    <!-- <div class="role">
        <label for="roleSelect" id="roleLabel">I am :</label>
        <select name="role" id="roleSelect">
          <option value="user">a user</option>
          <option value="admin">an admin</option>
        </select>
      </div> -->

    <label for="usernameInput">Email :</label>
    <input type="email" name="email" id="usernameInput" placeholder="Enter your email" value="{{ old('email') }}"
      required />
    <div id="mailError" class="error-text titanic">
      Email format should be "johndoe@gmail.com"
    </div>
    @error('email')
      <div class="error-text">{{ $message }}</div>
    @enderror

    <label for="passwordInput">Password :</label>
    <input type="password" name="password" id="passwordInput" placeholder="Enter your password" required />
    <div id="passwordError" class="error-text titanic">
      Password must be at least 6 characters long and contain at least one
      letter and one number
    </div>
    @error('password')
      <div class="error-text">{{ $message }}</div>
    @enderror

    <button type="submit">Connect</button>
    <div id="forgotPasswd">
      <a href="{{ route('password.request') }}" class="linkPassword">forgot password ?</a>
    </div>
  </form>
  <script src="{{ asset('script.js') }}"></script>
</body>

</html>