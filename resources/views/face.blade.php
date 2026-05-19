<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Attendance</title>
    <script defer src="{{ asset('js/face-api.min.js') }}"></script>
    <script defer src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header class="header">
        <div class="logo-area">
            <div class="logo"></div>
            <h2>Face Recognition Attendance</h2>
        </div>
        <div class="header-right">
            <span id="tanggal"></span>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="container">
        <!-- LEFT -->
        <section class="left-panel">
            <div class="camera-title">
                Kamera Deteksi Wajah
            </div>
            <div class="video-wrapper">
                <video id="video" autoplay muted></video>
            </div>
            <div class="camera-status">
                Kamera Aktif
            </div>
        </section>

        <!-- RIGHT -->
        <section class="right-panel">
            <div class="card-info">
                <h2>Informasi Kehadiran</h2>
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

    <!-- FOOTER -->
    <footer class="footer">
        Face Recognition System © 2026
    </footer>
</body>

</html>