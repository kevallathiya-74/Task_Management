<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management System</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            background: var(--neutral-50);
            background-image: 
                radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.15) 0px, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            position: relative;
        }

        /* Decorative blobs */
        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--grad-primary);
            filter: blur(80px);
            opacity: 0.2;
            border-radius: 50%;
            z-index: -1;
        }
        .blob-1 { top: -100px; left: -100px; }
        .blob-2 { bottom: -100px; right: -100px; }
        
        .login-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 2rem;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }
        
        .login-header .logo-box {
            width: 60px;
            height: 60px;
            background: var(--grad-primary);
            border-radius: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
        }
        
        .login-header h2 {
            font-weight: 800;
            color: var(--neutral-900);
            letter-spacing: -0.025em;
        }
        
        .login-header p {
            color: var(--neutral-500);
            font-size: 0.9rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--neutral-700);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        
        .input-group-text {
            background: transparent;
            border-right: none;
            color: var(--neutral-400);
            padding-left: 1.25rem;
        }
        
        .form-control {
            border-left: none;
            padding-left: 0.5rem;
        }
        
        .btn-login {
            padding: 1rem;
            font-size: 1rem;
            letter-spacing: 0.025em;
            margin-top: 1.5rem;
        }

        .animate-up {
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

<div class="login-container animate-up">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    
    <div class="login-card">
        <div class="login-header text-center mb-5">
            <div class="logo-box">
                <i class="fas fa-layer-group text-white fs-2"></i>
            </div>
            <h2>Welcome Back</h2>
        <p>Sign in to manage your tasks</p>
        </div>
        
        <form id="loginForm" action="<?= url('/api/auth/login') ?>" method="POST">
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-4"><i class="fas fa-user-ninja"></i></span>
                    <input type="text" class="form-control rounded-end-4 border-start-0 py-3" id="username" name="username" placeholder="Enter your username" required autofocus>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-4"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control rounded-end-4 border-start-0 py-3" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            
            
            <button type="submit" class="btn btn-primary btn-login w-100 rounded-4">
                <span>Sign In</span>
                <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?= url('assets/js/main.js') ?>"></script>

<script>
    $(document).ready(function() {
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
