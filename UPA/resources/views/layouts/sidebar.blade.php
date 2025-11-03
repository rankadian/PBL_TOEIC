<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .nav-link {
        font-size: 14px;
        padding: 8px 12px;
    }

    small {
        font-size: 11px;
        letter-spacing: 0.5px;
    }
</style>

<div class="d-flex flex-column p-3 bg-white shadow-sm" style="width: 240px; min-height: 100vh; padding-bottom: 100px;">
    <!-- Logo -->
    {{--  <h5 class="text-primary fw-bold mb-4">UPA</h5>  --}}
    <img src="https://upload.wikimedia.org/wikipedia/id/4/4a/Logo_Politeknik_Negeri_Malang.png"
     alt="Logo Polinema" class="mb-4" style="width: 50px; height: 50px;">


    <!-- General -->
    <small class="text-uppercase text-muted fw-bold mb-2">General</small>
    <ul class="nav nav-pills flex-column mb-3">
        <li class="nav-item">
            <a href="/dashboard" class="nav-link text-dark {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/announcement" class="nav-link text-dark {{ request()->is('announcement*') ? 'active' : '' }}">
                <i class="bi bi-megaphone me-2"></i> Announcements
            </a>
        </li>
    </ul>

    <!-- TOEIC -->
    @if(auth()->user()->role_name === 'student' || auth()->user()->role_name === 'admin')
        <small class="text-uppercase text-muted fw-bold mb-2 mt-3">TOEIC</small>
        <ul class="nav nav-pills flex-column mb-3">
            @if(auth()->user()->role_name === 'student')
                <li class="nav-item">
                    <a href="/freeRegist" class="nav-link text-dark {{ request()->is('freeRegist*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square me-2"></i> Student verification
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/paid-toeic/register" class="nav-link text-dark {{ request()->is('paid-toeic*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square me-2"></i> Paid Student Register
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/toeic-scores" class="nav-link text-dark {{ request()->is('toeic-scores*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data me-2"></i> Scores
                    </a>
                </li>
            @endif

            @if(auth()->user()->role_name === 'admin')
                <li class="nav-item">
                    <a href="/students" class="nav-link text-dark {{ request()->is('students*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square me-2"></i> Free Student Registrations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('adminRegist.index') }}" class="nav-link text-dark {{ request()->is('adminRegist*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square me-2"></i> Registrations Validation
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/toeic-registration/index" class="nav-link text-dark {{ request()->is('toeic-registration*') ? 'active' : '' }}">
                        <i class="bi bi-pencil-square me-2"></i> Paid Student Registrations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('toeic-scores.index') }}" class="nav-link text-dark {{ request()->is('toeic-scores*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data me-2"></i> Scores
                    </a>
                </li>
            @endif
        </ul>
    @endif

    @if(auth()->user()->role_name === 'educational_staff' )
        <!-- Educational Staff Section -->
        <small class="text-uppercase text-muted fw-bold mb-2 mt-3">Educational Staff</small>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="/educational-staff" class="nav-link text-dark {{ request()->is('educational-staff') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square me-2"></i> Educational Staff Registrations
                </a>
            </li>
        </ul>
    @endif

    @if(auth()->user()->role_name === 'admin')
        <!-- Academic -->
        <small class="text-uppercase text-muted fw-bold mb-2 mt-3">Academic</small>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="/majors" class="nav-link text-dark {{ request()->is('majors*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3 me-2"></i> Majors
                </a>
            </li>
            <li class="nav-item">
                <a href="/study-programs" class="nav-link text-dark {{ request()->is('study-programs*') ? 'active' : '' }}">
                    <i class="bi bi-journal-code me-2"></i> Study Programs
                </a>
            </li>
        </ul>
    @endif

    <!-- Users -->
    <small class="text-uppercase text-muted fw-bold mb-2 mt-3">Profile</small>
    <ul class="nav nav-pills flex-column mb-3">
        <li class="nav-item">
            <a href="/profile" class="nav-link text-dark {{ request()->is('profile*') ? 'active' : '' }}">
                <i class="bi bi-person me-2"></i> User Profile
            </a>
        </li>
    </ul>

    <!-- Logout -->
    <small class="text-uppercase text-muted fw-bold mb-2 mt-3">Out</small>
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a href="#" class="nav-link text-dark"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
