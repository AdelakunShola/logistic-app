<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        
        return view('admin.admin_login');
    }

    /**
     * Handle login request
     */ 
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // Check if user is active
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account is currently ' . $user->status . '. Please contact support.'],
            ]);
        }

        // Attempt login
     if (Auth::attempt($credentials, $remember)) {
    $request->session()->regenerate();

    $authenticatedUser = Auth::user();
    $authenticatedUser->update([
        'last_login_at' => now(),
        'last_login_ip' => $request->ip(),
    ]);

    $this->logActivity($authenticatedUser, 'login', 'User logged in successfully');

    return $this->redirectBasedOnRole($authenticatedUser);
}



        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request
     */
    public function AdminDestroy(Request $request)
    {
        $user = Auth::user();
        
        // Log activity before logout
        if ($user) {
            $this->logActivity($user, 'logout', 'User logged out');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'You have been successfully logged out.');
    }



    /**
     * Change password
     */
    public function changePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => 'required',
        'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
    ]);

    $user = Auth::user();

    if (!Hash::check($validated['current_password'], $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Current password is incorrect'
        ], 422);
    }

    $user->update([
        'password' => Hash::make($validated['new_password'])
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Password changed successfully'
    ]);
}
    /**
     * Redirect user based on role
     */
    protected function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
            case 'manager':
            case 'dispatcher':
                return redirect()->route('admin.dashboard');
            case 'driver':
                return redirect()->route('driver.dashboard');
            case 'user':
                return redirect()->route('user.dashboard');
            default:
                Auth::logout();
                return redirect('/login')->with('error', 'Invalid user role.');
        }
    }

    /**
     * Log user activity
     */
    protected function logActivity($user, $action, $description)
{
    try {
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    } catch (\Throwable $e) {
        Log::error('Activity log failed: '.$e->getMessage());
    }
}

}