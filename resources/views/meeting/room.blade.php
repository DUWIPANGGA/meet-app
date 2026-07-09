@extends('layouts.room')

@section('content')
    @include('meeting.room.styles')

    <div id="meetingContainer" class="h-screen flex flex-col relative meeting-bg text-white overflow-hidden font-sans">
        @include('meeting.room.top-bar')
        @include('meeting.room.hidden-elements')

        <!-- Video Grid Area -->
        <div class="flex-1 min-h-0 p-1 md:p-2 pb-24 relative flex flex-col max-h-[90vh]">
            @include('meeting.room.video-grid')
            @include('meeting.room.confirmation-modal')
        </div>

        @include('meeting.room.toolbar')
        @include('meeting.room.participant-sidebar')
        @include('meeting.room.transcript-sidebar')
    </div>

    @include('meeting.room.notulensi-modal')

    <script>
        @include('meeting.room.scripts.config')
        @include('meeting.room.scripts.device-state')
        @include('meeting.room.scripts.audio-monitor')
        @include('meeting.room.scripts.transcription')
        @include('meeting.room.scripts.utils')
        @include('meeting.room.scripts.layout')
        @include('meeting.room.scripts.livekit')
        @include('meeting.room.scripts.screen-share')
        @include('meeting.room.scripts.pin-context')
        @include('meeting.room.scripts.echo')
        @include('meeting.room.scripts.recording')
        @include('meeting.room.scripts.pipeline')
        @include('meeting.room.scripts.events')
        @include('meeting.room.scripts.mobile')
        @include('meeting.room.scripts.init')
    </script>
@endsection
