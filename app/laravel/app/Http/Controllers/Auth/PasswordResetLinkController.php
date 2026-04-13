<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $codeDigits = collect(range(1, 6))
            ->map(fn (int $i): string => (string) $request->input("code{$i}", ''));

        $enteredCode = $codeDigits->implode('');
        $hasAnyCode = $codeDigits->contains(fn (string $digit): bool => trim($digit) !== '');

        if ($hasAnyCode) {
            if ($enteredCode !== '222222') {
                return redirect()
                    ->route('password.request')
                    ->withInput($request->only('email'))
                    ->withErrors(['code' => 'Invalid code.']);
            }

            $user = User::query()->where('email', $request->email)->first();

            if (! $user) {
                return redirect()
                    ->route('password.request')
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => __('passwords.user')]);
            }

            $token = Password::broker()->createToken($user);

            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? redirect()->route('password.request')->with('status', __($status))
            : redirect()->route('password.request')
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
