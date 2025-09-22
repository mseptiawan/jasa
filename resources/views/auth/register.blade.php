<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="form-container">
        <h2>Register</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <label for="name">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <p class="error">{{ $message }}</p> @enderror

            <!-- Email -->
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <p class="error">{{ $message }}</p> @enderror

            <!-- Password -->
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @error('password') <p class="error">{{ $message }}</p> @enderror

            <!-- Confirm Password -->
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
            @error('password_confirmation') <p class="error">{{ $message }}</p> @enderror

            <button type="submit" class="btn">Register</button>
            <p>Already registered? <a href="{{ route('login') }}">Login here</a></p>
        </form>
    </div>
</body>

</html>