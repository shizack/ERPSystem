<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Log In</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-image: url('{{ asset('images/Mayet Resort (Pillar).jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Glass overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px) brightness(0.85);
            background: rgba(255, 255, 255, 0.05);
            z-index: 1;
        }

        /* Login Card */
        .login-container {
            width: 420px;
            background: rgba(255, 255, 255, 0.9);
            text-align: center;
            padding: 40px 36px;
            border-radius: 16px;
            box-shadow: 0px 6px 30px rgba(0,0,0,0.2);
            position: relative;
            z-index: 2;
        }

        /* Logo */
        .logo {
            width: 120px;
            margin-bottom: 15px;
        }

        h2 {
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 700;
            color: #102a43;
        }

        /* Labels and Inputs */
        label {
            text-align: left;
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }

        input[type="email"],
        .password-field {
            width: 100%;
            padding: 13px;
            padding-left: 14px;
            padding-right: 45px;
            margin-bottom: 20px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            font-size: 15px;
            background-color: white;
            transition: all 0.25s ease-in-out;
        }

        input[type="email"]:focus,
        .password-field:focus {
            border-color: #007bff;
            box-shadow: 0px 0px 6px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        /* Password wrapper */
        .password-wrapper {
            position: relative;
        }

        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 35%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 22px;
        }

        /* Links */
        .forgot {
            text-align: right;
            font-size: 14px;
            color: #007bff;
            margin-bottom: 25px;
            text-decoration: none;
        }

        /* Button - Resort Gradient */
        .btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(90deg, #007bff, #ffcc00);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Remember Me */
        .remember-container {
            margin-top: 12px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        /* Role Switch */
        .role-switch {
            margin-top: 18px;
            font-size: 14px;
        }

        .role-switch a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }

        /* Hide Edge built-in password reveal button */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        /* Hide Chrome auto-fill password icon */
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none;
        }

        /* General fix for possible built-in icons */
        input[type="password"] {
            -webkit-appearance: none;
        }


    </style>
</head>

<body>

<div class="overlay"></div>

<div class="login-container">

    <img src="{{ asset('images/logo.png') }}" class="logo" alt="Mayet Resort Logo">

    <h2>Employee Log In</h2>

    <form method="POST" action="{{ route('employee.login') }}">
        @csrf

        @if ($errors->any())
            <div style="background: #ffe3e6; color: #cc0000; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; border: 1px solid #f5c6cb;">
                Invalid credentials. Please try again.
            </div>
        @endif

        <!-- Email -->
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required value="{{ old('email') }}">

        <!-- Password -->
        <label for="passwordInput">Password</label>
        <div class="password-wrapper">
            <input type="password" id="passwordInput" name="password" class="password-field" placeholder="Enter password" required>
            <span class="material-icons toggle-eye" onclick="togglePassword()">visibility</span>
        </div>

        <a href="#" class="forgot">Forgot password?</a>

        <button type="submit" class="btn">Sign in as Employee</button>

        <!-- Remember me added here -->
        <div class="remember-container">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember me</label>
        </div>

        <p class="role-switch">
            Switch role? <a href="{{ route('admin.login') }}">Login as Admin</a>
        </p>

    </form>
</div>

<script>
    document.querySelectorAll(".toggle-eye").forEach(eye => {
        eye.addEventListener("click", function () {
            const input = this.previousElementSibling; // password field
            const isHidden = input.type === "password";

            input.type = isHidden ? "text" : "password";
            this.innerHTML = isHidden ? "visibility_off" : "visibility";
        });
    });
</script>



</body>
</html>
