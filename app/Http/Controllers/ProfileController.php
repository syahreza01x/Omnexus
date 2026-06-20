<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        return view('profile.edit', [
            'user' => $user,
            'addresses' => $user->addresses()->get(),
            'activeTab' => $request->query('tab', 'profile'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update basic profile details such as username and photo.
     */
    public function updateBasic(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,name,'.$request->user()->id],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $user->name = $validated['name'];

        if ($request->hasFile('photo')) {
            $user->profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
        }

        $user->save();

        return Redirect::route('profile.edit', ['tab' => 'profile'])->with('status', 'basic-updated');
    }

    /**
     * Update account email.
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
        ]);

        $user = $request->user();
        $user->email = $validated['email'];
        $user->email_verified_at = null;
        $user->save();

        return Redirect::route('profile.edit', ['tab' => 'security'])->with('status', 'email-updated');
    }

    /**
     * Add a new address.
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_default' => ['boolean'],
        ]);

        $user = $request->user();

        // If this is set as default, unset other defaults
        if ($validated['is_default'] ?? false) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($validated);

        return Redirect::route('profile.edit', ['tab' => 'address'])->with('status', 'address-created');
    }

    /**
     * Update an existing address.
     */
    public function updateAddress(Request $request, Address $address): RedirectResponse
    {
        // Check authorization
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $address->update($validated);

        return Redirect::route('profile.edit', ['tab' => 'address'])->with('status', 'address-updated');
    }

    /**
     * Set address as default.
     */
    public function setDefaultAddress(Request $request, Address $address): RedirectResponse
    {
        // Check authorization
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return Redirect::route('profile.edit', ['tab' => 'address'])->with('status', 'address-default-set');
    }

    /**
     * Delete an address.
     */
    public function deleteAddress(Request $request, Address $address): RedirectResponse
    {
        // Check authorization
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $address->delete();

        return Redirect::route('profile.edit', ['tab' => 'address'])->with('status', 'address-deleted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
