<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $meetings = Meeting::orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->get();

        return view('admin.agendas.index', compact('meetings'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'nama_rapat' => 'required|string|max:255',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'deskripsi_rapat' => 'nullable|string|max:5000',
            'tipe_rapat' => 'required|in:Online,Offline',
            'link_meeting' => 'nullable|string|max:255',
            'status_rapat' => 'required|string|max:50',
        ]);

        $tanggal = $validated['tanggal'];
        $waktu = $validated['waktu'];
        if (\Carbon\Carbon::parse($tanggal . ' ' . $waktu)->isPast()) {
            return back()->withErrors(['waktu' => 'Tanggal dan waktu tidak boleh di masa lalu.'])->withInput();
        }

        $meeting->update($validated);

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        return redirect()->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
