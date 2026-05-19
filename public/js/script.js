const video = document.getElementById('video');

const namaEl = document.getElementById('nama');
const statusEl = document.getElementById('status');
const waktuEl = document.getElementById('waktu');
const logList = document.getElementById('log-list');

const jokowiAudio = new Audio('./audio/lawan.mp3');
const prabowoAudio = new Audio('./audio/wowo.mp3');

let lastDetection = '';

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

function getLabeledFaceDescriptors() {

    const labels = [
        'messi',
        'ronaldo',
        'tien',
        'jokowi',
        'prabowo'
    ];

    return Promise.all(
        labels.map(async (label) => {

            const descriptions = [];

            for (let i = 1; i <= 2; i++) {

                const img = await faceapi.fetchImage(
                    `./label/${label}/${i}.jpg`
                );

                const detections = await faceapi
                    .detectSingleFace(img)
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                descriptions.push(
                    detections.descriptor
                );
            }

            return new faceapi.LabeledFaceDescriptors(
                label,
                descriptions
            );
        })
    );
}

video.addEventListener('play', async () => {

    const labeledFaceDescriptors =
        await getLabeledFaceDescriptors();

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

        results.forEach((result, i) => {

            const box =
                resizedDetections[i].detection.box;

            const drawBox =
                new faceapi.draw.DrawBox(box, {
                    label: result.toString()
                });

            drawBox.draw(canvas);

            // UPDATE INFO
            if (result.label !== 'unknown') {

                namaEl.innerText =
                    result.label.toUpperCase();

                statusEl.innerText =
                    'HADIR';

                waktuEl.innerText =
                    new Date().toLocaleTimeString(
                        'id-ID'
                    );

                // LOG KEHADIRAN
                if (lastDetection !== result.label) {

                    const li =
                        document.createElement('li');

                    li.innerHTML = `
                        ${result.label.toUpperCase()}
                        - HADIR
                        (${new Date().toLocaleTimeString('id-ID')})
                    `;

                    logList.prepend(li);

                    lastDetection = result.label;
                }

                // AUDIO
                if (result.label === 'jokowi') {
                    jokowiAudio.play();
                }

                if (result.label === 'prabowo') {
                    prabowoAudio.play();
                }

            }

        });

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