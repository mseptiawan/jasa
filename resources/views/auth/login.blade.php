<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <p class="error">{{ $message }}</p> @enderror

            <!-- Password -->
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @error('password') <p class="error">{{ $message }}</p> @enderror

            <!-- Remember Me -->
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>

            <button type="submit" class="btn">Login</button>
            <p><a href="{{ route('password.request') }}">Forgot password?</a></p>
            <p>Don’t have an account? <a href="{{ route('register') }}">Register</a></p>
        </form>
    </div>
</body>

</html>