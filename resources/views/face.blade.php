<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Attendance</title>
    <script defer src="{{ asset('js/face-api.min.js') }}"></script>
    <script defer src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <section id="halaman-pilih" class="page-view">
        <div class="welcome-container">
            <div class="welcome-logo"></div>
            <h1>Sistem Absensi Wajah</h1>
            <p>Silahkan pilih jenis absensi Anda untuk melanjutkan</p>

            <div class="menu-absen">
                <button class="btn-absen datang" onclick="pilihTipeAbsen('Datang')">
                    <span class="icon">📥</span>
                    <span class="text">Absen Datang</span>
                </button>
                <button class="btn-absen pulang" onclick="pilihTipeAbsen('Pulang')">
                    <span class="icon">📤</span>
                    <span class="text">Absen Pulang</span>
                </button>
            </div>
        </div>
    </section>

    <section id="halaman-kamera" class="page-view d-none">
        <header class="header">
            <div class="logo-area">
                <div class="logo"></div>
                <h2>Face Recognition Attendance</h2>
            </div>
            <div class="header-right">
                <span id="tanggal"></span>
            </div>
        </header>

        <main class="container">
            <section class="left-panel">
                <div class="camera-title">
                    Kamera Deteksi Wajah (<span id="mode-aktif">-</span>)
                </div>
                <div class="video-wrapper">
                    <video id="video" autoplay muted></video>
                </div>
                <div class="camera-status">
                    Kamera Aktif
                </div>
            </section>

            <section class="right-panel">
                <div class="card-info">
                    <h2>Informasi Kehadiran</h2>
                    <div class="info-item">
                        <label>Tipe Absen</label>
                        <div class="value" id="info-tipe">-</div>
                    </div>
                    <div class="info-item">
                        <label>Nama</label>
                        <div class="value" id="nama">-</div>
                    </div>
                    <div class="info-item">
                        <label>Status</label>
                        <div class="value status hadir" id="status">
                            Belum Terdeteksi
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Waktu</label>
                        <div class="value" id="waktu">-</div>
                    </div>
                </div>
                <div class="log-box">
                    <h3>Log Kehadiran</h3>
                    <ul id="log-list"></ul>
                </div>
            </section>
        </main>

        <footer class="footer">
            Face Recognition System © 2026
        </footer>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>