<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
       $user = auth()->user();
        return view('auth.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user() ?? auth('mahasiswa')->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];

        if (!is_null($user->nrp)) {
            $rules['alamat'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!is_null($user->nrp)) {
            $user->alamat = $validated['alamat'];
        }

        $user->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Profile berhasil diperbarui');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
       //
    }
}
