@extends('layouts.main')
@section('content')
<div class="mb-9">
    <div class="row align-items-center justify-content-between g-3 mb-4">
        <div class="col-auto">
            <h2 class="mb-0">Detail Pengguna</h2>
        </div>

        <div class="col-auto">
            <div class="row g-3">
                <div class="col-auto">
                    <a href="{{ route('md.user') }}" class="btn btn-phoenix-secondary">
                        <span class="fa-solid fa-arrow-left me-2"></span>Kembali
                    </a>
                </div>
                <div class="col-auto">
                    <button class="btn btn-phoenix-danger">
                        <span class="fa-solid fa-trash-can me-2"></span>Delete customer
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-phoenix-secondary">
                        <span class="fas fa-key me-2"></span>Reset password
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5">
        <div class="col-12 col-xxl-4">
            <div class="row g-3 h-100">
                <div class="col-12 col-md-7 col-xxl-12">
                    <div class="card h-100 h-xxl-auto">
                        <div class="card-body d-flex flex-column justify-content-between pb-3">
                            <div class="row align-items-center g-5 mb-3 text-center text-sm-start">
                                <div class="col-12 col-sm-auto mb-sm-2">
                                    <div class="avatar avatar-5xl">
                                        <img class="rounded-circle" id="user-avatar"
                                            src="{{ $user->foto ? asset($user->foto) : asset('label/default.jpg') }}"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-auto flex-1">
                                    <h3>{{ $user->name ?? '-'}}</h3>
                                    <div>
                                        <a class="me-2" href="#!"><span
                                                class="fab fa-linkedin-in text-body-quaternary text-opacity-75 text-primary-hover"></span></a>
                                        <a class="me-2" href="#!"><span
                                                class="fab fa-facebook text-body-quaternary text-opacity-75 text-primary-hover"></span></a>
                                        <a href="#!"><span
                                                class="fab fa-twitter text-body-quaternary text-opacity-75 text-primary-hover"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-between-center border-top border-dashed pt-4"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-xxl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <h3 class="me-1">Default Address</h3>
                                <button class="btn btn-link p-0">
                                    <span class="fas fa-pen fs-8 ms-3 text-body-quaternary"></span>
                                </button>
                            </div>
                            <h5 class="text-body-secondary">Address</h5>
                            <p class="text-body-secondary">{{ $user->alamat ?? '-' }}</p>
                            <div class="mb-3">
                                <h5 class="text-body-secondary">Email</h5>
                                <a href="mailto:{{ $user->email ?? '#' }}">{{ $user->email ?? '-' }}</a>
                            </div>
                            <h5 class="text-body-secondary">Phone</h5>
                            <a class="text-body-secondary" href="tel:{{ $user->phone ?? '#' }}">{{ $user->phone ?? '-'
                                }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-face-smile me-2"></i>Training Face Recognition
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                Silakan lakukan perekaman wajah dari beberapa arah untuk meningkatkan akurasi sistem
                                presensi.
                            </div>

                            <div class="row g-3 mb-4">
                                @php
                                $poses = ['depan', 'kiri', 'kanan', 'atas'];
                                @endphp
                                @foreach($poses as $pose)
                                <div class="col-md-3">
                                    <div class="border rounded p-3 text-center bg-light">
                                        <h6 class="text-capitalize">{{ $pose }}</h6>
                                        <img id="preview-{{ $pose }}"
                                            src="{{ isset($user->faces[$pose]) ? asset($user->faces[$pose]) : asset('label/default.jpg') }}"
                                            class="img-fluid rounded mb-2 style-preview"
                                            style="max-height: 150px; object-fit: cover;">
                                        <br>
                                        <span id="badge-{{ $pose }}"
                                            class="badge {{ isset($user->faces[$pose]) ? 'bg-success' : 'bg-warning' }}">
                                            {{ isset($user->faces[$pose]) ? 'Tersimpan' : 'Belum Ada' }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Progress Training</label>
                                <div class="progress" style="height: 20px">
                                    <div id="training-progress" class="progress-bar bg-success" role="progressbar"
                                        style="width: 0%">
                                        0 / 4 Pose
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" id="btn-start-training">
                                    <i class="fas fa-camera me-2"></i>Mulai Training
                                </button>
                                <button type="button" class="btn btn-warning" id="btn-reset-training">
                                    <i class="fas fa-rotate me-2"></i>Training Ulang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cameraModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perekaman Wajah: <span id="current-pose-title"
                        class="text-capitalize text-primary">Depan</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="btn-close-modal"></button>
            </div>
            <div class="modal-body text-center position-relative">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div
                        style="width: 320px; height: 320px; border-radius: 50%; overflow: hidden; border: 4px solid #3874ff; position: relative;">
                        <video id="webcam" autoplay playsinline muted width="100%" height="100%"
                            style="object-fit: cover; transform: scaleX(-1);"></video>
                    </div>
                </div>
                <p id="camera-instruction" class="fw-bold text-secondary">Posisikan wajah Anda di tengah lingkaran dan
                    menghadap ke depan</p>
                <canvas id="canvas" class="d-none" width="640" height="480"></canvas>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-lg btn-success rounded-pill px-4" id="btn-capture">
                    <i class="fas fa-circle-dot me-2"></i>Ambil Foto
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const poses = ['depan', 'kiri', 'kanan', 'atas'];
        let currentPoseIndex = 0;
        let stream = null;

        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'));
        
        // Update Progress Awal berdasarkan badge yang 'Tersimpan'
        function updateProgressBar() {
            let savedCount = 0;
            poses.forEach(pose => {
                if(document.getElementById(`badge-${pose}`).classList.contains('bg-success')) {
                    savedCount++;
                }
            });
            const percentage = (savedCount / poses.length) * 100;
            const progressBar = document.getElementById('training-progress');
            progressBar.style.width = `${percentage}%`;
            progressBar.innerText = `${savedCount} / ${poses.length} Pose`;
        }
        updateProgressBar();

        // Ambil Instruksi teks dinamis ala Face ID
        function getInstruction(pose) {
            switch(pose) {
                case 'depan': return "Posisikan wajah Anda tepat di tengah lingkaran menghadap ke DEPAN.";
                case 'kiri': return "Tengokkan kepala Anda perlahan ke arah KIRI.";
                case 'kanan': return "Tengokkan kepala Anda perlahan ke arah KANAN.";
                case 'atas': return "Dongakkan kepala Anda perlahan ke arah ATAS.";
                default: return "";
            }
        }

        // Jalankan Kamera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
                video.srcObject = stream;
            } catch (err) {
                alert("Gagal mengakses kamera: " + err.message);
                cameraModal.hide();
            }
        }

        // Hentikan Kamera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }

        // Trigger Buka Modal Kamera
        document.getElementById('btn-start-training').addEventListener('click', function() {
            // Cari pose pertama yang belum tersimpan
            currentPoseIndex = 0;
            for (let i = 0; i < poses.length; i++) {
                if (document.getElementById(`badge-${poses[i]}`).classList.contains('bg-warning')) {
                    currentPoseIndex = i;
                    break;
                }
            }

            if(currentPoseIndex >= poses.length || (currentPoseIndex === 0 && document.getElementById(`badge-${poses[3]}`).classList.contains('bg-success'))) {
                alert("Semua sampel pose wajah sudah terpenuhi!");
                return;
            }

            updateModalUI();
            cameraModal.show();
            startCamera();
        });

        function updateModalUI() {
            const currentPose = poses[currentPoseIndex];
            document.getElementById('current-pose-title').innerText = currentPose;
            document.getElementById('camera-instruction').innerText = getInstruction(currentPose);
        }

        // Ambil Gambar & Kirim ke Backend Laravel via AJAX
        document.getElementById('btn-capture').addEventListener('click', function() {
            const context = canvas.getContext('2d');
            
            // Mirroring canvas agar sama dengan tampilan video
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            context.setTransform(1, 0, 0, 1, 0, 0); // Reset transform

            const dataUrl = canvas.toDataURL('image/jpeg');
            const currentPose = poses[currentPoseIndex];

            // Kirim gambar ke Server
            fetch("{{ route('user.face.store', $user->id ?? 1) }}", { // Sesuaikan ID User Anda
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    // UBAH BARIS DI BAWAH INI:
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify({
                    pose: currentPose,
                    image: dataUrl
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // Update tampilan card pose di background
                    document.getElementById(`preview-${currentPose}`).src = dataUrl;
                    const badge = document.getElementById(`badge-${currentPose}`);
                    badge.classList.remove('bg-warning');
                    badge.classList.add('bg-success');
                    badge.innerText = 'Tersimpan';
                    
                    updateProgressBar();

                    // Lanjut ke pose berikutnya jika ada
                    currentPoseIndex++;
                    if(currentPoseIndex < poses.length) {
                        updateModalUI();
                    } else {
                        alert("Selamat! Semua pose wajah berhasil didaftarkan.");
                        cameraModal.hide();
                        stopCamera();
                    }
                } else {
                    alert("Gagal menyimpan gambar: " + data.message);
                }
            })
            .catch(err => console.error("Error:", err));
        });

        // Hentikan kamera jika modal ditutup manual
        document.getElementById('btn-close-modal').addEventListener('click', stopCamera);
        
        // Reset Training Data
        document.getElementById('btn-reset-training').addEventListener('click', function() {
            if(confirm("Apakah Anda yakin ingin menghapus semua data training wajah user ini?")) {
                fetch("{{ route('user.face.reset', $user->id ?? 1) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        // UBAH BARIS DI BAWAH INI:
                        "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        poses.forEach(pose => {
                            document.getElementById(`preview-${pose}`).src = "{{ asset('label/default.jpg') }}";
                            const badge = document.getElementById(`badge-${pose}`);
                            badge.classList.remove('bg-success');
                            badge.classList.add('bg-warning');
                            badge.innerText = 'Belum Ada';
                        });
                        updateProgressBar();
                        alert("Data training wajah berhasil di-reset.");
                    }
                });
            }
        });
    });
</script>
@endpush