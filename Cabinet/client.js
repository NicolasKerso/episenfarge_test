let room = "room1"; // Set the room name

const socket = io();
let localStream;
let remoteStream;
const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');
const startButton = document.getElementById('startButton');
const hangupButton = document.getElementById('hangupButton');
const chatInput = document.getElementById('chatInput');
const sendButton = document.getElementById('sendButton');
const chatBox = document.getElementById('chatBox');

startButton.addEventListener('click', startCall);
hangupButton.addEventListener('click', hangupCall);
sendButton.addEventListener('click', sendMessage);

socket.emit('join', room); // Join the room when client connected

function startCall() {
    navigator.mediaDevices.getUserMedia({ video: true, audio: true })
        .then((stream) => {
            localStream = stream;
            localVideo.srcObject = localStream;

            socket.on('remoteStream', (stream) => {
                remoteStream = stream;
                remoteVideo.srcObject = remoteStream;
            });

            socket.on('hangup', () => {
                hangupCall();
            });

            socket.on('chat', (message) => {
                let p = document.createElement('p');
                p.innerText = message;
                chatBox.appendChild(p);
            });

            startButton.disabled = true;
            hangupButton.disabled = false;
        })
        .catch((error) => {
            console.error('Erreur lors de l\'accès à la caméra/microphone :', error);
        });
}

function hangupCall() {
    localStream.getTracks().forEach((track) => track.stop());
    socket.emit('hangup');

    localVideo.srcObject = null;
    remoteVideo.srcObject = null;

    startButton.disabled = false;
    hangupButton.disabled = true;
}

function sendMessage() {
    const message = chatInput.value;
    if (message) {
        socket.emit('chat', room, message);
        chatInput.value = '';
        let p = document.createElement('p');
        p.innerText = 'You: ' + message;
        chatBox.appendChild(p);
    }
}