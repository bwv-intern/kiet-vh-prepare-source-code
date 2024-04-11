<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use App\Libs\ConfigUtil;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    /**
     * Render login page
     */
    public function login()
    {
        if(Auth::check()) {
            return redirect()->route('admin.user.search');
        }
        return view('screens.auth.login');
    }

    /**
     * Handle login
     */
    public function handleLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $user = $this->authService->handleLogin($credentials);

        if ($user != null && $user->user_flg != 1) {
            Auth::login($user);
            return redirect()->route('admin.top.index');
        } else {
            if ($user == null) {
                return $this->handleInvalidCredentials($credentials);
            } elseif ($user->user_flg == 1) {
                return $this->handleUserFlagError();
            }
        }
    }


    private function handleInvalidCredentials($credentials)
    {
        Session::flash('credentials', $credentials);
        return redirect()->back()->withInput()->withErrors(ConfigUtil::getMessage('E010'));
    }

    private function handleUserFlagError()
    {
        abort(403);
    }

    /**
     * Logout the currently authenticated user.
     */
    public function logout()
    {
        Auth::logout();
        session()->flush();
        session()->invalidate();
        // fix back event from browser
        return redirect(URL::previous());
    }
}
