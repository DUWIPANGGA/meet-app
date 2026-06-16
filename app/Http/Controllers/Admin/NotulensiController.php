<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notulensi;
use Illuminate\Http\Request;

class NotulensiController extends Controller
{
    public function index()
    {
        $notulensis = Notulensi::with(['meeting', 'liveAudio'])
            ->latest()
            ->paginate(20);

        return view('admin.notulensis.index', compact('notulensis'));
    }

    public function show(Notulensi $notulensi)
    {
        $notulensi->load(['meeting', 'liveAudio']);
        return view('admin.notulensis.show', compact('notulensi'));
    }

    public function destroy(Notulensi $notulensi)
    {
        $notulensi->delete();

        return redirect()->route('admin.notulensis.index')
            ->with('success', 'Notulensi berhasil dihapus.');
    }
}
