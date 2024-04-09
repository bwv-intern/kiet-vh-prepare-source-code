<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Render user01 page
     */
    public function search(Request $request)
    {
//        $paramSession = session()->get('usr01.search') ?? [];
//        $users =  [];
//        $users = [];
//        return view('screens.user.usr01', compact('users', 'paramSession'));
        return view('screens.user.search');
    }

    /**
     * Handle user01 page
     */
    public function handleSearch(Request $request)
    {
        $params = $request->only(['user_id', 'user_flag', 'name', 'email']);
        session()->forget('usr01.search');
        session()->put('usr01.search', $params);
        return to_route('user.usr01');
    }
}
