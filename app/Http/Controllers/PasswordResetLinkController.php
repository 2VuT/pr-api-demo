<?php

namespace App\Http\Controllers;

use App\Notifications\ResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'app_url' => 'required|url'
        ]);

        $response = Password::broker()->sendResetLink(
            $request->only('email'),
            function ($user, $token) use ($request) {
                $user->notify(new ResetPassword($token, $request->app_url));
            }
        );

        if ($response == Password::RESET_LINK_SENT) {
            return new JsonResponse(['message' => trans($response)], 200);
        }

        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
}
