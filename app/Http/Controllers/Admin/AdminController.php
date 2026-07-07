<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Notulensi;
use App\Models\RekamanAudio;
use App\Models\Transkrip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_meetings'   => Meeting::count(),
            'total_online'     => Meeting::where('tipe_rapat', 'Online')->count(),
            'total_offline'    => Meeting::where('tipe_rapat', 'Offline')->count(),
            'total_rekaman'    => RekamanAudio::count(),
            'total_transkripsi'=> Transkrip::count(),
            'total_notulensi'  => Notulensi::count(),
        ];

        $recentMeetings = Meeting::latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentMeetings'));
    }

    public function riwayatMeeting()
    {
        $meetings = Meeting::with(['transkrip', 'notulensi', 'creator'])
            ->where(function ($q) {
                $q->whereHas('transkrip')->orWhereHas('notulensi');
            })
            ->latest()
            ->paginate(20);

        return view('admin.riwayat-meeting.index', compact('meetings'));
    }

    public function destroyRiwayatMeeting(Meeting $meeting)
    {
        $meeting->transkrip()->delete();
        $meeting->notulensi()->delete();
        $meeting->rekamanAudio()->delete();
        $meeting->arsip()->delete();
        $meeting->delete();

        return redirect()->route('admin.riwayat-meeting.index')
            ->with('success', 'Riwayat meeting berhasil dihapus.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile.show', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'          => ['required', 'string'],
            'password'                  => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')->with('success', 'Password berhasil diperbarui!');
    }
}
