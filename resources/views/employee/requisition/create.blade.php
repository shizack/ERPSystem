<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Requisition - Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('{{ asset('images/Mayet Resort (Pillar).jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            min-height: 100vh;
        }

        .global-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px) brightness(0.86);
            z-index: -1;
        }

        .loading-ring {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .refined {
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px #4f46e5;
        }

        .select-arrow {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body class="font-sans">
    <!-- Background overlay -->
    <div class="global-overlay"></div>

    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white bg-opacity-90 backdrop-blur-sm shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4"></div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex items-center justify-center py-12">
            <div class="w-full max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
                    <!-- Form Header -->
                    <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-blue-600">
                        <h2 class="text-3xl font-bold text-white">New Requisition Request</h2>
                        <p class="mt-2 text-indigo-100 text-lg">Request items from inventory for your department</p>
                    </div>

                    <!-- Form Content -->
                    <div class="px-8 py-8">
                        @if(session('success'))
                            <div class="mb-6 p-5 bg-green-50 text-green-700 rounded-lg border border-green-200 text-base">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 p-5 bg-red-50 text-red-700 rounded-lg border border-red-200 text-base">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 p-5 bg-red-50 text-red-700 rounded-lg border border-red-200">
                                <ul class="list-disc pl-5 space-y-1 text-base">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('employee.requisitions.store') }}" class="space-y-8">
                            @csrf

                            <!-- Item Selection -->
                            <div class="space-y-2">
                                <label for="product_id" class="block text-base font-semibold text-gray-700">Select Item</label>
                                <div class="mt-1 relative">
                                    <select id="product_id" name="product_id" required
                                        class="select-arrow appearance-none block w-full px-4 py-3 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- Select an item --</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item['item_id'] }}" 
                                                data-unit="{{ $item['unit'] ?? 'N/A' }}"
                                                data-category="{{ $item['category'] ?? 'N/A' }}"
                                                data-initial-quantity="{{ $item['quantity'] ?? 0 }}">
                                                {{ $item['name'] }} ({{ $item['quantity'] ?? 0 }} {{ $item['unit'] ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3 text-sm text-gray-600 bg-gray-50 p-4 rounded-md">
                                    <p class="mb-1"><strong>Unit:</strong> <span id="item-unit">-</span></p>
                                    <p class="mb-1"><strong>Category:</strong> <span id="item-category">-</span></p>
                                    <p><strong>Current Stock:</strong> <span id="current-stock">-</span></p>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="space-y-2">
                                <label for="quantity" class="block text-base font-semibold text-gray-700">Quantity</label>
                                <input type="number" id="quantity" name="quantity" required min="1" value="{{ old('quantity', 1) }}"
                                    class="mt-1 block w-full px-4 py-3 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description" class="block text-base font-semibold text-gray-700">
                                    Description
                                    <span class="text-sm text-gray-500 font-normal">(What will this be used for?)</span>
                                </label>
                                <textarea id="description" name="description" rows="5" required
                                    class="mt-1 block w-full px-4 py-3 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                                <div class="flex justify-end">
                                    <button type="button" id="refineButton" 
                                        class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <span id="buttonText">Refine with AI</span>
                                        <span id="loadingSpinner" class="hidden loading-ring"></span>
                                    </button>
                                </div>
                                <p id="statusMessage" class="mt-2 text-sm text-gray-500"></p>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('employee.dashboard') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Submit Requisition
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Item details display
            const itemSelect = document.getElementById('product_id');
            const itemUnit = document.getElementById('item-unit');
            const itemCategory = document.getElementById('item-category');
            const currentStock = document.getElementById('current-stock');
            const quantityInput = document.getElementById('quantity');

            // Function to update stock display and validate quantity
            function updateStockDisplay(stock) {
                currentStock.textContent = stock + ' ' + (itemSelect.options[itemSelect.selectedIndex]?.dataset.unit || '');
                
                // Update max attribute of quantity input
                if (quantityInput) {
                    quantityInput.max = stock;
                    if (parseInt(quantityInput.value) > stock) {
                        quantityInput.value = stock;
                    }
                }
            }

            // Function to fetch current stock from server
            async function fetchCurrentStock(productId) {
                if (!productId) {
                    updateStockDisplay('0');
                    return;
                }

                try {
                    const response = await fetch(`/api/inventory/stock/${productId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateStockDisplay(data.quantity);
                    } else {
                        // Fallback to initial quantity if API fails
                        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
                        const initialQuantity = selectedOption?.dataset.initialQuantity || '0';
                        updateStockDisplay(initialQuantity);
                        console.error('Failed to fetch current stock');
                    }
                } catch (error) {
                    console.error('Error fetching stock:', error);
                    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
                    const initialQuantity = selectedOption?.dataset.initialQuantity || '0';
                    updateStockDisplay(initialQuantity);
                }
            }

            function updateItemDetails() {
                const selectedOption = itemSelect.options[itemSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    itemUnit.textContent = selectedOption.dataset.unit || '-';
                    itemCategory.textContent = selectedOption.dataset.category || '-';
                    
                    // Show loading state
                    currentStock.textContent = 'Loading...';
                    
                    // Fetch current stock from server
                    fetchCurrentStock(selectedOption.value);
                } else {
                    itemUnit.textContent = '-';
                    itemCategory.textContent = '-';
                    currentStock.textContent = '-';
                    
                    if (quantityInput) {
                        quantityInput.removeAttribute('max');
                    }
                }
            }

            if (itemSelect) {
                itemSelect.addEventListener('change', updateItemDetails);
                // Initialize with selected value if any
                updateItemDetails();
            }

            // AI Description Refinement
            const refineButton = document.getElementById('refineButton');
            const descriptionTextarea = document.getElementById('description');
            const statusMessage = document.getElementById('statusMessage');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const buttonText = document.getElementById('buttonText');

            if (refineButton) {
                refineButton.addEventListener('click', async function() {
                    const description = descriptionTextarea.value.trim();
                    
                    if (description.length < 10) {
                        statusMessage.textContent = 'Please enter at least 10 characters before refining.';
                        statusMessage.className = 'mt-1 text-xs text-red-600';
                        return;
                    }

                    // Show loading state
                    buttonText.textContent = 'Refining...';
                    loadingSpinner.classList.remove('hidden');
                    refineButton.disabled = true;
                    statusMessage.textContent = 'Refining your description...';
                    statusMessage.className = 'mt-1 text-xs text-gray-600';

                    try {
                        const productId = itemSelect.value;
                        const quantity = quantityInput.value;
                        
                        const response = await fetch('{{ route("employee.requisitions.refine_description") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                description: description,
                                product_id: productId,
                                quantity: quantity
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            descriptionTextarea.value = data.refined_text;
                            descriptionTextarea.classList.add('refined');
                            statusMessage.textContent = 'Description refined successfully!';
                            statusMessage.className = 'mt-1 text-xs text-green-600';
                        } else {
                            statusMessage.textContent = data.message || 'Failed to refine description.';
                            statusMessage.className = 'mt-1 text-xs text-red-600';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        statusMessage.textContent = 'An error occurred. Please try again.';
                        statusMessage.className = 'mt-1 text-xs text-red-600';
                    } finally {
                        // Reset button state
                        buttonText.textContent = 'Refine with AI';
                        loadingSpinner.classList.add('hidden');
                        refineButton.disabled = false;
                    }
                });
            }
        });
    </script>
</body>
</html>