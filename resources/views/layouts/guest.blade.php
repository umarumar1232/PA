<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Learning') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f8f9fa;
                margin: 0;
            }
            .auth-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }
            .auth-card {
                background: white;
                width: 100%;
                max-w-md: 400px;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                border: 1px solid #e0e0e0;
            }
            .auth-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                margin-bottom: 2rem;
                text-decoration: none;
            }
            .auth-logo i {
                font-size: 32px;
                color: #1a73e8;
            }
            .auth-logo span {
                font-size: 24px;
                font-weight: 600;
                color: #3c4043;
            }
            .auth-input-group {
                margin-bottom: 1.5rem;
            }
            .auth-label {
                display: block;
                font-size: 14px;
                font-weight: 500;
                color: #3c4043;
                margin-bottom: 0.5rem;
            }
            .auth-input {
                width: 100%;
                padding: 10px 14px;
                border: 1px solid #dadace;
                border-radius: 8px;
                font-size: 15px;
                color: #3c4043;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .auth-input:focus {
                outline: none;
                border-color: #1a73e8;
                box-shadow: 0 0 0 4px rgba(26, 115, 232, 0.1);
            }
            .auth-btn-primary {
                width: 100%;
                padding: 12px;
                background-color: #1a73e8;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 500;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            .auth-btn-primary:hover {
                background-color: #1557b0;
            }
            .auth-btn-google {
                width: 100%;
                padding: 12px;
                background-color: white;
                color: #3c4043;
                border: 1px solid #dadce0;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 500;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                text-decoration: none;
                transition: background-color 0.2s;
            }
            .auth-btn-google:hover {
                background-color: #f8f9fa;
                text-decoration: none;
            }
            .auth-divider {
                display: flex;
                align-items: center;
                margin: 1.5rem 0;
            }
            .auth-divider::before, .auth-divider::after {
                content: '';
                flex: 1;
                border-bottom: 1px solid #dadce0;
            }
            .auth-divider span {
                padding: 0 16px;
                color: #5f6368;
                font-size: 13px;
                font-weight: 500;
            }
            .auth-footer {
                text-align: center;
                margin-top: 1.5rem;
                font-size: 14px;
                color: #5f6368;
            }
            .auth-link {
                color: #1a73e8;
                text-decoration: none;
                font-weight: 500;
            }
            .auth-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-card" style="max-width: 440px;">
                <a href="/" class="auth-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>E-Learning</span>
                </a>
                
                {{ $slot }}

            </div>
        </div>
    </body>
</html>
