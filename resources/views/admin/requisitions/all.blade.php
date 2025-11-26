@extends('layouts.admin')

@section('title', 'All Requisitions')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">All Requisitions</h1>
        <div class="space-x-2">
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Filter Requisitions</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <form method="GET" action="{{ route('admin.requisitions.all') }}" class="p-4">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Statuses</option>
                            <option value="{{ \App\Models\Requisition::STATUS_PENDING }}" {{ request('status') == \App\Models\Requisition::STATUS_PENDING ? 'selected' : '' }}>Pending</option>
                            <option value="{{ \App\Models\Requisition::STATUS_APPROVED }}" {{ request('status') == \App\Models\Requisition::STATUS_APPROVED ? 'selected' : '' }}>Approved</option>
                            <option value="{{ \App\Models\Requisition::STATUS_REJECTED }}" {{ request('status') == \App\Models\Requisition::STATUS_REJECTED ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Apply Filters
                        </button>
                        @if(request()->has('status'))
                            <a href="{{ route('admin.requisitions.all') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($requisitions->isEmpty())
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No requisitions found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->has('status'))
                        Try adjusting your filters or <a href="{{ route('admin.requisitions.all') }}" class="text-indigo-600 hover:text-indigo-500">clear all filters</a>.
                    @else
                        No requisitions have been created yet.
                    @endif
                </p>
            </div>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($requisitions as $requisition)
                    <li>
                        <a href="{{ route('admin.requisitions.show', $requisition) }}" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        {{ $requisition->item->name }}
                                        <span class="text-gray-500">x{{ $requisition->quantity }}</span>
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
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
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $requisition->requester->first_name }} {{ $requisition->requester->last_name }}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $requisition->created_at->format('M j, Y g:i A') }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        @if($requisition->status != \App\Models\Requisition::STATUS_PENDING)
                                            <span class="mr-2">
                                                {{ $requisition->status == \App\Models\Requisition::STATUS_APPROVED ? 'Approved' : 'Rejected' }} by {{ $requisition->approver->name }}
                                            </span>
                                        @endif
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $requisition->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $requisitions->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
