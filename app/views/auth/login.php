<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management System</title>
    <link rel="icon" type="image/png" href="<?= asset('image/logo.png') ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= url('assets/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    
    <style>
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        .login-wrap {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            z-index: 10;
        }

        .login-card {
            background: #ffffff;
            border-radius: 2rem;
            padding: 3.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            border-top: 6px solid #6366f1;
        }

        .brand-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .brand-logo-img {
            width: 80px;
            height: auto;
            margin-bottom: 1rem;
        }

        .brand-text {
            font-size: 1.75rem;
            color: #1e293b;
            letter-spacing: -1px;
        }

        .brand-text b {
            font-weight: 800;
        }

        .brand-text span {
            color: #6366f1;
            font-weight: 600;
        }

        .input-label {
            color: #94a3b8;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
            display: block;
        }

        .custom-input-group {
            background: #f1f5f9;
            border-radius: 1rem;
            padding: 0.5rem 0.5rem 0.5rem 1.25rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            margin-bottom: 1.75rem;
            border: 1px solid transparent;
        }

        .custom-input-group:focus-within {
            background: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .custom-input-group i {
            color: #94a3b8;
            font-size: 1rem;
        }

        .custom-input-group input {
            background: transparent;
            border: none;
            color: #1e293b;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .custom-input-group input:focus { outline: none; }
        .custom-input-group input::placeholder { color: #94a3b8; }

        .btn-auth {
            background: #6366f1;
            color: white;
            border: none;
            width: 100%;
            padding: 1.15rem;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.25);
            margin-top: 1.5rem;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.35);
            background: #4f46e5;
        }

        .btn-auth:active { transform: translateY(0); }

        /* Animations */
        .reveal {
            animation: reveal 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        @keyframes reveal {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-wrap reveal">
    <div class="login-card">
        <div class="brand-section">
            <img src="<?= url('assets/image/logo.png') ?>" alt="Logo" class="brand-logo-img">
            <div class="brand-text"><b>Deckoid</b><span>Tasks</span></div>
        </div>
        
        <form id="loginForm" action="<?= url('/api/auth/login') ?>" method="POST" data-no-toast="true">
            <div class="mb-2">
                <span class="input-label">Username</span>
                <div class="custom-input-group">
                    <i class="far fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
                </div>
            </div>
            
            <div class="mb-2">
                <span class="input-label">Password</span>
                <div class="custom-input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn-auth">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= url('assets/js/main.js') ?>"></script>

<script>
    $(document).ready(function() {
        // Handle login form submission
        handleFormSubmit('#loginForm', function(response) {
            if (response.redirect) {
                setTimeout(function() {
                    window.location.href = response.redirect;
                }, 1000);
            }
        });
    });
</script>

</body>
</html>
