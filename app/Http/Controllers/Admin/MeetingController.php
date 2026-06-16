<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with('creator')->latest()->paginate(20);
        return view('admin.meetings.index', compact('meetings'));
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['creator', 'participants.user', 'rekamanAudio', 'notulensi', 'agendas']);
        return view('admin.meetings.show', compact('meeting'));
    }

    public function create()
    {
        return view('admin.meetings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rapat' => 'required|string|max:255',
            'deskripsi_rapat' => 'nullable|string|max:5000',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'tipe_rapat' => 'required|in:Online,Offline',
            'link_meeting' => 'nullable|string|max:255',
            'status_rapat' => 'nullable|string|max:50',
        ]);

        $meeting = Meeting::create([
            'nama_rapat' => $validated['nama_rapat'],
            'deskripsi_rapat' => $validated['deskripsi_rapat'] ?? null,
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'tipe_rapat' => $validated['tipe_rapat'],
            'link_meeting' => $validated['link_meeting'] ?? null,
            'dibuat_oleh' => auth()->id(),
            'status_rapat' => $validated['status_rapat'] ?? 'Menunggu',
        ]);

        return redirect()->route('admin.meetings.index')
            ->with('success', 'Rapat berhasil dibuat.');
    }

    public function edit(Meeting $meeting)
    {
        return view('admin.meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'nama_rapat' => 'required|string|max:255',
            'deskripsi_rapat' => 'nullable|string|max:5000',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'tipe_rapat' => 'required|in:Online,Offline',
            'link_meeting' => 'nullable|string|max:255',
            'status_rapat' => 'required|string|max:50',
        ]);

        $meeting->update($validated);

        return redirect()->route('admin.meetings.index')
            ->with('success', 'Rapat berhasil diperbarui.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        return redirect()->route('admin.meetings.index')
            ->with('success', 'Rapat berhasil dihapus.');
    }
}
