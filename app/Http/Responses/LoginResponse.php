<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $target = match ($user->role ?? 'student') {
            'admin' => route('dashboard.admin.index'),
            'teacher' => route('dashboard.teacher.index'),
            default => route('dashboard.student.index'),
        };

        return redirect()->intended($target);
    }
}
