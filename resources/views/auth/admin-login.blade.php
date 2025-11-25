<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Log In</title>
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        /* Reset */
=======
    <!-- Google Icons for the eye icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* CSS merged from Log_In.css */
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
<<<<<<< HEAD
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Inter', sans-serif;
            background-image: url('{{ asset('images/Mayet Resort (Pillar).jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

=======
            /* Using a generic placeholder background */
            background: url('https://placehold.co/1920x1080/007bff/ffffff?text=Resort+Background') no-repeat center center fixed;
            background-size: cover;
        }

        /* Light white overlay for blur effect */
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
<<<<<<< HEAD
            backdrop-filter: blur(6px) brightness(0.85);
            background: rgba(255, 255, 255, 0.05);
            z-index: 1;
        }

        /* Card */
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

        .logo {
            width: 120px;
            margin-bottom: 15px;
=======
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
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
        }

        h2 {
            margin-bottom: 25px;
<<<<<<< HEAD
            font-size: 24px;
            font-weight: 700;
            color: #102a43;
        }

        label {
            text-align: left;
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }

        /* Inputs */
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
=======
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

        input[type="email"], /* Changed from text for Laravel auth */
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
            border-color: #007bff;
            outline: none;
        }

        /* Password Wrapper for eye icon */
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
        .password-wrapper {
            position: relative;
        }

        .toggle-eye {
            position: absolute;
<<<<<<< HEAD
            right: 12px;
            top: 35%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 22px;
        }

        /* Hide built-in password icons (Edge/Chrome) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none;
        }
        input[type="password"] {
            -webkit-appearance: none;
        }

        /* Forgot */
        .forgot {
            text-align: right;
            font-size: 14px;
            color: #007bff;
            margin-bottom: 25px;
            text-decoration: none;
        }

        /* Button */
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

        /* Switch role */
        .role-switch {
            margin-top: 18px;
            font-size: 14px;
=======
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
            color: #007bff;
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
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
        }
        .role-switch a {
            color: #007bff;
            font-weight: bold;
<<<<<<< HEAD
            text-decoration: none;
        }
    </style>
</head>

=======
        }


        /* Button */
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
<body>

<div class="overlay"></div>

<div class="login-container">
<<<<<<< HEAD

    <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
=======
    <!-- Using a placeholder for the logo -->
    <img src="https://placehold.co/110x110/007bff/ffffff?text=ADMIN+LOGO" class="logo" alt="Admin Logo">
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a

    <h2>Admin Log In</h2>

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        @if ($errors->any())
<<<<<<< HEAD
            <div style="background: #ffe3e6; color: #cc0000; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
=======
            <div style="background: #ffe3e6; color: #cc0000; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; border: 1px solid #f5c6cb;">
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
                Invalid credentials. Please try again.
            </div>
        @endif

<<<<<<< HEAD
        <!-- Email -->
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required value="{{ old('email') }}">

        <!-- Password -->
        <label for="passwordInput">Password</label>
        <div class="password-wrapper">
            <input type="password" id="passwordInput" name="password" class="password-field" placeholder="Enter password" required>
            <span class="material-icons toggle-eye">visibility</span>
=======
        <!-- Email Input -->
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required value="{{ old('email') }}">

        <!-- Password with eye toggle -->
        <label for="passwordInput">Password</label>
        <div class="password-wrapper">
            <input type="password" id="passwordInput" name="password" placeholder="Enter password" required>
            <span class="material-icons toggle-eye" onclick="togglePassword()">visibility</span>
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
        </div>

        <a href="#" class="forgot">Forgot password?</a>

        <button type="submit" class="btn">Sign in as Admin</button>
<<<<<<< HEAD

        <!-- Remember Me -->
        <div class="remember-container">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember me</label>
        </div>

=======
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
        </div>
        
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
        <p class="role-switch">
            Switch role? <a href="{{ route('employee.login') }}">Login as Employee</a>
        </p>

    </form>
</div>

<script>
<<<<<<< HEAD
    document.querySelectorAll(".toggle-eye").forEach(eye => {
        eye.addEventListener("click", function () {
            const input = this.previousElementSibling;
            const isHidden = input.type === "password";

            input.type = isHidden ? "text" : "password";
            this.innerHTML = isHidden ? "visibility_off" : "visibility";
        });
    });
</script>

</body>
</html>
=======
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
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a
