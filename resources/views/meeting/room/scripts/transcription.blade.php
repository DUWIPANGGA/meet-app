const sidebarPulse = document.getElementById('sidebarPulse');
const sidebarStatusIndicator = document.getElementById('sidebarStatusIndicator');
const transcribeStatusEl = document.getElementById('transcribeStatus');
const showNotulensiBtn = document.getElementById('showNotulensiBtn');
const notulensiModal = document.getElementById('notulensiModal');

// Live transcription vars
let liveTranscriptionActive = false;
let isWhisperSocketOpen = false;
let whisperSocket = null;
let whisperRequestQueue = []; // [{userId, name}] - tracks which speaker's PCM is being processed
let lastSpeakerId = null;
let lastMessageElement = null;

const participantTranscribers = new Map();
// key: identity (string)
// value: { audioContext, processor, source, pcmBuffer,
//          silenceFrames, isSpeaking, wasSpeaking, userId, name }

function createTrackTranscriber(identity, userId, name) {
    if (participantTranscribers.has(identity)) return participantTranscribers.get(identity);
    const state = {
        audioContext: null,
        source: null,
        processor: null,
        pcmBuffer: [],
        silenceFrames: 0,
        isSpeaking: false,
        wasSpeaking: false,
        userId: userId,
        name: name,
        identity: identity
    };
    participantTranscribers.set(identity, state);
    return state;
}

function removeTrackTranscriber(identity) {
    const state = participantTranscribers.get(identity);
    if (!state) return;
    if (state.processor) try {
        state.processor.disconnect();
    } catch (e) {}
    if (state.source) try {
        state.source.disconnect();
    } catch (e) {}
    if (state.audioContext) try {
        state.audioContext.close();
    } catch (e) {}
    participantTranscribers.delete(identity);
    if (lastSpeakerId === state.userId) {
        lastSpeakerId = null;
        lastMessageElement = null;
    }
}

function removeAllTranscribers() {
    for (const identity of participantTranscribers.keys()) {
        removeTrackTranscriber(identity);
    }
}

// ======================== LIVE TRANSCRIPTION ========================
async function connectWhisperSocket() {
    return new Promise((resolve, reject) => {
        if (whisperSocket && whisperSocket.readyState === WebSocket.OPEN) {
            resolve();
            return;
        }
        whisperRequestQueue = [];
        whisperSocket = new WebSocket(whisperWsUrl);
        whisperSocket.binaryType = 'arraybuffer';
        whisperSocket.onopen = () => {
            isWhisperSocketOpen = true;
            updateSidebarStatus('Socket terbuka', 'text-emerald-400', 'bg-emerald-500');
            resolve();
        };
        whisperSocket.onclose = () => {
            isWhisperSocketOpen = false;
            updateSidebarStatus('Koneksi putus', 'text-amber-400', 'bg-amber-500');
        };
        whisperSocket.onerror = (err) => {
            reject(err);
        };
        whisperSocket.onmessage = (event) => {
            try {
                const queued = whisperRequestQueue.shift();
                const data = JSON.parse(event.data);
                if (data.status === 'success' && data.text && data.text.trim() !== '') {
                    appendTranscriptMessage(queued.userId, queued.name, data.text.trim());
                    syncTranscriptToLaravel(data.text.trim(), queued.userId, queued.name);
                }
            } catch (e) {
                console.error(e);
            }
        };
    });
}

function sendAccumulatedPcmForSpeaker(state) {
    if (state.pcmBuffer.length === 0) return;
    if (!whisperSocket || whisperSocket.readyState !== WebSocket.OPEN) {
        state.pcmBuffer = [];
        return;
    }
    let totalSamples = 0;
    for (let arr of state.pcmBuffer) totalSamples += arr.length;
    const int16Array = new Int16Array(totalSamples);
    let offset = 0;
    for (let floatArr of state.pcmBuffer) {
        for (let i = 0; i < floatArr.length; i++) {
            let s = Math.max(-1, Math.min(1, floatArr[i]));
            int16Array[offset++] = s < 0 ? s * 0x8000 : s * 0x7FFF;
        }
    }
    whisperRequestQueue.push({
        userId: state.userId,
        name: state.name
    });
    whisperSocket.send(int16Array.buffer);
    state.pcmBuffer = [];
}

function startTrackVAD(state) {
    const VAD_THRESHOLD = 0.0002;
    const HANGOVER_FRAMES = 10;

    state.processor.onaudioprocess = (event) => {
        if (!liveTranscriptionActive) return;
        const inputData = event.inputBuffer.getChannelData(0);
        let sum = 0;
        for (let i = 0; i < inputData.length; i++) sum += inputData[i] * inputData[i];
        const energy = sum / inputData.length;

        if (energy > VAD_THRESHOLD) {
            state.isSpeaking = true;
            state.silenceFrames = 0;
        } else {
            state.silenceFrames++;
            if (state.silenceFrames > HANGOVER_FRAMES) state.isSpeaking = false;
        }

        if (state.isSpeaking) {
            state.pcmBuffer.push(new Float32Array(inputData));
            if (!state.wasSpeaking) {
                state.wasSpeaking = true;
                updateSidebarStatus('Mendengarkan (' + state.name + ')', 'text-emerald-400', 'bg-emerald-500');
            }
            if (state.pcmBuffer.length >= 28) sendAccumulatedPcmForSpeaker(state);
        } else {
            if (state.wasSpeaking) {
                state.wasSpeaking = false;
                updateSidebarStatus('Memproses...', 'text-indigo-400', 'bg-indigo-500');
                sendAccumulatedPcmForSpeaker(state);
            }
        }
    };
}

async function startTrackAudioCapture(state, audioTracks) {
    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    state.audioContext = new AudioContextClass({
        sampleRate: 16000
    });
    const stream = new MediaStream(audioTracks);
    state.source = state.audioContext.createMediaStreamSource(stream);
    state.processor = state.audioContext.createScriptProcessor(4096, 1, 1);
    startTrackVAD(state);
    state.source.connect(state.processor);
    state.processor.connect(state.audioContext.destination);
    await state.audioContext.resume();
}

async function startLiveTranscription() {
    await connectWhisperSocket();

    // 1. Process local participant
    if (localStream) {
        const audioTracks = localStream.getAudioTracks();
        if (audioTracks.length > 0 && audioTracks[0].enabled) {
            const state = createTrackTranscriber('local_' + currentUserId, currentUserId, authName);
            await startTrackAudioCapture(state, audioTracks);
        }
    }

    // 2. Process all remote participants
    if (room) {
        room.remoteParticipants.forEach((participant) => {
            const audioPub = participant.getTrackPublication(LiveKit.Track.Source.Microphone);
            if (audioPub && audioPub.track) {
                const tracks = [];
                if (audioPub.track.mediaStream) {
                    audioPub.track.mediaStream.getAudioTracks().forEach(t => tracks.push(t));
                }
                if (tracks.length === 0 && audioPub.track.mediaStreamTrack) {
                    tracks.push(audioPub.track.mediaStreamTrack);
                }
                if (tracks.length > 0) {
                    const identity = participant.identity;
                    const userId = Number(identity) || identity;
                    const displayName = participant.name || identity;
                    const state = createTrackTranscriber(identity, userId, displayName);
                    startTrackAudioCapture(state, tracks);
                }
            }
        });
    }

    liveTranscriptionActive = true;
    updateSidebarStatus('Mendengarkan (Multi)', 'text-emerald-400', 'bg-emerald-500');
}

function stopLiveTranscription() {
    liveTranscriptionActive = false;
    removeAllTranscribers();
    whisperRequestQueue = [];
    lastSpeakerId = null;
    lastMessageElement = null;
    if (whisperSocket && whisperSocket.readyState === WebSocket.OPEN) {
        whisperSocket.close();
        whisperSocket = null;
    }
    isWhisperSocketOpen = false;
    updateSidebarStatus('Mati', 'text-gray-500', 'bg-gray-500');
}
