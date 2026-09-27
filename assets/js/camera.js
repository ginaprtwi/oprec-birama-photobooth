const video = document.querySelector('#camera-preview');
const canvas = document.querySelector('#capture-canvas');
const countdown = document.querySelector('#countdown');
const startButton = document.querySelector('#start-capture');
const statusText = document.querySelector('#status-text');
const progressText = document.querySelector('#photo-progress');
const stepNumber = document.querySelector('#step-number');
const videoWrap = document.querySelector('.video-wrap');

let stream;
let capturedPhotos = [];

async function openCamera() {
    try {
        const permissionStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        permissionStream.getTracks().forEach((track) => track.stop());

        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter((device) => device.kind === 'videoinput');
        const eosCamera = videoDevices.find((device) => /obs virtual camera/i.test(device.label))
            || videoDevices.find((device) => /eos|canon|webcam utility/i.test(device.label));
        const videoConstraints = eosCamera
            ? { deviceId: { exact: eosCamera.deviceId } }
            : { facingMode: 'user' };

        stream = await navigator.mediaDevices.getUserMedia({ video: videoConstraints, audio: false });
        video.srcObject = stream;
        startButton.disabled = false;
        statusText.textContent = 'Kamera siap. Saatnya tersenyum.';
    } catch (error) {
        statusText.textContent = 'Kamera tidak dapat diakses. Izinkan kamera lalu refresh halaman.';
        statusText.parentElement.classList.add('has-error');
    }
}

function wait(milliseconds) {
    return new Promise((resolve) => setTimeout(resolve, milliseconds));
}

async function runCountdown() {
    for (let number = 3; number > 0; number -= 1) {
        countdown.textContent = number;
        await wait(1000);
    }
    countdown.textContent = '';
    await wait(350);
    videoWrap.classList.add('capture-flash');
    await wait(220);
    videoWrap.classList.remove('capture-flash');
}

function capturePhoto() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const context = canvas.getContext('2d');
    context.translate(canvas.width, 0);
    context.scale(-1, 1);
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    return canvas.toDataURL('image/png');
}

async function startCapture() {
    startButton.disabled = true;
    capturedPhotos = [];

    for (let index = 0; index < 3; index += 1) {
        stepNumber.textContent = `02.${index + 1}`;
        statusText.textContent = `Bersiap untuk foto ${index + 1}...`;
        await runCountdown();
        capturedPhotos.push(capturePhoto());
        progressText.textContent = `${capturedPhotos.length} / 3 photos`;
        await wait(600);
    }

    statusText.textContent = 'Selesai! Menyiapkan hasil foto...';
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
    }

    const response = await fetch('save_photos.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ photos: capturedPhotos })
    });

    if (response.ok) {
        window.location.href = 'result.php';
    } else {
        statusText.textContent = 'Gagal menyimpan foto. Silakan coba lagi.';
        startButton.disabled = false;
    }
}

startButton.addEventListener('click', startCapture);
openCamera();
