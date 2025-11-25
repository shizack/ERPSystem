@extends('layouts.app')

@section('title', 'My Requisitions')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">My Requisitions</h1>
            <a href="{{ route('employee.requisitions.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                New Requisition
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($requisitions->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500">You haven't submitted any requisitions yet.</p>
                <a href="{{ route('employee.requisitions.create') }}" 
                   class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                    Create your first requisition
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-4 text-left">Product</th>
                            <th class="py-3 px-4 text-left">Quantity</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Date Requested</th>
                            <th class="py-3 px-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requisitions as $requisition)
                            <tr class="border-t border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    {{ $requisition->product->name ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4">{{ $requisition->quantity }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ][$requisition->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 text-xs rounded-full {{ $statusClasses }}">
                                        {{ ucfirst($requisition->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">{{ $requisition->created_at->format('M d, Y h:i A') }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('employee.requisitions.show', $requisition) }}" 
                                       class="text-blue-600 hover:text-blue-800 mr-3">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $requisitions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
