<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transkrip;
use App\Models\Meeting;
use Illuminate\Http\Request;

class TranskripController extends Controller
{
    public function index()
    {
        $transkrips = Transkrip::with(['meeting.rekamanAudio', 'meeting.notulensi', 'meeting.arsip'])
            ->latest()
            ->paginate(20);
        return view('admin.transkrips.index', compact('transkrips'));
    }

    public function create()
    {
        $meetings = Meeting::doesntHave('transkrip')->orderBy('tanggal', 'desc')->get();
        return view('admin.transkrips.create', compact('meetings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'hasil_transkrip' => 'required|string',
            'tanggal_generate' => 'required|date',
            'openai_model' => 'nullable|string|max:64',
        ]);

        Transkrip::create($validated);

        return redirect()->route('admin.transkrips.index')
            ->with('success', 'Transkrip berhasil dibuat.');
    }

    public function show(Transkrip $transkrip)
    {
        $transkrip->load(['meeting.rekamanAudio', 'meeting.notulensi', 'meeting.arsip']);
        return view('admin.transkrips.show', compact('transkrip'));
    }

    public function edit(Transkrip $transkrip)
    {
        $transkrip->load('meeting');
        $meetings = Meeting::orderBy('tanggal', 'desc')->get();
        return view('admin.transkrips.edit', compact('transkrip', 'meetings'));
    }

    public function update(Request $request, Transkrip $transkrip)
    {
        $validated = $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'hasil_transkrip' => 'required|string',
            'tanggal_generate' => 'required|date',
            'openai_model' => 'nullable|string|max:64',
        ]);

        $transkrip->update($validated);

        return redirect()->route('admin.transkrips.index')
            ->with('success', 'Transkrip berhasil diperbarui.');
    }

    public function destroy(Transkrip $transkrip)
    {
        $transkrip->delete();

        return redirect()->route('admin.transkrips.index')
            ->with('success', 'Transkrip berhasil dihapus.');
    }
}
