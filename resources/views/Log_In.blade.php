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

{<!-- new: AI integrations toggle + panel (developer helper) -->}
<div class="ai-toggle-wrapper">
	<button id="aiToggleBtn" class="ai-toggle">AI Integrations</button>
	<section id="aiPanel" class="ai-panel" aria-hidden="true" style="display:none;">
		<h3>Recommended AI features for Beach ERP</h3>
		<ul>
			<li><strong>Analytics & Summaries</strong> — use OpenAI (Chat/summaries) or a dedicated analytics job to generate weekly operational summaries.</li>
			<li><strong>Demand Forecasting & Inventory Optimization</strong> — use time-series models (Prophet/ARIMA) or ML service; store forecasts in Postgres for reorder suggestions.</li>
			<li><strong>Semantic Search / Knowledge Base</strong> — generate embeddings (OpenAI/Cohere) and store in pgvector (Supabase) for fast staff queries (SOPs, invoices, suppliers).</li>
			<li><strong>Purchasing Assistant</strong> — LLM assistant suggests suppliers/quantities from inventory, lead times and historical prices.</li>
			<li><strong>Image analysis</strong> — Google Vision / AWS Rekognition for asset damage, beach occupancy photos.</li>
			<li><strong>Sentiment & Feedback</strong> — analyze reviews/feedback with OpenAI or Azure Text Analytics.</li>
			<li><strong>Notifications & Automation</strong> — Twilio (SMS), SendGrid (email), Stripe for payments; trigger via Laravel queued jobs.</li>
			<li><strong>Vector DB options</strong> — Supabase pgvector (recommended if using Supabase) or Pinecone for production-scale vector search.</li>
			<li><strong>Security</strong> — keep API keys server-side (Laravel .env), use background jobs for calls, and RLS if frontend accesses Supabase directly.</li>
		</ul>
		<p style="font-size:0.9rem; color:#555">Next steps: enable pgvector on Supabase, add a Laravel queued Job to generate embeddings, store them in a vector column, expose endpoints for analytics.</p>
	</section>
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

// new: AI panel toggle
document.addEventListener('DOMContentLoaded', function () {
	const btn = document.getElementById('aiToggleBtn');
	const panel = document.getElementById('aiPanel');
	btn?.addEventListener('click', function () {
		if (panel.style.display === 'none' || panel.style.display === '') {
			panel.style.display = 'block';
			panel.setAttribute('aria-hidden', 'false');
			btn.textContent = 'Hide AI Integrations';
		} else {
			panel.style.display = 'none';
			panel.setAttribute('aria-hidden', 'true');
			btn.textContent = 'AI Integrations';
		}
	});
});
</script>

</body>
</html>
