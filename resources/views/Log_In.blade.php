<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Log in</title>

	<link rel="stylesheet" href="{{ asset('css/Log_In.css') }}">

	<!-- Google Icons for the eye icon -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<div class="overlay"></div>

<div class="login-container">
	{{-- Use public/images/logo.png (copy your logo there) --}}
	<img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">

	<h2>Log in to your account</h2>

	<form class="login-form" method="POST" action="#">
		<!-- Username -->
		<label>Username</label>
		<input type="text" name="username" placeholder="Enter username" required>

		<!-- Password with eye toggle -->
		<label>Password</label>
		<div class="password-wrapper">
			<input type="password" id="passwordInput" name="password" placeholder="Enter password" required>
			<button type="button" class="eye-btn" aria-label="Toggle password" onclick="togglePassword()">
				<span class="material-icons toggle-eye">visibility</span>
			</button>
		</div>

		<a href="#" class="forgot">Forgot password</a>

		<button type="submit" class="btn">Sign in</button>
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
