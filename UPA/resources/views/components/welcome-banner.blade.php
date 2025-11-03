@php
    $role = Auth::user()->role;

    $roleLabel = match($role) {
        'admin' => 'Admin',
        'student' => 'Student',
        'educationStaff', 'staff' => 'Education Staff',
        default => 'User'
    };

    $roleDescription = match($role) {
        'admin' => 'You have full access to manage TOEIC registrations and platform settings.',
        'student' => 'You can register for TOEIC and track your progress here.',
        'educationStaff', 'staff' => 'Staff-level privileges for managing data.',
        default => 'General user access.'
    };
@endphp

<div class="rounded-4 p-4 mb-4"
     style="background: linear-gradient(90deg, #ffecd2 0%, #fcb69f 100%);">

    <h4 class="fw-bold text-dark mb-2">
        Hello, {{ $roleLabel }} {{ $name }} 👋
    </h4>

    <p class="mb-1 text-dark">
        Keep moving 🎯
    </p>

    {{--  <p class="mb-2 text-dark">
        You've completed <strong>{{ $progress }}%</strong> of your tasks. Keep it up!
    </p>  --}}

    <div class="bg-white border rounded-3 p-3 shadow-sm mt-3" style="max-width: 450px;">
        <div class="fw-semibold text-dark mb-1">Role: {{ $roleLabel }}</div>
        <div class="text-muted small">{{ $roleDescription }}</div>
    </div>
</div>
