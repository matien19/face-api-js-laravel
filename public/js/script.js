const video = document.getElementById('video');

const namaEl = document.getElementById('nama');
const statusEl = document.getElementById('status');
const waktuEl = document.getElementById('waktu');
const logList = document.getElementById('log-list');

const jokowiAudio = new Audio('./audio/lawan.mp3');
const prabowoAudio = new Audio('./audio/wowo.mp3');

const DETECTION_DELAY = 3 * 60 * 1000; // 3 menit
let lastDetection = {};

Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri('./models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('./models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('./models')
]).then(startVideo);

function startVideo() {
    navigator.mediaDevices.getUserMedia({
        video: true,
        audio: false
    })
        .then((stream) => {
            video.srcObject = stream;
        })
        .catch((err) => {
            console.error(err);
        });
}

async function getLabeledFaceDescriptors() {

    const response = await fetch('/face-descriptors');
    const data = await response.json();
    return data.map(user => {

        const descriptors =
            user.descriptors.map(desc => {
                return new Float32Array(
                    JSON.parse(desc)
                );
            });

        return {
            user_id: user.user_id,
            label: user.label,
            descriptors: new faceapi.LabeledFaceDescriptors(
                user.label,
                descriptors
            )
        };
    });
}


video.addEventListener('play', async () => {

    const users =
        await getLabeledFaceDescriptors();

    // AMBIL DESCRIPTOR FACEAPI
    const labeledFaceDescriptors =
        users.map(u => u.descriptors);

    const faceMatcher =
        new faceapi.FaceMatcher(
            labeledFaceDescriptors,
            0.4
        );

    const canvas =
        faceapi.createCanvasFromMedia(video);

    document.querySelector('.video-wrapper')
        .append(canvas);

    const displaySize = {
        width: video.offsetWidth,
        height: video.offsetHeight
    };

    faceapi.matchDimensions(
        canvas,
        displaySize
    );

    setInterval(async () => {

        const detections =
            await faceapi
                .detectAllFaces(
                    video,
                    new faceapi.SsdMobilenetv1Options()
                )
                .withFaceLandmarks()
                .withFaceDescriptors();

        const resizedDetections =
            faceapi.resizeResults(
                detections,
                displaySize
            );

        const ctx =
            canvas.getContext('2d');

        ctx.clearRect(
            0,
            0,
            canvas.width,
            canvas.height
        );

        faceapi.draw.drawDetections(
            canvas,
            resizedDetections
        );

        const results =
            resizedDetections.map((d) => {
                return faceMatcher.findBestMatch(
                    d.descriptor
                );
            });

        for (const [i, result] of results.entries()) {

            const box =
                resizedDetections[i].detection.box;

            const drawBox =
                new faceapi.draw.DrawBox(box, {
                    label: result.toString()
                });

            drawBox.draw(canvas);

            // JIKA WAJAH DIKENALI
            if (result.label !== 'unknown') {

                const user =
                    users.find(
                        u => u.label === result.label
                    );

                if (!user) continue;

                const now = Date.now();

                // JEDA 3 MENIT

                if (
                    lastDetection[user.user_id] &&
                    now - lastDetection[user.user_id] < DETECTION_DELAY
                ) {
                    continue;
                }

                lastDetection[user.user_id] = now;

                try {

                    // KIRIM KE SERVER
                    const response = await fetch('/face/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: user.user_id
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Presensi Berhasil',
                            text: `${data.nama} (${data.status.toUpperCase()})`,
                            timer: 2500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: data.message,
                            timer: 2500,
                            showConfirmButton: false
                        });

                    }

                    // UPDATE PANEL KANAN
                    namaEl.innerText =
                        result.label.toUpperCase();

                    statusEl.innerText =
                        data.status.toUpperCase();

                    waktuEl.innerText =
                        new Date().toLocaleTimeString('id-ID');

                    // LOG KEHADIRAN
                    const li =
                        document.createElement('li');

                    li.innerHTML = `
                        <strong>${result.label.toUpperCase()}</strong>
                        - ${data.status.toUpperCase()}
                        (${new Date().toLocaleTimeString('id-ID')})
                    `;

                    logList.prepend(li);

                    // AUDIO
                    // if (result.label === 'Jokowi') {
                    //     jokowiAudio.play();
                    // }

                    // if (result.label === 'prabowo') {
                    //     prabowoAudio.play();
                    // }

                } catch (error) {

                    console.log(error);

                }

            }

        }

    }, 500);

});

// TANGGAL HEADER
setInterval(() => {

    const now = new Date();

    document.getElementById('tanggal')
        .innerText =
        now.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

}, 1000);