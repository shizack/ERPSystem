<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Recent Requisitions</h3>
    </div>
    <div class="divide-y divide-gray-200">
        @forelse($requisitions as $requisition)
            <div class="px-6 py-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-blue-600 truncate">
                            {{ $requisition->product->name ?? 'Product Not Found' }}
                        </p>
                        <div class="flex items-center mt-1 text-sm text-gray-500">
                            <span>Requested by {{ $requisition->requester->name }} • {{ $requisition->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        @if($requisition->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Approved
                            </span>
                        @elseif($requisition->status == 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Rejected
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
                @if($requisition->admin_notes)
                    <div class="mt-2 text-sm text-gray-500">
                        <span class="font-medium">Notes:</span> {{ $requisition->admin_notes }}
                    </div>
                @endif
            </div>
        @empty
            <div class="px-6 py-4 text-center text-gray-500">
                No recent requisitions found.
            </div>
        @endforelse
    </div>
    <div class="px-6 py-3 bg-gray-50 text-right">
        <a href="{{ route('admin.requisitions.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
            View all requisitions <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
</div>
