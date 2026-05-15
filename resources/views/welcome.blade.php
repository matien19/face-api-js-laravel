<!DOCTYPE html>
<html>
<head>
    <title>Presensi Face Recognition</title>
    <script async src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.11.0"></script>
    <script async src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd@2.2.2"></script>
    <script async src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <style>
        * { margin: 0; padding: 0; }
        body { display: flex; flex-direction: column; align-items: center; font-family: sans-serif; padding: 20px; background: #f0f0f0; }
        h2 { margin-bottom: 20px; color: #333; }
        
        .video-container { 
            position: relative; 
            width: 600px; 
            height: 450px; 
            margin: 20px 0;
            border: 2px solid #333;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
        }
        
        #video { 
            width: 100%;
            height: 100%;
            display: block;
        }
        
        #canvas { 
            position: absolute; 
            top: 0; 
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .info-panel {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-width: 300px;
            margin: 20px 0;
        }
        
        #status { 
            font-weight: bold; 
            color: #0066cc;
            margin-bottom: 10px;
        }
        
        .face-info {
            margin: 10px 0;
            padding: 10px;
            background: #e8f4f8;
            border-left: 4px solid #0066cc;
            display: none;
        }
        
        .face-info.show { display: block; }
        
        .face-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .label {
            font-weight: bold;
            color: #333;
        }
        
        button {
            padding: 10px 20px;
            cursor: pointer;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            margin: 0 5px;
            transition: background 0.3s;
        }
        
        button:hover { background: #0052a3; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        
        .button-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <h2>🎥 Deteksi Wajah & Umur</h2>
    
    <div class="video-container">
        <video id="video" autoplay muted playsinline></video>
        <canvas id="canvas"></canvas>
    </div>

    <div class="info-panel">
        <p id="status">Memuat Model...</p>
        <div class="face-info" id="faceInfo">
            <p><span class="label">Umur (Estimasi):</span> <span id="ageValue">-</span> tahun</p>
            <p><span class="label">Jenis Kelamin:</span> <span id="genderValue">-</span></p>
            <p><span class="label">Kepercayaan Diri:</span> <span id="confidenceValue">-</span>%</p>
        </div>
    </div>

    <div class="button-group">
        <button id="btn-scan" onclick="captureAndVerify()">📸 Absen Sekarang</button>
        <button id="btn-toggle" onclick="toggleDetection()">⏸️ Hentikan Deteksi</button>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const status = document.getElementById('status');
        const faceInfo = document.getElementById('faceInfo');
        let detectionActive = true;
        let ageDetector = null;

        canvas.width = video.videoWidth || 600;
        canvas.height = video.videoHeight || 450;

        // Load models
        async function loadModels() {
            try {
                await Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                    faceapi.nets.ageGenderNet.loadFromUri('/models')
                ]);
                status.innerText = "✓ Model Siap! Hidupkan Kamera...";
                startVideo();
            } catch (err) {
                status.innerText = "⚠️ Models not found locally, using CDN...";
                await Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights'),
                    faceapi.nets.ageGenderNet.loadFromUri('https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights')
                ]);
                status.innerText = "✓ Model Siap! Hidupkan Kamera...";
                startVideo();
            }
        }

        // Wait for all scripts to load
        const checkAndLoad = setInterval(() => {
            if (typeof faceapi !== 'undefined') {
                clearInterval(checkAndLoad);
                loadModels();
            }
        }, 100);

        function startVideo() {
            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 600 },
                    height: { ideal: 450 }
                }
            })
                .then(stream => {
                    video.srcObject = stream;
                    video.onloadedmetadata = () => {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        status.innerText = "✓ Kamera Aktif! Hadap ke Kamera...";
                        detectFaces();
                    };
                })
                .catch(err => {
                    status.innerText = "❌ Akses Kamera Ditolak: " + err.message;
                });
        }

        async function detectFaces() {
            if (!detectionActive) {
                requestAnimationFrame(detectFaces);
                return;
            }

            try {
                // Deteksi semua wajah di frame
                const detections = await faceapi
                    .detectAllFaces(video)
                    .withFaceLandmarks()
                    .withAgeAndGender();

                // Clear canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                if (detections.length > 0) {
                    status.innerText = `✓ Terdeteksi ${detections.length} Wajah`;
                    faceInfo.classList.add('show');

                    detections.forEach((detection, index) => {
                        drawDetection(detection);
                        
                        // Tampilkan info wajah pertama
                        if (index === 0) {
                            const age = Math.round(detection.age);
                            const gender = detection.gender;
                            const confidence = Math.round(detection.genderProbability * 100);
                            
                            document.getElementById('ageValue').innerText = age;
                            document.getElementById('genderValue').innerText = gender === 'male' ? 'Laki-laki' : 'Perempuan';
                            document.getElementById('confidenceValue').innerText = confidence;
                        }
                    });
                } else {
                    status.innerText = "⚠️ Wajah tidak terdeteksi. Silakan hadap ke kamera...";
                    faceInfo.classList.remove('show');
                }
            } catch (err) {
                console.error('Detection error:', err);
            }

            requestAnimationFrame(detectFaces);
        }

        function drawDetection(detection) {
            const { x, y, width, height } = detection.detection.box;
            const age = Math.round(detection.age);
            const gender = detection.gender;

            // Draw border box
            ctx.strokeStyle = '#00ff00';
            ctx.lineWidth = 3;
            ctx.strokeRect(x, y, width, height);

            // Draw filled background for text
            ctx.fillStyle = 'rgba(0, 255, 0, 0.8)';
            ctx.fillRect(x, y - 35, 150, 35);

            // Draw text
            ctx.fillStyle = '#000000';
            ctx.font = 'bold 16px Arial';
            ctx.fillText(`Umur: ${age} tahun`, x + 5, y - 15);
            ctx.fillText(gender === 'male' ? '👨 Laki-laki' : '👩 Perempuan', x + 5, y + 5);
        }

        function toggleDetection() {
            detectionActive = !detectionActive;
            const btn = document.getElementById('btn-toggle');
            btn.innerText = detectionActive ? '⏸️ Hentikan Deteksi' : '▶️ Lanjutkan Deteksi';
        }

        async function captureAndVerify() {
            const btn = document.getElementById('btn-scan');
            btn.disabled = true;
            status.innerText = "🔄 Memverifikasi Wajah...";

            try {
                const detection = await faceapi.detectSingleFace(video)
                    .withFaceLandmarks()
                    .withFaceDescriptor()
                    .withAgeAndGender();

                if (detection) {
                    const age = Math.round(detection.age);
                    const gender = detection.gender;

                    status.innerText = `✓ Wajah Terdeteksi - Umur: ${age} tahun`;

                    // Kirim ke Laravel
                    fetch('/verify-face', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                            descriptor: Object.values(detection.descriptor),
                            age: age,
                            gender: gender
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message + (data.user ? "\nNama: " + data.user + "\nUmur Terdeteksi: " + age + " tahun" : ""));
                        status.innerText = "✓ Selesai. Silakan Scan Ulang...";
                    })
                    .catch(err => {
                        status.innerText = "❌ Error: " + err.message;
                    });
                } else {
                    alert("⚠️ Wajah tidak terlihat jelas!");
                    status.innerText = "❌ Gagal. Silakan coba lagi...";
                }
            } catch (err) {
                status.innerText = "❌ Error: " + err.message;
            } finally {
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>