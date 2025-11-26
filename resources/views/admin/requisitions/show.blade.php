@extends('layouts.admin')

@section('title', 'Requisition Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Requisition #{{ $requisition->req_id }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.requisitions.all') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="material-icons mr-1" style="font-size: 16px;">arrow_back</i>
                    Back to List
                </a>
            </div>
        </div>
        <div class="mt-1 flex items-center text-sm text-gray-500">
            <span class="mr-2">Status:</span>
            @if($requisition->status == \App\Models\Requisition::STATUS_PENDING)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Pending
                </span>
            @elseif($requisition->status == \App\Models\Requisition::STATUS_APPROVED)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Approved
                </span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Rejected
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Requisition Details
            </h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Product Requested
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $requisition->product->name }}
                        @if(isset($requisition->product->current_quantity))
                            <span class="text-gray-500">(Current Stock: {{ $requisition->product->current_quantity }})</span>
                        @endif
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Quantity Requested
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $requisition->quantity }}
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Requested By
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $requisition->requester->first_name }} {{ $requisition->requester->last_name }}
                        <span class="text-gray-500">({{ $requisition->requester->email }})</span>
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Requested On
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $requisition->created_at->format('F j, Y g:i A') }}
                        <span class="text-gray-500">({{ $requisition->created_at->diffForHumans() }})</span>
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Description
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $requisition->description ?? 'No description provided.' }}
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Product Code
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $requisition->product->product_code ?? 'N/A' }}
                    </dd>
                </div>
                
                @if($requisition->status != \App\Models\Requisition::STATUS_PENDING)
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ $requisition->status == \App\Models\Requisition::STATUS_APPROVED ? 'Approved' : 'Rejected' }} By
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $requisition->approver->name }}
                            <span class="text-gray-500">({{ $requisition->updated_at->format('F j, Y g:i A') }})</span>
                        </dd>
                    </div>
                    @if($requisition->status == \App\Models\Requisition::STATUS_REJECTED && $requisition->reason_for_rejection)
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Reason for Rejection
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $requisition->reason_for_rejection }}
                            </dd>
                        </div>
                    @endif
                    @if($requisition->admin_notes)
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Admin Notes
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $requisition->admin_notes }}
                            </dd>
                        </div>
                    @endif
                @endif
            </dl>
        </div>
    </div>

    @if($requisition->status == \App\Models\Requisition::STATUS_PENDING)
        <div class="mt-6 bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Process Requisition</h3>
                <div class="mt-5">
                    <div class="rounded-md bg-blue-50 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    @if(isset($requisition->product->current_quantity))
                                        Current Stock: {{ $requisition->product->current_quantity }}
                                    @else
                                        Current Stock: N/A
                                    @endif
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Requested: {{ $requisition->quantity }} {{ $requisition->product->unit ?? 'unit' }}{{ $requisition->quantity != 1 ? 's' : '' }}</p>
                                    @if(isset($requisition->product->current_quantity) && $requisition->product->current_quantity < $requisition->quantity)
                                        <p class="mt-1 font-medium">Note: Approving will result in negative inventory.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('admin.requisitions.approve', ['requisition' => $requisition->req_id]) }}" class="flex-1">
                            @csrf
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full">
                                Approve Requisition
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.requisitions.reject', ['requisitionId' => $requisition->req_id]) }}" class="flex-1">
                            @csrf
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Rejection <span class="text-red-500">*</span></label>
                                <input type="text" id="reason" name="reason" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="reject_notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <textarea id="reject_notes" name="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 w-full">
                                Reject Requisition
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
