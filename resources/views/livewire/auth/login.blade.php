<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('frontend/img/logoexodia.png') }}" />
    <title>Sigma | Login Page</title>
    <link rel="stylesheet" href="{{ asset('assets/asset-login/style.css') }}" />
</head>

<body>
    <form class="login" method="POST" action="{{ route('login.store') }}">
        @csrf

        <div class="logo-container">
            <img src="{{ asset('assets/img/ukt1logo.png') }}" alt="Sigma Logo" class="logo" />
        </div>

        <h2 class="text-center">SIGMA APP - UKT 1</h2>
        <p class="text-center">Sistem Integrasi Manajemen Kinerja</p>

        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}" />
        <input type="password" name="password" placeholder="Password" required />

        <button type="submit" class="login-btn">Login</button>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif
    </form>
</body>

</html>
