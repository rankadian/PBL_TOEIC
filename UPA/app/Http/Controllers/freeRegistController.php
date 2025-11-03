<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ToeicRegistration;

class FreeRegistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->nim) {
            return redirect()->route('login')->with('error', 'User does not have a valid NIM.');
        }

        $registration = ToeicRegistration::where('nim', $user->nim)->first();

        if ($registration) {
            return view('freeregist.index', [
                'registration' => $registration,
                'nim' => $user->nim
            ]);
        }

        return redirect()->route('freeRegist.create')->with('message', 'Please complete your registration.');
    }

    public function create()
    {
        $user = Auth::user();

        if (!$user || !$user->nim) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $existingRegistration = ToeicRegistration::where('nim', $user->nim)->first();

        return view('freeregist.create', compact('existingRegistration'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $nim = $user->nim;

        $validated = $request->validate([
            'status' => 'nullable|in:pending,approved,rejected,cancelled',
            'certificate_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
            'ktp_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
        ]);

        $status = $validated['status'] ?? 'pending';
        $ktp_path = $request->file('ktp_path') ? $request->file('ktp_path')->store('public/ktps') : null;

        $existing = ToeicRegistration::where('nim', $nim)->first();

        if ($existing) {
            $existing->update([
                'status' => $status,
                'registration_date' => now(),
                'ktp_path' => $ktp_path ?? $existing->ktp_path,
            ]);

            return redirect()->route('freeRegist.index')->with('success', 'Pendaftaran berhasil diperbarui!');
        }

        ToeicRegistration::create([
            'nim' => $nim,
            'status' => $status,
            'registration_date' => now(),
            'ktp_path' => $ktp_path,
        ]);

        return redirect()->route('freeRegist.index')->with('success', 'Pendaftaran berhasil!');
    }

    public function showKtp($id)
    {
        $registration = ToeicRegistration::findOrFail($id);
        return view('partials.ktp', compact('registration'));
    }

    public function show($id)
    {
        $registration = ToeicRegistration::find($id);

        if (!$registration) {
            return redirect()->route('freeRegist.index')->with('error', 'Pendaftaran tidak ditemukan');
        }

        return view('freeregist.show', compact('registration'));
    }

    public function createSecondRegistration(Request $request, $id)
    {
        $registration = ToeicRegistration::find($id);

        if (!$registration) {
            return redirect()->route('freeRegist.index')->with('error', 'Pendaftaran tidak ditemukan');
        }

        if ($registration->is_second_registration) {
            return redirect()->route('freeRegist.index')->with('message', 'Anda sudah terdaftar kedua kalinya');
        }

        $registration->update(['is_second_registration' => true]);

        return redirect()->route('freeRegist.index')->with('success', 'Pendaftaran kedua berhasil');
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $nim = $user->nim;

        if (Auth::user()->role_name !== 'student') {
            abort(403, 'Unauthorized');
        }

        $registration = ToeicRegistration::where('id', $id)->where('nim', $nim)->first();

        if (!$registration) {
            return redirect()->route('freeRegist.index')->with('error', 'Registration not found.');
        }

        return view('freeregist.edit', compact('registration'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $nim = $user->nim;

        $validated = $request->validate([
            'status' => 'nullable|in:pending,approved,rejected,cancelled',
            'certificate_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
            'ktp_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
        ]);

        $registration = ToeicRegistration::where('id', $id)->where('nim', $nim)->first();

        if (!$registration) {
            return redirect()->route('freeRegist.index')->with('error', 'Registration not found.');
        }

        $ktp_path = $request->file('ktp_path') ? $request->file('ktp_path')->store('public/ktps') : $registration->ktp_path;

        $registration->update([
            'status' => $validated['status'] ?? 'pending',
            'ktp_path' => $ktp_path,
            'registration_date' => now(),
        ]);

        return redirect()->route('freeRegist.index')->with('success', 'Registration updated successfully!');
    }
}
