<?php
require 'koneksi.php';
require_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bahasa Isyarat - SpeechSign AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <button id="open-sidebar" class="sidebar-toggle">
                <svg width="1.5rem" height="1.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="page-title">Bahasa Isyarat</h1>
            <div></div>
        </header>

        <main class="content">
            <div class="grid" style="grid-template-columns: 1fr; gap: 1.5rem;">
                <div class="card">
                    <h3 style="font-weight: 600; margin-bottom: 1rem;">Deteksi Bahasa Isyarat</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">
                        Izinkan akses kamera untuk mulai mendeteksi gerakan tangan.
                    </p>
                    
                    <div class="video-container">
                        <video id="video" autoplay playsinline style="display: block; width: 100%;"></video>
                        <canvas id="canvas" style="position: absolute; top: 0; left: 0; width: 100%;"></canvas>
                    </div>

                    <div style="margin-top: 1.5rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem;">
                        <p style="font-weight: 500; margin-bottom: 0.5rem;">Terdeteksi:</p>
                        <p id="detected-text" style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">-</p>
                    </div>
                </div>

                <div class="card">
                    <h3 style="font-weight: 600; margin-bottom: 1rem;">Panduan</h3>
                    <ul style="color: #4b5563; line-height: 1.8;">
                        <li>Pastikan cahaya cukup untuk mendeteksi tangan</li>
                        <li>Letakkan tangan di depan kamera dengan jelas</li>
                        <li>Gerakan tangan harus terlihat penuh dalam frame</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script src="<?php echo asset_url('js/script.js'); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            const detectedText = document.getElementById('detected-text');

            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;

                await new Promise(resolve => {
                    video.onloadedmetadata = () => {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        resolve();
                    };
                });

                if (window.Hands) {
                    const hands = new Hands({
                        locateFile: (file) => {
                            return `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`;
                        }
                    });

                    hands.setOptions({
                        maxNumHands: 2,
                        modelComplexity: 1,
                        minDetectionConfidence: 0.7,
                        minTrackingConfidence: 0.5
                    });

                    hands.onResults((results) => {
                        ctx.save();
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(results.image, 0, 0, canvas.width, canvas.height);

                        if (results.multiHandLandmarks) {
                            for (const landmarks of results.multiHandLandmarks) {
                                if (window.drawConnectors && window.drawLandmarks) {
                                    drawConnectors(ctx, landmarks, HAND_CONNECTIONS, { color: '#00FF00', lineWidth: 2 });
                                    drawLandmarks(ctx, landmarks, { color: '#FF0000', lineWidth: 1, radius: 3 });
                                }
                            }

                            if (results.multiHandLandmarks.length > 0) {
                                detectedText.textContent = 'Tangan terdeteksi!';
                            } else {
                                detectedText.textContent = '-';
                            }
                        }
                        ctx.restore();
                    });

                    if (window.Camera) {
                        const camera = new Camera(video, {
                            onFrame: async () => {
                                await hands.send({ image: video });
                            },
                            width: 640,
                            height: 480
                        });
                        camera.start();
                    }
                }
            } catch (err) {
                console.error('Gagal mengakses kamera:', err);
                detectedText.textContent = 'Gagal mengakses kamera';
            }
        });
    </script>
</body>
</html>
