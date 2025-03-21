const boutonOuvrirCamera = document.getElementById('ouvrirCamera');
const cameraStream = document.getElementById('cameraStream');
const qrCodeResult = document.getElementById('qrCodeResult');
let isScanning = false;

boutonOuvrirCamera.addEventListener('click', () => {
    if (!isScanning) {
        alert("bonjour");
        // Demander l'accès à la caméra de l'utilisateur
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((stream) => {
                // Afficher la vidéo dans l'élément <video>
                cameraStream.srcObject = stream;
                cameraStream.style.display = 'block';
                boutonOuvrirCamera.style.display = 'none';

                // Démarrer la détection des QR codes
                const video = document.createElement('video');
                const canvasElement = document.createElement('canvas');
                const canvas = canvasElement.getContext('2d');

                const checkQRCode = () => {
                    canvasElement.width = video.videoWidth;
                    canvasElement.height = video.videoHeight;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);

                    // Spécifiez le type de code à rechercher (QR_CODE)
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        format: 'QR_CODE', // Spécifiez le type de code à rechercher
                        inversionAttempts: 'dontInvert',
                    });

                    if (code) {
                        qrCodeResult.textContent = 'Enregistrement en cours...';
                        isScanning = true;

                        // Envoi du contenu du QR code au backend via une requête AJAX
                        fetch('{{ route('eglises.save.scanner.qrcode') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Incluez le jeton CSRF
                            },
                            body: JSON.stringify({ content: code.data }),
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.message === 'Présence enregistrée avec succès.' || data.message === 'Vous avez déjà pointé.' || data.message === 'Vous n\'êtes pas encore enregistré.') {
                                    // Fermer la caméra immédiatement après un scan réussi ou pas
                                    stream.getTracks().forEach((track) => {
                                        track.stop();
                                    });
                                    cameraStream.style.display = 'none';
                                    boutonOuvrirCamera.style.display = 'block';
                                }
                                qrCodeResult.textContent = data.message;
                                isScanning = false;
                            })
                            .catch((error) => {
                                qrCodeResult.textContent = 'Une erreur s\'est produite lors de l\'enregistrement.';
                                console.error(error);
                                isScanning = false;
                            });
                    } else {
                        requestAnimationFrame(checkQRCode);
                    }
                };

                video.srcObject = stream;
                video.setAttribute('playsinline', true);
                video.play();

                video.addEventListener('loadedmetadata', () => {
                    canvasElement.width = video.videoWidth;
                    canvasElement.height = video.videoHeight;
                    requestAnimationFrame(checkQRCode);
                });
            })
            .catch((error) => {
                console.error('Erreur lors de l\'accès à la caméra :', error);
            });
    }
});