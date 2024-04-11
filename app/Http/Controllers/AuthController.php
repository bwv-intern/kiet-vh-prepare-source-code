<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Libs\ConfigUtil;
use App\Services\AuthService;
use Illuminate\Support\Facades\{Auth, Session, URL};

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * Render login page
     */
    public function login() {
        if (Auth::check()) {
            return redirect()->route('admin.user.search');
        }

        return view('screens.auth.login');
    }

    /**
     * Handle login
     * @param LoginRequest $request
     */
    public function handleLogin(LoginRequest $request) {
        $credentials = $request->only('email', 'password');
        $user = $this->authService->handleLogin($credentials);

        if ($user != null && $user->user_flg != 1) {
            Auth::login($user);

            return redirect()->route('admin.top.index');
        }
        if ($user == null) {
            return $this->handleInvalidCredentials($credentials);
        }
        if ($user->user_flg == 1) {
            return $this->handleUserFlagError();
        }
    }

    /**
     * Logout the currently authenticated user.
     */
    public function logout() {
        Auth::logout();
        session()->flush();
        session()->invalidate();

        // fix back event from browser
        return redirect(URL::previous());
    }

    private function handleInvalidCredentials($credentials) {
        Session::flash('credentials', $credentials);

        return redirect()->back()->withInput()->withErrors(ConfigUtil::getMessage('E010'));
    }

    private function handleUserFlagError() {
        abort(403);
    }
}
