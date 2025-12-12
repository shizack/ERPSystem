@extends('layouts.admin')

@section('title', 'Create User')

@section('breadcrumbs')
    {{-- Breadcrumbs omitted for legacy layout; using unified design system --}}
@endsection

@section('content')
<div style="display:flex; justify-content:center; align-items:center; min-height:80vh; padding:16px;">
<div class="card" style="max-width:1400px; width:100%; margin:0 auto; background:#ffffff; border-radius:18px; box-shadow:0 8px 28px rgba(0,0,0,0.10);">
    <div class="card-header" style="padding:20px 24px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0; font-size:1.4rem; font-weight:800; color:#0b2540;">Create User</h2>
        <a href="{{ route('admin.dashboard') }}" style="text-decoration:none; color:#2f6bff; font-weight:700; display:flex; align-items:center; gap:6px;">
            <i class="material-icons" style="font-size:20px;">home</i> Back to Dashboard
        </a>
    </div>

    <div class="card-body" style="padding:36px;">
        @if(session('success'))
            <div class="alert" style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:12px 14px; border-radius:10px; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                <i class="material-icons" style="color:#10b981">check_circle</i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-row" style="margin-bottom:14px;">
                <label for="full_name" style="display:block; font-weight:700; color:#334155; margin-bottom:6px;">Full Name</label>
                  <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required
                      style="width:100%; padding:14px 16px; border:1px solid #e5e7eb; border-radius:12px; font-size:16px;">
                @error('full_name')<div style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-row" style="margin-bottom:14px;">
                <label for="email" style="display:block; font-weight:700; color:#334155; margin-bottom:6px;">Email</label>
                  <input id="email" name="email" type="email" value="{{ old('email') }}" required
                      style="width:100%; padding:14px 16px; border:1px solid #e5e7eb; border-radius:12px; font-size:16px;">
                @error('email')<div style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-row" style="margin-bottom:14px;">
                <label for="password" style="display:block; font-weight:700; color:#334155; margin-bottom:6px;">Password</label>
                <input id="password" name="password" type="password" required
                       style="width:100%; padding:14px 16px; border:1px solid #e5e7eb; border-radius:12px; font-size:16px;">
                @error('password')<div style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-grid" style="display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:22px;">
                <div>
                    <label for="role" style="display:block; font-weight:700; color:#334155; margin-bottom:6px;">Role</label>
                    <select id="role" name="role" required style="width:100%; padding:14px 16px; border:1px solid #e5e7eb; border-radius:12px; font-size:16px;">
                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="Manager" {{ old('role') === 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="General Manager" {{ old('role') === 'General Manager' ? 'selected' : '' }}>General Manager</option>
                    </select>
                    @error('role')<div style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label for="status" style="display:block; font-weight:700; color:#334155; margin-bottom:6px;">Status</label>
                    <select id="status" name="status" required style="width:100%; padding:14px 16px; border:1px solid #e5e7eb; border-radius:12px; font-size:16px;">
                        <option value="ACTIVE" {{ old('status') === 'ACTIVE' ? 'selected' : '' }}>ACTIVE</option>
                        <option value="INACTIVE" {{ old('status') === 'INACTIVE' ? 'selected' : '' }}>INACTIVE</option>
                    </select>
                    @error('status')<div style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div id="employee-department-wrap" class="form-row" style="margin-top:14px; display:none;">
                <label for="department" style="display:block; font-weight:700; color:#334155; margin-bottom:6px;">Department</label>
                <select id="department" name="department" style="width:100%; padding:14px 16px; border:1px solid #e5e7eb; border-radius:12px; font-size:16px;">
                    <option value="" disabled {{ old('department') ? '' : 'selected' }}>Select Department</option>
                    <option value="Front Desk Clerk" {{ old('department') === 'Front Desk Clerk' ? 'selected' : '' }}>Front Desk Clerk</option>
                    <option value="Cook" {{ old('department') === 'Cook' ? 'selected' : '' }}>Cook</option>
                    <option value="Assistant Cook" {{ old('department') === 'Assistant Cook' ? 'selected' : '' }}>Assistant Cook</option>
                    <option value="Waiter" {{ old('department') === 'Waiter' ? 'selected' : '' }}>Waiter</option>
                    <option value="Maintenance" {{ old('department') === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="House Keeping" {{ old('department') === 'House Keeping' ? 'selected' : '' }}>House Keeping</option>
                    <option value="Life Guard" {{ old('department') === 'Life Guard' ? 'selected' : '' }}>Life Guard</option>
                </select>
                @error('department')<div style="color:#dc2626; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="padding-top:10px;">
                <button type="submit" style="display:inline-flex; align-items:center; gap:10px; background:#2f6bff; color:#fff; border:0; padding:14px 20px; border-radius:12px; font-weight:800; font-size:16px;">
                    <i class="material-icons" style="font-size:20px;">person_add</i>
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const roleSelect = document.getElementById('role');
        const deptWrap = document.getElementById('employee-department-wrap');
        function syncVisibility() {
            if (!roleSelect || !deptWrap) return;
            deptWrap.style.display = roleSelect.value === 'employee' ? 'block' : 'none';
        }
        if (roleSelect) {
            roleSelect.addEventListener('change', syncVisibility);
        }
        // Initialize on load
        syncVisibility();
    })();
</script>
@endpush
