<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $home = match (auth()->user()->role) {
            'admin' => route('admin.dashboard'),
            'staff' => route('staff.pos'),
            default => route('home'),
        };

        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false], 200)
            : redirect()->intended($home);
    }
}
