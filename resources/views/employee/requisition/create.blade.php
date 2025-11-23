<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Requisition - Employee</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSRF Token for Laravel AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Custom styles for the textarea interaction */
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
<body class="bg-gray-100 font-sans antialiased">

<div class="min-h-screen flex items-start justify-center pt-10">
    <div class="w-full max-w-2xl bg-white p-8 rounded-xl shadow-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">New Purchase Requisition</h1>

        <form method="POST" action="{{ route('employee.requisitions.store') }}">
            @csrf

            <!-- Basic Requisition Fields -->
            <div class="space-y-4 mb-8">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Requisition Title</label>
                    <input type="text" id="title" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>
            </div>

            <!-- AI Refinement Section -->
            <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200 mb-8">
                <h2 class="text-xl font-semibold text-indigo-800 mb-4">Request Description & AI Refinement</h2>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Detailed Description of Needs (Minimum 10 characters)</label>
                    <textarea id="description" name="description" rows="6" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 border resize-y"></textarea>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="button" id="refineButton" disabled class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out disabled:bg-indigo-400">
                        <span id="loadingSpinner" class="loading-ring hidden mr-2"></span>
                        <span id="refineText">Refine Description with AI</span>
                    </button>
                    <p id="statusMessage" class="text-sm font-medium text-gray-500"></p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
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