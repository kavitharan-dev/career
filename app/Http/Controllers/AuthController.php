<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\NameWithoutDigits;
use App\Rules\SriLankanMobile;
use App\Services\NotificationService;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected NotificationService $notifications,
        protected SmsService $sms,
    ) {}

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new NameWithoutDigits],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', new SriLankanMobile],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $phone = $this->sms->normalizePhone($validated['phone']);

        if (User::where('phone', $phone)->exists()) {
            return back()
                ->withErrors(['phone' => 'This Sri Lankan mobile number is already registered.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $phone,
            'password' => $validated['password'],
        ]);

        $user->ensureProfile();

        Auth::login($user);
        $request->session()->regenerate();

        $this->notifications->sendRegistrationNotifications($user);

        return redirect()->route('onboarding.index')
            ->with('success', 'Account created! Check your email and phone for confirmation messages.');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $user->ensureProfile();

        if (! $user->is_admin) {
            $this->notifications->sendLoginNotifications($user, $request->ip());
        }

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        if (! $user->hasCompletedOnboarding()) {
            return redirect()->route('onboarding.index');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Login successful. A confirmation was sent to your email and phone.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }
}
