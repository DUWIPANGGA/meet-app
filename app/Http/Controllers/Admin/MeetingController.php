<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with('creator')->where('tipe_rapat', 'Online')->latest()->paginate(20);
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
            'tanggal' => 'nullable|date|after_or_equal:today',
            'waktu' => 'nullable|date_format:H:i',
            'tipe_rapat' => 'required|in:instan,terjadwal',
            'jenis_rapat' => 'nullable|in:Online,Offline',
            'link_meeting' => 'nullable|string|max:255',
            'status_rapat' => 'nullable|string|max:50',
        ]);

        if (($validated['tipe_rapat'] ?? '') !== 'instan') {
            $tanggal = $validated['tanggal'] ?? null;
            $waktu = $validated['waktu'] ?? null;
            if ($tanggal && $waktu && \Carbon\Carbon::parse($tanggal . ' ' . $waktu)->isPast()) {
                return back()->withErrors(['waktu' => 'Tanggal dan waktu tidak boleh di masa lalu.'])->withInput();
            }
        }

        $isInstant = $validated['tipe_rapat'] === 'instan';
        $tipeRapatDb = $validated['jenis_rapat'];

        $meeting = Meeting::create([
            'nama_rapat' => $validated['nama_rapat'],
            'deskripsi_rapat' => $validated['deskripsi_rapat'] ?? null,
            'tanggal' => $isInstant ? now()->toDateString() : $validated['tanggal'],
            'waktu' => $isInstant ? now()->toTimeString() : $validated['waktu'],
            'tipe_rapat' => $tipeRapatDb,
            'link_meeting' => $validated['link_meeting'] ?? null,
            'dibuat_oleh' => auth()->id(),
            'status_rapat' => $isInstant ? 'Berlangsung' : ($validated['status_rapat'] ?? 'Menunggu'),
        ]);

        if ($isInstant) {
            return redirect()->route('meeting.room', $meeting->id);
        }

        if ($request->_source === 'agenda') {
            return redirect()->route('admin.agendas.index')
                ->with('success', 'Rapat berhasil dibuat.');
        }

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
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'jenis_rapat' => 'required|in:Online,Offline',
            'link_meeting' => 'nullable|string|max:255',
            'status_rapat' => 'required|string|max:50',
        ]);

        $tanggal = $validated['tanggal'];
        $waktu = $validated['waktu'];
        if (\Carbon\Carbon::parse($tanggal . ' ' . $waktu)->isPast()) {
            return back()->withErrors(['waktu' => 'Tanggal dan waktu tidak boleh di masa lalu.'])->withInput();
        }

        $validated['tipe_rapat'] = $validated['jenis_rapat'];
        unset($validated['jenis_rapat']);

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
