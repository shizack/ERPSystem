@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Requisitions</h2>
                    <a href="{{ route('admin.requisitions.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                        Export
                    </a>
                </div>

                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.requisitions.index') }}" class="px-4 py-2 rounded-md {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            All
                        </a>
                        @foreach($statuses as $status)
                            <a href="{{ route('admin.requisitions.index', ['status' => $status]) }}" class="px-4 py-2 rounded-md {{ request('status') === $status ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                {{ ucfirst($status) }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Requisitions List -->
                @if($requisitions->count() > 0)
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
                                                    @if($requisition->status === \App\Models\Requisition::STATUS_PENDING)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Pending
                                                        </span>
                                                    @elseif($requisition->status === \App\Models\Requisition::STATUS_APPROVED)
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
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $requisition->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            @if($requisition->status === \App\Models\Requisition::STATUS_APPROVED && $requisition->approver)
                                                <div class="mt-2 text-sm text-gray-500">
                                                    <span class="font-medium">Approved by:</span> {{ $requisition->approver->full_name }}
                                                </div>
                                            @elseif($requisition->status === \App\Models\Requisition::STATUS_REJECTED)
                                                <div class="mt-2 text-sm text-red-600">
                                                    <span class="font-medium">Reason:</span> {{ $requisition->reason_for_rejection ?? 'No reason provided' }}
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $requisitions->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No requisitions found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            There are currently no requisitions to display.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
</li>