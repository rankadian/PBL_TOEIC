<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ToeicRegistration;
use Illuminate\Support\Facades\Log;

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

<<<<<<< HEAD
=======
    // Method untuk memproses pendaftaran kedua kalinya
>>>>>>> 79757d161fde471857d0fb792c071065257dee5a
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

<<<<<<< HEAD
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

=======
    // Show the edit registration form
    // public function edit($id)
    // {
    //     // Ensure the user is authenticated
    //     if (Auth::check()) {
    //         $user = Auth::user();
    //         $nim = $user->nim;

    //         // Check if the registration exists for the given ID and user NIM
    //         $registration = ToeicRegistration::where('id', $id)->where('nim', $nim)->first();

    //         if ($registration) {
    //             // Show the edit form with the current registration data
    //             return view('freeregist.edit', compact('registration'));
    //         } else {
    //             // Redirect if registration does not exist
    //             return redirect()->route('freeRegist.index')->with('error', 'Registration not found.');
    //         }
    //     } else {
    //         // Redirect to login page if the user is not authenticated
    //         return redirect()->route('login');
    //     }
    // }

//     public function edit($id)
// {
//     // Ensure the user is authenticated
//     if (!Auth::check()) {
//         return redirect()->route('login')->with('error', 'Please login to access this page.');
//     }

//     $user = Auth::user();
//     $nim = $user->nim;

//     // Validate the ID parameter
//     if (!is_numeric($id) || $id <= 0) {
//         return redirect()->route('freeRegist.index')->with('error', 'Invalid registration ID.');
//     }

//     try {
//         // Find the registration with proper authorization check
//         $registration = ToeicRegistration::where('id', $id)
//             ->where('nim', $nim)
//             ->firstOrFail();

//         // Additional check to ensure students can only edit their own registrations
//         if ($user->role_name === 'student' && $registration->user_id != $user->id) {
//             abort(403, 'You are not authorized to edit this registration.');
//         }

//         // Check registration status - prevent editing if already approved/rejected
//         if (in_array($registration->status, ['approved', 'rejected'])) {
//             return redirect()->route('freeRegist.show', $id)
//                 ->with('warning', 'Cannot edit a registration that has been '.$registration->status.'.');
//         }

//         return view('freeregist.edit', compact('registration'));

//     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//         return redirect()->route('freeRegist.index')
//             ->with('error', 'Registration not found or you do not have permission to edit it.');
//     } catch (\Exception $e) {
//         Log::error('Error accessing edit page: '.$e->getMessage());
//         return redirect()->route('freeRegist.index')
//             ->with('error', 'An error occurred while accessing the edit page.');
//     }
// }

public function edit($id)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login to access this page.');
    }

    $user = Auth::user();
    $nim = $user->nim;

    // Validate the ID parameter
    if (!is_numeric($id) || $id <= 0) {
        return redirect()->route('freeRegist.index')->with('error', 'Invalid registration ID.');
    }

    try {
        // Find the registration with proper authorization check
        $registration = ToeicRegistration::where('id', $id)
            ->where('nim', $nim)
            ->firstOrFail();

        // Check registration status - prevent editing if already approved/rejected
        if (in_array($registration->status, ['approved', 'rejected'])) {
            return redirect()->route('freeRegist.show', $id)
                ->with('warning', 'Cannot edit a registration that has been '.$registration->status.'.');
        }

        return view('freeregist.edit', compact('registration'));

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('freeRegist.index')
            ->with('error', 'Registration not found or you do not have permission to edit it.');
    } catch (\Exception $e) {
        Log::error('Error accessing edit page: '.$e->getMessage());
        return redirect()->route('freeRegist.index')
            ->with('error', 'An error occurred while accessing the edit page.');
    }
}

    // Update the registration data
>>>>>>> 79757d161fde471857d0fb792c071065257dee5a
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
