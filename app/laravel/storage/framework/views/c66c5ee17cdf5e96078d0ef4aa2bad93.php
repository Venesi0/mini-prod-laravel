<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?php echo e(asset('styles.css')); ?>" />
  <title>Login Page</title>
  <link rel="icon" type="image/x-icon" sizes="32x32" href="<?php echo e(asset('img/planet_logo_32x32.png')); ?>" />
  <style>
    body {
      min-height: 100vh;
      height: 100vh;
      background: linear-gradient(135deg, #f5f7fa, #e4ebf5);
      background-image: url("<?php echo e(asset('img/bg_login.jpg')); ?>");
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
  <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm">
    <?php echo csrf_field(); ?>
    <img src="<?php echo e(asset('img/logo_projecta.png')); ?>" alt="logo de TicketFlow" />
    <h1>
      <span>Log in</span> or
      <span><a href="<?php echo e(route('register')); ?>">Sign Up</a></span> to create your account
    </h1>

    <!-- <div class="role">
        <label for="roleSelect" id="roleLabel">I am :</label>
        <select name="role" id="roleSelect">
          <option value="user">a user</option>
          <option value="admin">an admin</option>
        </select>
      </div> -->

    <label for="usernameInput">Email :</label>
    <input type="email" name="email" id="usernameInput" placeholder="Enter your email" value="<?php echo e(old('email')); ?>"
      required />
    <div id="mailError" class="error-text titanic">
      Email format should be "johndoe@gmail.com"
    </div>
    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="error-text"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <label for="passwordInput">Password :</label>
    <input type="password" name="password" id="passwordInput" placeholder="Enter your password" required />
    <div id="passwordError" class="error-text titanic">
      Password must be at least 6 characters long and contain at least one
      letter and one number
    </div>
    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <div class="error-text"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <button type="submit">Connect</button>
    <div id="forgotPasswd">
      <a href="<?php echo e(route('password.request')); ?>" class="linkPassword">forgot password ?</a>
    </div>
  </form>
  <script src="<?php echo e(asset('script.js')); ?>"></script>
</body>

</html><?php /**PATH C:\Users\coren\Documents\Alternance\Projets\Prod\mini-prod-laravel\app\laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>