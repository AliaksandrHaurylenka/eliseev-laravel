<?php

namespace App\UseCases\Profile;

use App\Entity\User;
use App\Http\Requests\Cabinet\ProfileEditRequest;

class ProfileService
{
    public function edit($id, ProfileEditRequest $request): void
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $oldPhone = $user->phone;
        $user->update($request->only('name', 'last_name', 'phone'));
        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }
    }
}
