<?php
require 'koneksi.php';
require_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunikasi - SpeechSign AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
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
            <h1 class="page-title">Komunikasi Dua Arah</h1>
            <div></div>
        </header>

        <main class="content">
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="card">
                    <h3 style="font-weight: 600; margin-bottom: 1rem; color: #3b82f6;">Suara ke Teks</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">
                        Klik untuk mulai mendengarkan dan mengubah suara menjadi teks.
                    </p>
                    <button type="button" id="speech-btn" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                        <svg width="1.25rem" height="1.25rem" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                        Mulai Mendengarkan
                    </button>
                    <div id="speech-result" style="background: #f9fafb; padding: 1rem; border-radius: 0.5rem; min-height: 100px; white-space: pre-wrap;">
                        <span style="color: #9ca3af;">Hasil suara akan muncul di sini...</span>
                    </div>
                </div>

                <div class="card">
                    <h3 style="font-weight: 600; margin-bottom: 1rem; color: #8b5cf6;">Teks ke Suara</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">
                        Ketik teks dan klik untuk mendengarkan suara.
                    </p>
                    <textarea id="text-input" class="form-input" rows="4" placeholder="Ketik teks di sini..." style="margin-bottom: 1rem;"></textarea>
                    <button type="button" id="text-btn" class="btn btn-primary" style="width: 100%;">
                        <svg width="1.25rem" height="1.25rem" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                        </svg>
                        Mainkan Suara
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const speechBtn = document.getElementById('speech-btn');
            const speechResult = document.getElementById('speech-result');
            const textBtn = document.getElementById('text-btn');
            const textInput = document.getElementById('text-input');

            let recognition = null;
            let isListening = false;

            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();
                recognition.continuous = true;
                recognition.interimResults = true;
                recognition.lang = 'id-ID';

                recognition.onstart = function() {
                    speechBtn.textContent = 'Mendengarkan...';
                    speechBtn.classList.remove('btn-primary');
                    speechBtn.classList.add('btn-danger');
                    isListening = true;
                };

                recognition.onresult = function(event) {
                    let transcript = '';
                    for (let i = event.resultIndex; i < event.results.length; i++) {
                        transcript += event.results[i][0].transcript;
                    }
                    speechResult.textContent = transcript;
                };

                recognition.onerror = function(event) {
                    console.error('Speech recognition error:', event.error);
                    stopListening();
                };

                recognition.onend = function() {
                    if (isListening) {
                        recognition.start();
                    }
                };
            }

            speechBtn.addEventListener('click', function() {
                if (recognition) {
                    if (isListening) {
                        stopListening();
                    } else {
                        recognition.start();
                    }
                } else {
                    alert('Browser tidak mendukung speech recognition');
                }
            });

            function stopListening() {
                isListening = false;
                if (recognition) {
                    recognition.stop();
                }
                speechBtn.innerHTML = `
                    <svg width="1.25rem" height="1.25rem" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                    </svg>
                    Mulai Mendengarkan
                `;
                speechBtn.classList.remove('btn-danger');
                speechBtn.classList.add('btn-primary');
            }

            textBtn.addEventListener('click', function() {
                const text = textInput.value.trim();
                if (text && 'speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';
                    speechSynthesis.speak(utterance);
                }
            });
        });
    </script>
</body>
</html>
