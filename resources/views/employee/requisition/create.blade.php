<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Requisition - Employee</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('{{ asset('images/Mayet Resort (Pillar).jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
        }

        /* Blur + brightness dim overlay */
        .global-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px) brightness(0.86);
            z-index: -1;
        }

        #description {
            transition: all 0.3s ease;
        }

        .loading-ring {
            display: inline-block;
            width: 20px;
            height: 20px;
        }

        .loading-ring:after {
            content: " ";
            display: block;
            width: 16px;
            height: 16px;
            margin: 2px;
            border-radius: 50%;
            border: 2px solid #fff;
            border-color: #fff transparent #fff transparent;
            animation: loading-ring 1.2s linear infinite;
        }

        @keyframes loading-ring {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="font-sans">
    
    <!-- Background overlay -->
    <div class="global-overlay"></div>

    <div class="min-h-screen flex items-start justify-center pt-16 pb-10 px-6">


        <!-- MAIN FORM CARD -->
        <div class="w-full max-w-3xl bg-white/80 backdrop-blur-xl p-10 rounded-2xl shadow-xl border border-gray-100">

            <!-- BACK BUTTON (UPPER RIGHT INSIDE CONTAINER) -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('employee.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl 
                        bg-gray-200/70 text-gray-700 font-medium shadow 
                        hover:bg-gray-300 transition">

                    <svg xmlns="http://www.w3.org/2000/svg" 
                        fill="none" viewBox="0 0 24 24" stroke-width="2" 
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>

                    Back
                </a>
            </div>

            <!-- PAGE HEADER -->
            <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight mb-4">
                Create New Requisition
            </h1>
            <p class="text-gray-500 mb-8 text-lg">
                Fill out the form below to submit a purchase request.
            </p>

            <form method="POST" action="{{ route('employee.requisitions.store') }}">
                @csrf

                <!-- TITLE FIELD -->
                <div class="mb-8">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Requisition Title
                    </label>
                    <input type="text" id="title" name="title" required
                        class="w-full p-3 rounded-xl border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                </div>

                <!-- AI REFINEMENT SECTION -->
                <div class="bg-blue-50/60 border border-blue-200 p-6 rounded-2xl shadow-md mb-10">

                    <h2 class="text-xl font-semibold text-blue-800 mb-4">
                        Description & AI Assistance
                    </h2>

                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Detailed Description (min. 10 characters)
                    </label>

                    <textarea id="description" name="description" rows="6" required
                        class="w-full p-4 rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition resize-y"></textarea>

                    <div class="flex items-center justify-between mt-5">

                        <button type="button" id="refineButton" disabled
                            class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl shadow bg-blue-600 text-white hover:bg-blue-700 disabled:bg-blue-300 transition">

                            <span id="loadingSpinner" class="loading-ring hidden mr-1"></span>
                            <span id="refineText">Refine with AI</span>
                        </button>

                        <p id="statusMessage" class="text-sm font-semibold text-gray-500"></p>
                    </div>
                </div>

                <!-- SUBMIT BTN -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-7 py-3 text-base font-semibold rounded-xl shadow bg-green-600 text-white hover:bg-green-700 transition">
                        Submit Requisition
                    </button>
                </div>
            </form>
        </div>
    </div>



<script>
    const refineButton = document.getElementById('refineButton');
    const descriptionTextarea = document.getElementById('description');
    const statusMessage = document.getElementById('statusMessage');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const refineText = document.getElementById('refineText');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Enable/disable the button based on text length
    descriptionTextarea.addEventListener('input', () => {
        const minLength = 10;
        refineButton.disabled = descriptionTextarea.value.length < minLength;
        if (descriptionTextarea.value.length < minLength) {
            statusMessage.textContent = `Type at least ${minLength - descriptionTextarea.value.length} more characters to enable AI refinement.`;
            statusMessage.className = 'text-sm font-medium text-red-500';
        } else {
            // Clear status message if valid
            if (!loadingSpinner.classList.contains('hidden')) {
                // If loading, don't clear the loading message
            } else if (statusMessage.textContent.startsWith('Type at least')) {
                statusMessage.textContent = '';
            }
        }
    });

    // Initial check for button state
    document.addEventListener('DOMContentLoaded', () => {
        descriptionTextarea.dispatchEvent(new Event('input'));
    });


    refineButton.addEventListener('click', async () => {
        const rawDescription = descriptionTextarea.value;

        // 1. Set Loading State & Disable Button
        refineButton.disabled = true;
        refineText.textContent = 'Refining...';
        loadingSpinner.classList.remove('hidden');
        descriptionTextarea.classList.add('opacity-75');
        statusMessage.textContent = 'Contacting AI service... Please wait.';
        statusMessage.className = 'text-sm font-semibold text-indigo-600';


        try {
            // 2. Make AJAX Request
            const response = await fetch("{{ route('employee.requisitions.refine_description') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ description: rawDescription })
            });

            const data = await response.json();

            // 3. Process Response
            if (response.ok && data.success) {
                // Update textarea with the refined text
                descriptionTextarea.value = data.refined_text;

                // Update status message
                statusMessage.textContent = data.message || 'Description successfully refined!';
                statusMessage.className = 'text-sm font-semibold text-green-600';

                // Re-enable button if new content is still long enough
                refineButton.disabled = data.refined_text.length < 10;
            } else {
                // Handle application-level errors (e.g., AI Service unavailable)
                statusMessage.textContent = `AI Error: ${data.message || 'The AI service returned an error.'}`;
                statusMessage.className = 'text-sm font-semibold text-orange-600';
                // Note: The textarea value remains the original input (fallback provided in controller)
                refineButton.disabled = false;
            }

        } catch (error) {
            console.error('Fetch Error:', error);
            statusMessage.textContent = 'A network or critical error occurred. Check console.';
            statusMessage.className = 'text-sm font-semibold text-red-600';
            refineButton.disabled = false;
        } finally {
            // 5. Reset Loading State
            refineText.textContent = 'Refine Description with AI';
            loadingSpinner.classList.add('hidden');
            descriptionTextarea.classList.remove('opacity-75');

            // Ensure the button is enabled if the resulting text is valid
            if (descriptionTextarea.value.length >= 10) {
                 refineButton.disabled = false;
            } else {
                 refineButton.disabled = true;
            }
        }
    });
</script>

</body>
</html>