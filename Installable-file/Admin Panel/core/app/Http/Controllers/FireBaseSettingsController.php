<?php

namespace App\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FireBaseSettingsController extends Controller
{
    public function uploadFirebaseJson(Request $request)
    {
        // Validate the uploaded file
        if ($request->isMethod('post')) {
            $request->validate([
                'firebase_json' => 'required|file|mimes:json|max:2048',
            ]);
            $request->file('firebase_json')->storeAs('firebase', 'firebase_credentials.json', 'local');
            return redirect()->back()->with(FlashMsg::settings_update());
        }

        $firebaseFileExists = Storage::disk('local')->exists('firebase/firebase_credentials.json');
        return view('backend.pages.firebase.settings', compact('firebaseFileExists'));
    }

}
