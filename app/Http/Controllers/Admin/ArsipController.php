<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\Meeting;
use App\Models\Notulensi;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index()
    {
        $arsips = Arsip::with(['meeting', 'notulensi'])->latest()->paginate(20);
        return view('admin.arsips.index', compact('arsips'));
    }

    public function create()
    {
        $meetings = Meeting::whereDoesntHave('arsip')->get();
        $notulensis = Notulensi::all();
        return view('admin.arsips.create', compact('meetings', 'notulensis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'notulensi_id' => 'nullable|exists:notulensis,id',
            'tanggal_arsip' => 'required|date',
        ]);

        Arsip::create($validated);

        return redirect()->route('admin.arsips.index')
            ->with('success', 'Arsip berhasil dibuat.');
    }

    public function show(Arsip $arsip)
    {
        $arsip->load(['meeting', 'notulensi']);
        return view('admin.arsips.show', compact('arsip'));
    }

    public function edit(Arsip $arsip)
    {
        $arsip->load(['meeting', 'notulensi']);
        $meetings = Meeting::all();
        $notulensis = Notulensi::all();
        return view('admin.arsips.edit', compact('arsip', 'meetings', 'notulensis'));
    }

    public function update(Request $request, Arsip $arsip)
    {
        $validated = $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'notulensi_id' => 'nullable|exists:notulensis,id',
            'tanggal_arsip' => 'required|date',
        ]);

        $arsip->update($validated);

        return redirect()->route('admin.arsips.index')
            ->with('success', 'Arsip berhasil diperbarui.');
    }

    public function destroy(Arsip $arsip)
    {
        $arsip->delete();

        return redirect()->route('admin.arsips.index')
            ->with('success', 'Arsip berhasil dihapus.');
    }
}
