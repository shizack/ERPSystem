@extends('layouts.admin')

@section('content')
<style>
    .suppliers-wrapper {
        background: #ffffff;
        padding: 25px 30px;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        margin-top: 10px;
    }
    .suppliers-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .add-btn { background:#007bff; padding:6px 20px; color:#fff !important; border-radius:10px; font-weight:600; text-decoration:none; transition:.2s; display:inline-block; }
    .add-btn:hover { background:#005fcc; }
    .table-card { width:100%; border-collapse:collapse; }
    .table-card thead { background:#f5f7fb; }
    .table-card th { padding:14px; font-size:14px; color:#555; font-weight:600; border-bottom:1px solid #e5e7eb; }
    .table-card td { padding:14px; font-size:14px; color:#333; border-bottom:1px solid #f0f0f0; }
    .badge-status { padding:6px 12px; border-radius:12px; font-size:12px; font-weight:600; color:#fff; }
    .badge-active { background:#2ecc71; }
    .badge-inactive { background:#6c757d; }
    .btn-action { border:none; padding:6px 10px; border-radius:6px; font-weight:600; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; cursor:pointer; }
    .btn-view { background:#17a2b8; color:#fff; }
    .btn-view:hover { background:#138496; }
    .btn-edit { background:#ffca28; color:#000; }
    .btn-edit:hover { background:#ffb300; }
    .btn-delete { background:#e74c3c; color:#fff; }
    .btn-delete:hover { background:#c0392b; }
    .empty-row { text-align:center; padding:40px 0; color:#777; }
    .actions-cell { white-space:nowrap; }
    .pagination { margin-top:15px; }
    .pagination a, .pagination span { padding:8px 12px; margin:0 3px; background:#fff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08); font-size:13px; color:#007bff; text-decoration:none; }
    .confirm-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); display:none; align-items:center; justify-content:center; z-index:100; }
    .confirm-box { background:#fff; padding:24px 28px; border-radius:16px; width:340px; box-shadow:0 6px 28px rgba(0,0,0,0.18); }
    .confirm-box h4 { margin:0 0 12px; font-size:18px; font-weight:700; }
    .confirm-box p { margin:0 0 18px; font-size:14px; color:#444; }
    .confirm-actions { display:flex; gap:10px; justify-content:flex-end; }
    .btn-cancel { background:#6c757d; color:#fff; }
    .btn-cancel:hover { background:#5a6268; }
    .btn-confirm { background:#e74c3c; color:#fff; }
    .btn-confirm:hover { background:#c0392b; }
</style>

<div class="suppliers-wrapper">
    <div class="suppliers-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <h2>Suppliers</h2>
        <a href="{{ route('admin.suppliers.create') }}" class="add-btn">+ Add Supplier</a>
    </div>

    <table class="table-card mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                    <td><a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></td>
                    <td>{{ $supplier->phone ?? 'N/A' }}</td>
                    <td>
                        @if($supplier->is_active)
                            <span class="badge-status badge-active">Active</span>
                        @else
                            <span class="badge-status badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="actions-cell">
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('admin.suppliers.show', $supplier) }}" class="btn-action btn-view">View</a>
                            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn-action btn-edit">Edit</a>
                            <button type="button" class="btn-action btn-delete delete-supplier" data-id="{{ $supplier->id }}">Delete</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-row">No suppliers found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $suppliers->links() }}
</div>

<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <h4>Delete Supplier</h4>
        <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
        <div class="confirm-actions">
            <button type="button" class="btn-action btn-cancel" id="cancelDelete">Cancel</button>
            <form id="deleteForm" method="POST" action="" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-confirm">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('confirmOverlay');
    const deleteForm = document.getElementById('deleteForm');
    document.querySelectorAll('.delete-supplier').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            deleteForm.action = `{{ url('/admin/suppliers') }}/${id}`;
            overlay.style.display = 'flex';
        });
    });
    document.getElementById('cancelDelete').addEventListener('click', () => {
        overlay.style.display = 'none';
    });
    overlay.addEventListener('click', (e) => {
        if(e.target === overlay){ overlay.style.display = 'none'; }
    });
});
</script>
@endpush
