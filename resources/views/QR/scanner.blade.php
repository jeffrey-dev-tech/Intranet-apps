<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>

<div class="camera">
    <video id="video" width="600" height="600" autoplay></video>
</div>

<canvas id="canvas" hidden></canvas>
<p id="result"></p>

<style>
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background-image: url('img/Picture1.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
.camera {
    text-align: left;
    display: block;
    margin-left:230px;
    padding-top:200px;
}
</style>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const resultEl = document.getElementById('result');

let lastQR = null; // Prevent duplicate prints

navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
    .then(stream => { video.srcObject = stream; });

video.addEventListener('play', () => {
    const scan = () => {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                const qrValue = code.data.trim(); // remove spaces
                if (qrValue && qrValue !== lastQR) { // only if not empty and not duplicate
                    lastQR = qrValue;
                    resultEl.textContent = "QR Code: " + qrValue;

                    fetch('/qr-process', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ qr: qrValue })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('Print status:', data);
                    })
                    .catch(err => console.error(err));
                }
            }
        }
        requestAnimationFrame(scan);
    };
    scan();
});
</script>
