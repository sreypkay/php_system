<?php
session_start();
ob_start();

if(isset($_POST["submit"])){
    $u = $_POST['username'];
    $p = ($_POST['password']);

    $sql = "SELECT fullname FROM tbluser WHERE username=? AND password=?;";
    require("db.php");
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $u, $p);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['logged_in'] = true;
        
        // Close connections
        $stmt->close();
        $conn->close();
        
        header("Location: index.php");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        $login_error = "<div class='error-message'>Invalid username or password!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ABC Library Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            position: relative;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
            border-radius: 20px;
        }

        .illustration-side {
            flex: 1;
            background-image: url("images/library.png");
            position: relative;
            background-size: contain;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        .illustration-side::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .illustration-content {
            position: relative;
            z-index: 2;
        }

        .illustration-side .icon {
            font-size: 4rem;
            margin-bottom: 30px;
            opacity: 0.9;
           
        }

        .illustration-side h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .illustration-side p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.9;
            max-width: 300px;
            margin-bottom: 30px;
        }

        .dots-indicator {
            display: flex;
            gap: 10px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }

        .dot.active {
            background: white;
            transform: scale(1.2);
        }

        .login-form-side {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #718096;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            padding-left: 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #6a0dad;
            background: white;
            box-shadow: 0 0 0 3px rgba(106, 13, 173, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
        }

        .form-control:focus + .input-icon {
            color: #6a0dad;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #6a0dad;
        }

        .checkbox-wrapper label {
            margin: 0;
            font-size: 0.9rem;
            color: #4a5568;
            cursor: pointer;
        }

        .forgot-password {
            color: #6a0dad;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #4a148c;
            text-decoration: underline;
        }

        .btn-login {
            background: linear-gradient(135deg, #6a0dad 0%, #4a148c 100%);
            color: white;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(106, 13, 173, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border-left: 4px solid #c53030;
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }
        .login-form-side {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #718096;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            padding-left: 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #6a0dad;
            background: white;
            box-shadow: 0 0 0 3px rgba(106, 13, 173, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
        }

        .form-control:focus + .input-icon {
            color: #6a0dad;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #6a0dad;
        }

        .checkbox-wrapper label {
            margin: 0;
            font-size: 0.9rem;
            color: #4a5568;
            cursor: pointer;
        }

        .forgot-password {
            color: #6a0dad;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #4a148c;
            text-decoration: underline;
        }

        .btn-login {
            background: linear-gradient(135deg, #6a0dad 0%, #4a148c 100%);
            color: white;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(106, 13, 173, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border-left: 4px solid #c53030;
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }
        .social-login p {
            color: #718096;
            margin-bottom: 20px;
            position: relative;
        }

        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e2e8f0;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .social-btn.google {
            color: #db4437;
        }

        .social-btn.facebook {
            color: #4267b2;
        }

        .social-btn.twitter {
            color: #1da1f2;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 10px;
                border-radius: 15px;
            }

            .illustration-side {
                padding: 40px 30px;
                min-height: 300px;
            }

            .illustration-side h2 {
                font-size: 1.8rem;
            }

            .illustration-side p {
                font-size: 1rem;
            }

            .login-form-side {
                padding: 40px 30px;
            }

            .form-header h2 {
                font-size: 1.7rem;
            }

            .options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .login-form-side {
                padding: 30px 20px;
            }

            .form-control {
                padding: 12px 15px;
                padding-left: 45px;
            }

            .btn-login {
                padding: 14px 20px;
            }
        }
    </style>
    
</head>
<body>
<div class="login-container">
        <div class="illustration-side">
            <div class="illustration-content">
                <div class="icon">
                    <i class="fas fa-book-open"></i>
                </div>

                <div class="dots-indicator">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
        </div>

        <div class="login-form-side">
            <div class="form-header">
                <h2>Welcome Back</h2>
                <p>Please sign in to your account to continue</p>
            </div>

            <!-- Error message placeholder -->
            <div class="error-message" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                Invalid username or password!
            </div>
            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label for="username">Email or Username</label>
                    <div class="input-wrapper">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your email or username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <div class="options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember_me" name="remember_me">
                        <label for="remember_me">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" name="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                    Sign In
                </button>
            </form>
            <?php if(isset($login_error)) echo $login_error; ?>

            <div class="social-login">
                <p>Or continue with</p>
                <div class="social-buttons">
                    <div class="social-btn google">
                        <i class="fab fa-google"></i>
                    </div>
                    <div class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate dots
            const dots = document.querySelectorAll('.dot');
            let currentDot = 0;
            
            setInterval(() => {
                dots[currentDot].classList.remove('active');
                currentDot = (currentDot + 1) % dots.length;
                dots[currentDot].classList.add('active');
            }, 3000);

            // Form validation feedback
            const form = document.getElementById('loginForm');
            const inputs = form.querySelectorAll('.form-control');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = '#e53e3e';
                    } else {
                        this.style.borderColor = '#48bb78';
                    }
                });
                
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#6a0dad';
                });
            });
        });
    </script>
</body>
</html>
            
         
