<?php

namespace App\Repositories\Profile;

use App\Enums\PrivacySettings;
use App\Enums\ProfileVisibilityTypes;
use App\Http\Requests\BiographyFormRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileRepository
{
    public function updateBiography(User $user, BiographyFormRequest $request): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->back()->with('success', 'Biography updated successfully');
    }

    public function updatePrivacySettings(User $user, Request $request): RedirectResponse
    {
        $request->merge(['visibility_type' => (int) $request->visibility_type]);

        $privacySettings = PrivacySettings::cases();
        $visibilityTypes = ProfileVisibilityTypes::cases();

        // Creating validation rules for privacy settings
        $rules = [];
        foreach ($privacySettings as $setting) {
            $rules[$setting->value] = ['required', 'in:true,false'];
        }

        // Adding validation for visibility type
        $rules['visibility_type'] = [
            'required',
            'integer',
            Rule::in(array_map(fn($type) => $type->value, $visibilityTypes))
        ];


        $validatedData = $request->validate($rules);

        $settings = [];
        foreach ($validatedData as $key => $value) {
            if (in_array($key, array_map(fn($setting) => $setting->value, $privacySettings), true)) {
                $settings[$key] = $value === 'true';
            }
        }

        // Merge visibility type with boolean settings
        $settings['visibility_type'] = $validatedData['visibility_type'];

        // Updating boolean settings
        $user->privacySettings()->update($settings);

        return redirect()->back()->with('success', 'Privacy settings updated successfully');
    }
}
