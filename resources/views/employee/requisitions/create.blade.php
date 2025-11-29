@extends('layouts.employee')

@section('title', 'Create New Requisition')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create New Requisition</h1>
        <a href="{{ route('employee.requisitions.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Back to Requisitions
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Requisition Details</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Fill in the details below to create a new requisition.</p>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            @include('employee.requisition.create')
        </div>
    </div>
</div>
@endsection
