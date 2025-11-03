<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaidToeicController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        return view('paid-toeic.form', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
            'id_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        // Upload files
        $photoPath = $request->file('photo')->store('photos');
        $idCardPath = $request->file('id_card')->store('id_cards');

        // Update user record
        DB::table('user')->where('id', $user->id)->update([
            'photo_path' => $photoPath,
            'id_card_path' => $idCardPath,
            'updated_at' => now(),
        ]);

        return view('paid-toeic.payment', ['user' => $user]);
    }
}
