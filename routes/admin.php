<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\NotulensiController;
use App\Http\Controllers\Admin\RekamanAudioController;
use App\Http\Controllers\Admin\TranskripController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::middleware('user.permission:AdminAccessDashboard')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    });

    // Users
    Route::middleware('user.permission:AdminAccessUsers')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Roles
    Route::middleware('user.permission:AdminAccessRoles')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Meetings (Full CRUD)
    Route::middleware('user.permission:AdminAccessMeetings')->group(function () {
        Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
        Route::get('meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
        Route::post('meetings', [MeetingController::class, 'store'])->name('meetings.store');
        Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
        Route::get('meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
        Route::put('meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
        Route::delete('meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    });

    // Agendas
    Route::middleware('user.permission:AdminAccessAgendas')->group(function () {
        Route::get('agendas', [AgendaController::class, 'index'])->name('agendas.index');
        Route::put('agendas/{meeting}', [AgendaController::class, 'update'])->name('agendas.update');
        Route::delete('agendas/{meeting}', [AgendaController::class, 'destroy'])->name('agendas.destroy');
    });

    // Riwayat Meeting
    Route::middleware('user.permission:AdminAccessArsips')->group(function () {
        Route::get('riwayat-meeting', [AdminController::class, 'riwayatMeeting'])->name('riwayat-meeting.index');
        Route::delete('riwayat-meeting/{meeting}', [AdminController::class, 'destroyRiwayatMeeting'])->name('riwayat-meeting.destroy');
    });

    // Transkrip (CRUD)
    Route::middleware('user.permission:AdminAccessArsips')->group(function () {
        Route::get('transkrips', [TranskripController::class, 'index'])->name('transkrips.index');
        Route::get('transkrips/create', [TranskripController::class, 'create'])->name('transkrips.create');
        Route::post('transkrips', [TranskripController::class, 'store'])->name('transkrips.store');
        Route::get('transkrips/{transkrip}', [TranskripController::class, 'show'])->name('transkrips.show');
        Route::get('transkrips/{transkrip}/edit', [TranskripController::class, 'edit'])->name('transkrips.edit');
        Route::put('transkrips/{transkrip}', [TranskripController::class, 'update'])->name('transkrips.update');
        Route::delete('transkrips/{transkrip}', [TranskripController::class, 'destroy'])->name('transkrips.destroy');
    });

    // Rekaman Audio
    Route::middleware('user.permission:AdminAccessRekamanAudio')->group(function () {
        Route::get('rekaman-audio', [RekamanAudioController::class, 'index'])->name('rekaman-audio.index');
        Route::get('rekaman-audio/{rekaman}/play', [RekamanAudioController::class, 'play'])->name('rekaman-audio.play');
        Route::delete('rekaman-audio/{rekaman}', [RekamanAudioController::class, 'destroy'])->name('rekaman-audio.destroy');
    });

    // Rekaman Video
    Route::middleware('user.permission:AdminAccessRekamanAudio')->group(function () {
        Route::get('rekaman-video', [RekamanAudioController::class, 'videoIndex'])->name('rekaman-video.index');
        Route::get('rekaman-video/{rekaman}/stream', [RekamanAudioController::class, 'stream'])->name('rekaman-video.stream');
        Route::get('rekaman-video/{rekaman}/download', [RekamanAudioController::class, 'download'])->name('rekaman-video.download');
        Route::delete('rekaman-video/{rekaman}', [RekamanAudioController::class, 'destroy'])->name('rekaman-video.destroy');
    });

    // Notulensi Riwayat
    Route::middleware('user.permission:AdminAccessMeetings')->group(function () {
        Route::get('notulensis', [NotulensiController::class, 'index'])->name('notulensis.index');
        Route::get('notulensis/{notulensi}', [NotulensiController::class, 'show'])->name('notulensis.show');
        Route::get('notulensis/{notulensi}/pdf', [NotulensiController::class, 'downloadPdf'])->name('notulensis.pdf');
        Route::get('notulensis/{notulensi}/edit', [NotulensiController::class, 'edit'])->name('notulensis.edit');
        Route::put('notulensis/{notulensi}', [NotulensiController::class, 'update'])->name('notulensis.update');
        Route::delete('notulensis/{notulensi}', [NotulensiController::class, 'destroy'])->name('notulensis.destroy');
    });

    // Profile (tetap di layout admin)
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::delete('profile/photo', [AdminController::class, 'deletePhoto'])->name('profile.deletePhoto');
    Route::put('profile/password', [AdminController::class, 'updatePassword'])->name('profile.password');
    Route::delete('profile', [AdminController::class, 'destroy'])->name('profile.destroy');

});
