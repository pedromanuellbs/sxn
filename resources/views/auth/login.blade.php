<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Split Login Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html, body {
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
    }

    .container {
      display: flex;
      height: 100vh;
      width: 100%;
    }

    .left-side {
      flex: 1;
      background-color: #b4c8fc;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 2rem;
      text-align: center;
    }

    .left-side img {
      width: 70px;
      margin-bottom: 20px;
    }

    .left-side h1 {
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .left-side p {
      max-width: 80%;
      font-size: 1rem;
      font-weight: 500;
    }

    .right-side {
      flex: 1;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-card {
      background-color: #b4c8fc;
      padding: 2rem;
      border-radius: 12px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .login-card h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .login-card label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
    }

    .login-card input {
      width: 100%;
      padding: 0.5rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .login-card a {
      display: block;
      text-align: right;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      color: #333;
      text-decoration: none;
    }

    .login-card button {
      width: 100%;
      padding: 0.6rem;
      background-color: #111;
      color: white;
      border: none;
      border-radius: 30px;
      font-weight: bold;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .left-side, .right-side {
        flex: none;
        width: 100%;
        height: 50vh;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="left-side">
    <img style="height: 20%;width:60%" src="https://gsk.co.id/wp-content/uploads/2022/08/Logo-color-1024x400.png" alt="Logo">
    <p>Kami adalah perusahan teknologi kreatif yang berfokus pada layanan pengembangan aplikasi berbasis immersive technology dan AI untuk membantu anda dalam kebutuhan event & marketing activation</p>
  </div>
  <div class="right-side">
    <div class="login-card">
      <h2>Sign In</h2>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <label>Email *</label>
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
        <label>Password *</label>
        <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        {{-- <a href="#">Forgot Password?</a> --}}

        <button type="submit">LOGIN</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
