<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Media - Content Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            background: url('{{ asset('images/Latarblkng Login.png') }}') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 0;
            overflow: hidden;
            animation: backgroundShift 20s ease-in-out infinite alternate;
        }

        @keyframes backgroundShift {
            0% { background-position: center center; }
            100% { background-position: center 10%; }
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
            transition: background 0.3s ease;
        }

        /* Responsive breakpoints */
        @media (max-width: 768px) {
            body::before {
                background: rgba(0, 0, 0, 0.5);
            }
        }

        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100vh;
            position: relative;
            z-index: 2;
            padding: 20px;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 50px 40px 40px 40px;
            width: 100%;
            max-width: 420px;
            min-width: 320px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideIn 0.6s ease-out;
            transition: all 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-form:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px rgba(0, 0, 0, 0.3);
        }

        .login-form::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 50%, rgba(255, 255, 255, 0.3) 100%);
            border-radius: 0 0 25px 25px;
            pointer-events: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                padding: 15px;
            }
            
            .login-form {
                padding: 40px 30px 30px 30px;
                max-width: 100%;
                min-width: 280px;
                border-radius: 20px;
            }
            
            .form-title {
                font-size: 20px;
            }
            
            .form-subtitle {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 10px;
            }
            
            .login-form {
                padding: 30px 25px 25px 25px;
                border-radius: 15px;
            }
            
            .form-logo-text {
                font-size: 22px;
            }
            
            .form-title {
                font-size: 18px;
            }
            
            .form-subtitle {
                font-size: 12px;
                margin-bottom: 25px;
            }
            
            .form-group input {
                padding: 14px 16px;
                font-size: 14px;
            }
            
            .login-btn {
                padding: 14px;
                font-size: 15px;
            }
        }

        @media (max-width: 360px) {
            .login-form {
                padding: 25px 20px 20px 20px;
                min-width: 260px;
            }
            
            .form-logo-text {
                font-size: 20px;
            }
            
            .form-title {
                font-size: 16px;
            }
        }

        .form-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .form-logo img {
            height: 40px;
            width: auto;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .form-logo img:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        .form-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .form-logo-icon::before {
            content: 'e';
            color: white;
            font-size: 22px;
            font-weight: bold;
            font-style: italic;
        }

        .form-logo-text {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            letter-spacing: -0.5px;
        }

        .form-title {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            text-align: center;
            line-height: 1.3;
        }

        .form-subtitle {
            color: #666;
            margin-bottom: 35px;
            font-size: 14px;
            text-align: center;
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 20px;
            animation: slideInLeft 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .form-group input {
            width: 100%;
            padding: 16px 18px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            position: relative;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
            background: white;
            transform: translateY(-2px);
        }

        .form-group input:focus + label {
            color: #ff6b6b;
        }

        .form-group:hover input {
            border-color: #ff6b6b;
            transform: translateY(-1px);
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(90deg, #ff6b6b 0%, #ee5a52 50%, #d63031 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(214, 48, 49, 0.3);
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            animation: buttonPulse 2s ease-in-out infinite;
        }

        @keyframes buttonPulse {
            0%, 100% { box-shadow: 0 4px 15px rgba(214, 48, 49, 0.3); }
            50% { box-shadow: 0 6px 20px rgba(214, 48, 49, 0.5); }
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(214, 48, 49, 0.4);
            animation: none;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:active {
            transform: translateY(-1px) scale(0.98);
        }

        .login-btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .login-btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-spin 1s linear infinite;
        }

        @keyframes button-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .copyright {
            text-align: center;
            color: #888;
            font-size: 11px;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c66;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .demo-accounts {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 12px;
            animation: slideInRight 0.6s ease-out;
            animation-delay: 0.3s;
            animation-fill-mode: both;
            transition: all 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .demo-accounts:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .demo-accounts h4 {
            margin-bottom: 8px;
            color: #495057;
            transition: color 0.3s ease;
        }

        .demo-accounts p {
            margin: 3px 0;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .demo-accounts:hover h4,
        .demo-accounts:hover p {
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="form-logo">
                <!-- Jika Anda punya logo file, gunakan ini: -->
                <img src="{{ asset('images/Logo life media.png') }}" alt="Life Media Logo">
                
                <!-- Atau gunakan logo CSS seperti ini: -->
                <!-- <div class="form-logo-icon"></div>
                <div class="form-logo-text">Life media</div> -->
            </div>

            <h2 class="form-title">Welcome to Life Media Content Management System</h2>
            <p class="form-subtitle">Please log in here</p>

            <!-- Demo accounts info 
            <div class="demo-accounts">
                <h4>Demo Accounts:</h4>
                <p><strong>Admin:</strong> admin@lifemedia.com / admin123</p>
                <p><strong>Sales:</strong> sales@lifemedia.com / sales123</p>
                <p><strong>Sudirman Park:</strong> sudirmanpark@lifemedia.com / sudirman123</p>
                <p><strong>Report:</strong> report@lifemedia.com / report123</p>
            </div> -->

            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="login-btn">Log In</button>
            </form>

            <div class="copyright">
                2025 Life Media. All Rights Reserved
            </div>
        </div>
    </div>

    <script>
        // Loading effect untuk tombol login
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            const loginBtn = document.querySelector('.login-btn');
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');

            // Animasi saat form submit
            loginForm.addEventListener('submit', function(e) {
                loginBtn.classList.add('loading');
                loginBtn.textContent = '';
            });

            // Auto-focus pada input pertama
            emailInput.focus();

            // Smooth scrolling untuk mobile
            if (window.innerWidth <= 768) {
                document.body.style.scrollBehavior = 'smooth';
            }

            // Parallax effect untuk background (desktop only)
            if (window.innerWidth > 768) {
                window.addEventListener('mousemove', function(e) {
                    const mouseX = e.clientX / window.innerWidth;
                    const mouseY = e.clientY / window.innerHeight;
                    
                    document.body.style.backgroundPosition = 
                        `${50 + mouseX * 2}% ${50 + mouseY * 2}%`;
                });
            }

            // Input validation dengan visual feedback
            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.borderColor = '#28a745';
                        this.style.background = 'rgba(40, 167, 69, 0.05)';
                    } else {
                        this.style.borderColor = '#e1e5e9';
                        this.style.background = 'rgba(255, 255, 255, 0.9)';
                    }
                });

                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = '#e1e5e9';
                        this.style.background = 'rgba(255, 255, 255, 0.9)';
                    }
                });
            });

            // Demo account click to fill
            const demoAccounts = document.querySelectorAll('.demo-accounts p');
            demoAccounts.forEach(account => {
                account.style.cursor = 'pointer';
                account.addEventListener('click', function() {
                    const text = this.textContent;
                    const emailMatch = text.match(/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/);
                    const passwordMatch = text.match(/\/([a-zA-Z0-9]+)/);
                    
                    if (emailMatch) {
                        emailInput.value = emailMatch[1];
                        emailInput.style.borderColor = '#28a745';
                        emailInput.style.background = 'rgba(40, 167, 69, 0.05)';
                    }
                    
                    if (passwordMatch) {
                        passwordInput.value = passwordMatch[1];
                        passwordInput.style.borderColor = '#28a745';
                        passwordInput.style.background = 'rgba(40, 167, 69, 0.05)';
                    }
                });
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Enter key pada form
                if (e.key === 'Enter' && (e.target === emailInput || e.target === passwordInput)) {
                    e.preventDefault();
                    if (e.target === emailInput) {
                        passwordInput.focus();
                    } else {
                        loginForm.submit();
                    }
                }
                
                // Escape key untuk clear form
                if (e.key === 'Escape') {
                    emailInput.value = '';
                    passwordInput.value = '';
                    emailInput.style.borderColor = '#e1e5e9';
                    passwordInput.style.borderColor = '#e1e5e9';
                    emailInput.style.background = 'rgba(255, 255, 255, 0.9)';
                    passwordInput.style.background = 'rgba(255, 255, 255, 0.9)';
                    emailInput.focus();
                }
            });

            // Responsive adjustments
            function handleResize() {
                const form = document.querySelector('.login-form');
                if (window.innerWidth <= 480) {
                    form.style.maxWidth = '100%';
                    form.style.margin = '10px';
                } else {
                    form.style.maxWidth = '420px';
                    form.style.margin = '0';
                }
            }

            window.addEventListener('resize', handleResize);
            handleResize();
        });
    </script>
</body>
</html>