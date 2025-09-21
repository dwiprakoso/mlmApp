<!DOCTYPE html>
<html lang="id">

<head>
    <base href="{{ url('/') }}/" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="mobile-container">
        <!-- Hero -->
        <div class="hero-card">
            <div class="d-flex align-items-center mb-3">
                <div class="me-2">
                    <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User">
                </div>
                <div>
                    <small class="text-muted">Selamat datang kembali</small>
                    <h5 class="mb-0 text-white">Solusi pintar untuk uang pintar.</h5>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <i class="bi bi-shield-fill-check text-gold"></i>
                    <i class="bi bi-house-door-fill text-gold"></i>
                    <i class="bi bi-bell-fill position-relative text-gold">
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span>
                    </i>
                </div>
            </div>

            <div class="balance-card text-center">
                <h6 class="mb-1 text-muted">Saldo akun</h6>
                <h3 class="fw-bold text-gold">Rp 75,000</h3>
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <button class="btn btn-gold btn-sm">Deposit</button>
                    <button class="btn btn-outline-gold btn-sm">Penarikan</button>
                </div>
            </div>
        </div>

        <!-- Bantuan & Perawatan -->
        <div class="content-section">
            <h6 class="text-white mb-3">Bantuan & Perawatan</h6>
            <div class="row text-center">
                <div class="col">
                    <div class="circle-icon bg-secondary text-white">
                        <i class="bi bi-headset"></i>
                    </div>
                    <small class="text-muted">Layanan Online</small>
                </div>
                <div class="col">
                    <div class="circle-icon bg-success text-white">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <small class="text-muted">WhatsApp</small>
                </div>
                <div class="col">
                    <div class="circle-icon bg-gold text-dark">
                        <i class="bi bi-question-lg"></i>
                    </div>
                    <small class="text-muted">Mandiri</small>
                </div>
            </div>
        </div>

        <!-- Kartu Undangan -->
        <div class="content-section">
            <div class="card-dark shadow-sm p-3">
                <h6 class="text-white mb-2">Kartu undangan saya</h6>
                <p class="small text-muted">Undangan yang berhasil akan memberi Anda hak untuk komisi <span
                        class="text-gold fw-bold">35%</span> pada setiap investasi.</p>
                <div class="d-flex justify-content-between text-center mb-3">
                    <div>
                        <i class="bi bi-qr-code fs-3 text-gold"></i><br>
                        <small class="text-muted">QR</small>
                    </div>
                    <div>
                        <i class="bi bi-link-45deg fs-3 text-gold"></i><br>
                        <small class="text-muted">Tautan</small>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-gold">ZKL9S</h5>
                        <small class="text-muted">Kode</small>
                    </div>
                </div>
                <button class="btn btn-gold w-100">Lihat tim saya</button>
            </div>
        </div>

        <!-- Bottom Navbar -->
        <div class="bottom-nav">
            <a href="#" class="nav-item active">
                <i class="bi bi-house-door-fill d-block fs-5"></i>
                <span>Home</span>
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-graph-up d-block fs-5"></i>
                <span>Investasi</span>
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-gift-fill d-block fs-5"></i>
                <span>Bonus</span>
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-wallet-fill d-block fs-5"></i>
                <span>Dompetku</span>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
