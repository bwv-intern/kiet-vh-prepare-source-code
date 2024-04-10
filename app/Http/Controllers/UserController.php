<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AddUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\SearchRequest;
use App\Libs\ConfigUtil;
use App\Repositories\UserRepository;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    protected UserService $userService;
    protected UserRepository $userRepository;
    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    /**
     * Render user01 page
     */
    public function search(SearchRequest $request)
    {
        $paramSession = session()->get('user.search') ?? [];
        $users = $this ->userService -> search($paramSession);
        $users = $this->pagination($users);
        return view('screens.user.search', compact('users', 'paramSession'));
    }

    /**
     * Handle user01 page
     */
    public function handleSearch(SearchRequest $request)
    {
        $params = $request->only(
            [   'email',
                'name',
                'user_flg',
                'date_of_birth',
                'phone',
            ]
        );

        session()->forget('user.search');
        session()->put('user.search', $params);
        return redirect() ->route('admin.user.search');
    }


    public function add()
    {
        return view('screens.user.add');
    }

    public function postAdd(AddUserRequest $request)
    {
        $result = $this->userService->create($request);
        if ($result) {
            Session::flash('success', ConfigUtil::getMessage('I013'));

            return redirect()->route('admin.user.search');
        }

        Session::flash('error', ConfigUtil::getMessage('E014'));

        return redirect()->back();
    }


    public function edit($id)
    {
        $user = $this->userService->find($id);
        if (! $user) {
            abort(404);
        }

        return view('screens.user.edit', compact('user'));
    }

    public function postEdit(EditUserRequest $request)
    {
        $result = $this->userService->update($request);
        if($result != null) {
            Session::flash('success', ConfigUtil::getMessage('I013'));
            return redirect()->route('admin.user.search');
        }
        Session::flash('error', ConfigUtil::getMessage('E014'));

        return redirect()->back();
    }
}
