<!-- Header dengan User Info dan Logout Icon -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <!-- User Info Section -->
    <div class="d-flex align-items-center">
        <div class="me-3">
            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User">
        </div>
        <div>
            <small class="text-muted">Selamat datang kembali</small>
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
