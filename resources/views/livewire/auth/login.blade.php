<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="{{ asset('assets/img/ukt1logo.png') }}" />
        <title>Sigma | Login Page</title>
        <link rel="stylesheet" href="{{ asset('assets/asset-login/style.css') }}" />
        <style>
            /* ========================= */
            /* Styling untuk semua input teks / email / password */
            input[type="text"],
            input[type="email"],
            input[type="password"] {
                width: 100%;
                max-width: 320px;
                /* biar input tidak terlalu panjang */
                padding: 0.75rem 1rem;
                /* lebih tinggi */
                border: 1px solid #d1d5db;
                /* outline tipis abu */
                border-radius: 6px;
                font-size: 1rem;
                transition: all 0.2s ease-in-out;
                display: block;
                margin: 0.5rem auto;
                /* center horizontal */
            }

            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="password"]:focus {
                outline: none;
                border-color: #6366f1;
                box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
            }

            input::placeholder {
                color: #9ca3af;
            }

            /* Center form horizontal */
            form.login {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <form class="login" method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="logo-container">
                <img src="{{ asset('assets/img/ukt1logo.png') }}" alt="Sigma Logo" class="logo" />
            </div>

            <h2 class="text-center">SIGMA APP - UKT 1</h2>
            <p class="text-center">Sistem Integrasi Manajemen Kinerja</p>

            @error('email')
                <span class="badge" role="alert"
                    style="
                    display: block;
                    background-color: #dc3545;
                    color: white;
                    padding: 0.5rem 1rem;
                    margin-bottom: 0.75rem;
                    font-size: 1rem;
                    border-radius: 0.375rem;
                    text-align: left;
                    word-wrap: break-word;
                ">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <input type="email" name="email" id="email" placeholder="Email" required value="{{ old('email') }}" />

            <input type="password" name="password" id="password" placeholder="Password" required />
            @error('password')
                <span class="badge bg-danger p-2 mb-3 text-start fs-6 w-100 text-wrap" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <button type="submit" class="login-btn">Login</button>
        </form>
    </body>

</html>
