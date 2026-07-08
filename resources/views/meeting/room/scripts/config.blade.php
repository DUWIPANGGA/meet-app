// ======================== DEKLARASI VARIABEL ========================
let room = null;
let localStream = null;
let isMuted = false,
    isCameraOff = false,
    audioEnabledByUser = false;
let isScreenSharing = false;
let screenShareStream = null;
let pinnedIdentities = [];
let currentLayout = localStorage.getItem('layout_' + @json($meeting->id)) || 'grid';
let isRecordingScreen = false;
let recordingByOther = false;
let recordingMediaRecorder = null;
let recordingChunks = [];
let recordingCanvasCtx = null;
let recordingRenderTimer = null;
let recordingAudioMixer = null;
let recordingAudioSource = null;
let recordingAudioDestination = null;
let recordingCanvas = null;
let recordingBgCanvas = null;
let recordingThumbCanvas = null;
let recordingParticipants = [];
let recordingSpeakerQueue = [];
let recordingVideoCache = new Map();
let thumbnailsDirty = true;
let bgDirty = true;
let recordingActiveSpeakers = [];
let activeSpeakerIdentity = null;
let spotlightTargetIdentity = null;
const meetingId = @json($meeting->id);
// Load saved device state
try {
    const s = JSON.parse(localStorage.getItem('device_' + meetingId));
    if (s) {
        isMuted = !!s.m;
        isCameraOff = !!s.c;
    }
} catch (e) {}
const currentUserId = Number(@json(auth()->id()));
const authName = @json(auth()->user()?->name ?? 'Anda');
const baseUrl = '/meeting/' + meetingId;
const liveKitUrl = @json($liveKitUrl);
const livekitTokenUrl = baseUrl + '/livekit-token';
const broadcastUrl = baseUrl + '/broadcast';
const leaveUrl = baseUrl + '/leave';
const endUrl = baseUrl + '/end';
const isCreator = @json($isCreator);
const isAdmin = @json($isAdmin);
const saveLiveTranscriptUrl = baseUrl + '/save-live-transcript';
const notulensiPdfUrl = baseUrl + '/notulensi-pdf';

// Dynamic Reverb config & Whisper WS URL
const wsHost = '{{ env("VITE_REVERB_HOST", "meet-bps.my.id") }}';
const isHttps = '{{ env("VITE_REVERB_SCHEME", "https") }}' === 'https';
const whisperWsUrl = (isHttps ? 'wss://' : 'ws://') + wsHost + '/ws/transcribe';
window._REVERB_CONFIG = {
    host: wsHost,
    wsPort: isHttps ? 443 : 8080,
    wssPort: 443,
    scheme: isHttps ? 'https' : 'http',
    key: '{{ env('REVERB_APP_KEY') }}',
    authEndpoint: '/broadcasting/auth',
};

// DOM references
const localVideo = document.getElementById('localVideo');
const remoteVideos = document.getElementById('remoteVideos');
const muteBtn = document.getElementById('muteBtn');
const cameraBtn = document.getElementById('cameraBtn');
const leaveBtn = document.getElementById('leaveBtn');
const startRecordBtn = document.getElementById('startRecordBtn');
const stopUploadBtn = document.getElementById('stopUploadBtn');
const recordScreenBtn = document.getElementById('recordScreenBtn');
const recordActiveDot = document.getElementById('recordActiveDot');
const recordIconDefault = document.getElementById('recordIconDefault');
const recordIconActive = document.getElementById('recordIconActive');
const layoutBtn = document.getElementById('layoutBtn');
const layoutDropdown = document.getElementById('layoutDropdown');
const pdfBtn = document.getElementById('pdfBtn');
const connectionStatusEl = document.getElementById('connectionStatus');
const enableAudioBtn = document.getElementById('enableAudioBtn');
const localAvatar = document.getElementById('localAvatar');
const localAvatarText = document.getElementById('localAvatarText');
const transcriptSidebar = document.getElementById('transcriptSidebar');
const transcriptMessages = document.getElementById('transcriptMessages');
const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
const openSidebarBtn = document.getElementById('openSidebarBtn');

// Remote participant metadata
const remoteParticipants = new Map();
