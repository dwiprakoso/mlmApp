<!-- Header dengan User Info dan Logout Icon -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <!-- User Info Section -->
    <div class="d-flex align-items-center">
        <div class="me-3">
            <img src="{{ $appLogo ?? 'https://via.placeholder.com/40' }}" class="rounded-circle" alt="User"
                style="width: 40px; height: 40px; object-fit: cover;">
        </div>
        <div>
            <div class="d-flex align-items-center">
                <span style="color: var(--gold-color); font-size: 16px; font-weight: 500;">
                    {{ $legalName ?? 'My Application' }}
                </span>
            </div>
            <!-- ✅ Info User Login -->
            @if ($currentUser)
                <small class="text-muted d-block" style="font-size: 12px;">
                    {{ $userPhone ?? ($userEmail ?? 'No Contact Info') }}
                </small>
            @endif
        </div>
    </div>

    <!-- Logout Icon Button -->
    <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" class="btn btn-link text-light p-2" title="Sign Out"
            style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 8px;">
            <i class="bi bi-box-arrow-right d-block fs-5"></i>
        </button>
    </form>
</div>
