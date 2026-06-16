<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\NotulensiController;
use App\Http\Controllers\Admin\RekamanAudioController;
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

    // Arsips (Full CRUD)
    Route::middleware('user.permission:AdminAccessArsips')->group(function () {
        Route::get('arsips', [ArsipController::class, 'index'])->name('arsips.index');
        Route::get('arsips/create', [ArsipController::class, 'create'])->name('arsips.create');
        Route::post('arsips', [ArsipController::class, 'store'])->name('arsips.store');
        Route::get('arsips/{arsip}', [ArsipController::class, 'show'])->name('arsips.show');
        Route::get('arsips/{arsip}/edit', [ArsipController::class, 'edit'])->name('arsips.edit');
        Route::put('arsips/{arsip}', [ArsipController::class, 'update'])->name('arsips.update');
        Route::delete('arsips/{arsip}', [ArsipController::class, 'destroy'])->name('arsips.destroy');
    });

    // Rekaman Audio
    Route::middleware('user.permission:AdminAccessRekamanAudio')->group(function () {
        Route::get('rekaman-audio', [RekamanAudioController::class, 'index'])->name('rekaman-audio.index');
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
        Route::delete('notulensis/{notulensi}', [NotulensiController::class, 'destroy'])->name('notulensis.destroy');
    });

});
