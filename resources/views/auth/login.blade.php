<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Jasa Anda</title>

    <!-- Google Fonts: Poppins & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2b3cd7;
            --primary-hover-color: #212da8;
            --primary-disabled-color: #a9b0e0; /* Warna untuk tombol disabled */
            --background-color: #ffffff; /* Latar belakang halaman menjadi putih total */
            --text-color: #000000; /* Warna teks diubah menjadi hitam */
            --label-color: #555555;
            --border-color: #e0e0e0;
            --error-color: #e74c3c;
            --font-headings: 'Montserrat', sans-serif;
            --font-body: 'Poppins', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            color: var(--text-color);
            background-color: var(--background-color);
            display: flex;
            align-items: center; /* Vertically center the content */
            min-height: 100vh;
        }

        /* Container utama untuk layout split, tanpa style visual */
        .login-container {
            display: flex;
            width: 100%;
            max-width: 1100px; /* Lebar maksimal konten di layar besar */
            margin: 0 auto; /* Horizontally center the container */
            padding: 20px;
        }

        /* Sisi Kiri (Branding & Logo) */
        .left-pane {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* Default align center */
            padding: 20px 40px;
            text-align: center;
        }

        /* Di layar besar, branding rata kiri untuk tampilan lebih rapi */
        @media (min-width: 851px) {
            .left-pane {
                align-items: flex-start;
                text-align: left;
            }
        }

        .brand-logo img {
            width: 400px; /* Logo diperbesar seukuran form */
            height: auto;
        }

        /* Sisi Kanan (Form Login) */
        .right-pane {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .login-form-wrapper h2 {
            font-family: var(--font-headings);
            margin-bottom: 8px;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-color);
        }

        .login-form-wrapper .subtitle {
            margin-bottom: 32px;
            color: var(--label-color);
            font-size: 15px;
        }

        /* Styling Form */
        form{text-align: left;}.input-group{margin-bottom: 20px;}label{display: none;}input[type="email"]{width: 100%;padding: 12px 15px;border: 1px solid var(--border-color);border-radius: 8px;font-size: 16px;font-family: var(--font-body);transition: border-color 0.3s, box-shadow 0.3s;}input#password{width: 100%;padding: 12px 45px 12px 15px;border: 1px solid var(--border-color);border-radius: 8px;font-size: 16px;font-family: var(--font-body);transition: border-color 0.3s, box-shadow 0.3s;}input[type="email"]:focus,input#password:focus{outline: none;border-color: var(--primary-color);box-shadow: 0 0 0 3px rgba(43, 60, 215, 0.2);}.form-options{display: flex;justify-content: space-between;align-items: center;margin-bottom: 20px;font-size: 14px;}.form-options label{display: flex;align-items: center;cursor: pointer;color: var(--label-color);font-weight: 400;}.form-options input[type="checkbox"]{margin-right: 8px;accent-color: var(--primary-color);}a{color: var(--primary-color);text-decoration: none;transition: color 0.3s;}a:hover{color: var(--primary-hover-color);text-decoration: underline;}.btn{width: 100%;padding: 14px;background-color: var(--primary-color);color: white;border: none;border-radius: 8px;font-size: 16px;font-weight: 600;font-family: var(--font-body);cursor: pointer;transition: background-color 0.3s, transform 0.2s;}.btn:hover:not(:disabled){background-color: var(--primary-hover-color);transform: translateY(-2px);}.btn:disabled{background-color: var(--primary-disabled-color); cursor: not-allowed;}.register-link{margin-top: 24px;font-size: 15px;text-align: center;}.error{color: var(--error-color);font-size: 13px;margin-top: 5px;}

        .input-wrapper {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--label-color);
        }
        .toggle-password svg {
            width: 20px;
            height: 20px;
        }

        /* Responsif untuk Mobile */
        @media (max-width: 850px) {
            .login-container {
                flex-direction: column; /* Ubah layout menjadi tumpukan vertikal */
            }
            .left-pane {
                align-items: center;
                text-align: center;
                margin-bottom: 40px; /* Beri jarak antara branding dan form */
                padding: 20px;
            }
            .brand-logo img {
                width: 220px; /* Ukuran logo disesuaikan untuk mobile */
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- SISI KIRI: LOGO & BRANDING -->
        <div class="left-pane">
            <div class="brand-logo">
                <img src="{{ asset('images/logo-JasaReceh.png') }}" alt="Logo Jasa Anda">
            </div>
        </div>

        <!-- SISI KANAN: FORM LOGIN -->
        <div class="right-pane">
            <div class="login-form-wrapper">
                <h2>Selamat Datang Kembali</h2>
                <p class="subtitle">Silakan masuk untuk melanjutkan.</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                        @error('email')<p class="error">{{$message}}</p>@enderror
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                         <div class="input-wrapper">
                            <input id="password" type="password" name="password" placeholder="Password" required>
                            <span class="toggle-password" data-target="password">
                                <svg class="icon-show" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="icon-hide" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" x2="22" y1="2" y2="22"></line></svg>
                            </span>
                        </div>
                        @error('password')<p class="error">{{$message}}</p>@enderror
                    </div>
                    <div class="form-options">
                        <label><input type="checkbox" name="remember"> Ingat saya</label>
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    </div>
                    <button type="submit" id="login-button" class="btn" disabled>Login</button>
                    <div class="register-link">
                        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('login-button');

            function validateForm() {
                const emailValue = emailInput.value.trim();
                const passwordValue = passwordInput.value.trim();

                // Tombol akan aktif jika kedua input tidak kosong
                if (emailValue !== '' && passwordValue !== '') {
                    loginButton.disabled = false;
                } else {
                    loginButton.disabled = true;
                }
            }

            // Tambahkan event listener untuk setiap kali ada input
            emailInput.addEventListener('input', validateForm);
            passwordInput.addEventListener('input', validateForm);

            // Fungsionalitas toggle password
            const togglePasswordIcons = document.querySelectorAll('.toggle-password');
            togglePasswordIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetInput = document.getElementById(targetId);
                    const iconShow = this.querySelector('.icon-show');
                    const iconHide = this.querySelector('.icon-hide');

                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        iconShow.style.display = 'none';
                        iconHide.style.display = 'block';
                    } else {
                        targetInput.type = 'password';
                        iconShow.style.display = 'block';
                        iconHide.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>

