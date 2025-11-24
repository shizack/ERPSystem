<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Log In</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* CSS merged from Log_In.css */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            /* Using a generic placeholder background */
            background: url('https://placehold.co/1920x1080/4f46e5/ffffff?text=Employee+Background') no-repeat center center fixed;
            background-size: cover;
        }

        /* Light white overlay for blur effect */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(5px) brightness(0.9);
        }

        /* Login card container */
        .login-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 380px;
            background: rgba(255, 255, 255, 0.92);
            padding: 35px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.15);
        }

        .logo {
            width: 110px;
            margin-bottom: 10px;
        }

        h2 {
            margin-bottom: 25px;
            font-size: 22px;
            font-weight: 600;
        }

        /* Inputs */
        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            padding-left: 14px;
            margin-bottom: 18px;
            border: 1px solid #cccccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4f46e5;
            outline: none;
        }

        /* Password Wrapper for eye icon */
        .password-wrapper {
            position: relative;
        }

        .toggle-eye {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 20px;
        }

        /* Links */
        .forgot {
            display: block;
            text-align: right;
            font-size: 13px;
            color: #4f46e5;
            text-decoration: none;
            margin-bottom: 25px;
        }
        
        .role-switch {
            display: block;
            text-align: left;
            font-size: 13px;
            color: #888;
            text-decoration: none;
            margin-top: 15px;
        }
        .role-switch a {
            color: #4f46e5;
            font-weight: bold;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #4f46e5; /* Indigo color for employee */
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<div class="login-container">
    <!-- Using a placeholder for the logo -->
    <img src="https://placehold.co/110x110/4f46e5/ffffff?text=EMP+LOGO" class="logo" alt="Employee Logo">

    <h2>Employee Log In</h2>

    <form method="POST" action="{{ route('employee.login') }}">
        @csrf

        @if ($errors->any())
            <div style="background: #ffe3e6; color: #cc0000; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; border: 1px solid #f5c6cb;">
                Invalid credentials. Please try again.
            </div>
        @endif

        <!-- Email Input -->
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required value="{{ old('email') }}">

        <!-- Password with eye toggle -->
        <label for="passwordInput">Password</label>
        <div class="password-wrapper">
            <input type="password" id="passwordInput" name="password" placeholder="Enter password" required>
            <span class="material-icons toggle-eye" onclick="togglePassword()">visibility</span>
        </div>

        <a href="#" class="forgot">Forgot password?</a>

        <button type="submit" class="btn">Sign in as Employee</button>
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
        </div>

        <p class="role-switch">
            Switch role? <a href="{{ route('admin.login') }}">Login as Admin</a>
        </p>

    </form>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById("passwordInput");
        const icon = document.querySelector(".toggle-eye");

        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "visibility_off";
        } else {
            input.type = "password";
            icon.textContent = "visibility";
        }
    }
</script>
</body>
</html>