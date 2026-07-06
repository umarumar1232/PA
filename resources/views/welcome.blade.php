<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang - E-Learning</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Styles -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #3c4043;
        }

        .navbar {
            display: flex;
            justify-content: flex-end;
            padding: 24px 40px;
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
        }

        .nav-links {
            display: flex;
            gap: 16px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline {
            color: #1a73e8;
            background: transparent;
            border: 1px solid #1a73e8;
        }

        .btn-outline:hover {
            background: rgba(26, 115, 232, 0.05);
        }

        .btn-primary {
            color: white;
            background: #1a73e8;
            border: 1px solid #1a73e8;
            box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3);
        }

        .btn-primary:hover {
            background: #1557b0;
            border-color: #1557b0;
            box-shadow: 0 1px 3px 1px rgba(60,64,67,0.15);
        }

        .main-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 24px;
        }

        .logo-icon {
            font-size: 72px;
            color: #1a73e8;
            margin-bottom: 16px;
        }

        h1 {
            font-size: 42px;
            font-weight: 700;
            color: #202124;
            margin: 0 0 16px 0;
            letter-spacing: -0.5px;
        }

        p {
            font-size: 18px;
            color: #5f6368;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .cta-container {
            margin-top: 40px;
        }

        .btn-cta {
            padding: 14px 32px;
            font-size: 16px;
            border-radius: 24px;
        }
        
        @media (max-width: 600px) {
            h1 { font-size: 32px; }
            p { font-size: 16px; padding: 0 20px; }
            .navbar { padding: 16px 20px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        @if (Route::has('login'))
            <div class="nav-links">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ url('/mahasiswa/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    <main class="main-content">
        <div class="logo-container">
            <i class="fas fa-graduation-cap logo-icon"></i>
            <h1>E-Learning</h1>
        </div>
        <p>Platform pembelajaran digital yang dirancang untuk memudahkan interaksi antara dosen dan mahasiswa dalam satu tempat.</p>
        
        @guest
        <div class="cta-container">
            <a href="{{ route('register') }}" class="btn btn-primary btn-cta">Mulai Sekarang</a>
        </div>
        @endguest
    </main>

</body>
</html>
