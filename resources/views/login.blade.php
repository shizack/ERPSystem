<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Login Role</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('{{ asset('images/Mayet Resort (Pillar).jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px) brightness(0.85);
            z-index: 1;
        }

        .container {
        position: relative;
        z-index: 10;
        min-height: 100vh; /* use 100vh if you prefer */
        display: grid;
        place-items: center;
        padding: 20px;
        }

        .card {
        width: 430px;
        max-width: 100%;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.92);
        padding: 45px 40px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

        .logo {
            width: 110px;
            margin-bottom: 15px;
        }

        .role-button {
            transition: all 0.25s ease-in-out;
        }

        .role-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #1a1a1a;
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<div class="container">
    <div class="card">
        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Mayet Resort Logo">

        <h2 class="text-2xl font-bold mb-3">Log in to your account</h2>

        <!-- Role Selection Buttons -->
        <div class="space-y-4 mt-6">

            <!-- Employee Button -->
            <a href="{{ route('employee.login') }}"
               class="role-button flex items-center justify-center p-3 bg-blue-600 text-white rounded-xl shadow-md hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                👥 <span class="ml-2 font-medium">Employee Login</span>
            </a>

            <!-- Manager Button -->
            <a href="{{ route('manager.login') }}"
               class="role-button flex items-center justify-center p-3 bg-yellow-500 text-white rounded-xl shadow-md hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300">
                🧑‍💼 <span class="ml-2 font-medium">Manager Login</span>
            </a>

            <!-- Admin Button -->
            <a href="{{ route('admin.login') }}"
               class="role-button flex items-center justify-center p-3 bg-red-500 text-white rounded-xl shadow-md hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300">
                🔐 <span class="ml-2 font-medium">System Administrator Login</span>
            </a>

        </div>
    </div>
</div>

</body>
</html>
