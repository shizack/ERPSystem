<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Login Role</title>
    <!-- Load Tailwind CSS for modern styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles based on your previous Log_In.css */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f6fa;
            /* Using the original background image source */
            background-image: url('Mayet Resort (Pillar).jpg'); 
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(5px) brightness(0.9);
            z-index: 1;
        }
        .container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 450px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .logo {
            width: 120px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .role-button {
            transition: all 0.2s ease-in-out;
        }
        .role-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<div class="container">
    <div class="card">
        <!-- Logo -->
        <img src="{{ asset('Mayet Resort Logo (TransparentBG).png') }}" class="logo" alt="Mayet Resort Logo">

        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Welcome Back</h2>
        <p class="text-gray-500 mb-8">Please select your role to proceed to the login page.</p>

        <!-- Role Selection Buttons -->
        <div class="space-y-4">
            
            <!-- Employee Button -->
            <a href="{{ route('employee.login') }}" class="role-button flex items-center justify-center p-4 bg-blue-600 text-white rounded-xl shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <span class="material-icons mr-3 text-2xl">group</span>
                <span class="text-lg font-semibold">Employeeeeeeee Login</span>
            </a>

            <!-- Admin Button -->
            <a href="{{ route('admin.login') }}" class="role-button flex items-center justify-center p-4 bg-gray-700 text-white rounded-xl shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300">
                <span class="material-icons mr-3 text-2xl">security</span>
                <span class="text-lg font-semibold">System Administrator Login</span>
            </a>
            
        </div>
        
    </div>
</div>

</body>
</html>