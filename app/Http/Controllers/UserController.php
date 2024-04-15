<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\{AddUserRequest, EditUserRequest, ImportCsvRequest, SearchRequest};
use App\Libs\ConfigUtil;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\{Auth, Cookie, Session};

class UserController extends Controller
{
    protected UserService $userService;

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, UserService $userService) {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    /**
     * Render ADMIN_USER_SEARCH page
     * @param SearchRequest $request
     */
    public function search(SearchRequest $request) {
        // default search
        $paramsDefault = ['user_flg' => [
            0 => '0',
            1 => '1',
            2 => '2',
        ]];
        $paramSession = session()->get('user.search') ?? $paramsDefault;
        // main search
        if (! isset($paramSession['btnExport'])) {
            $users = $this->userService->search($paramSession);
            $users = $this->pagination($users);

            return view('screens.user.search', compact('users', 'paramSession'));
        }

        return $this->exportCSV($paramSession);
    }

    /**
     * Handle ADMIN_USER_SEARCH page
     * @param SearchRequest $request
     */
    public function handleSearch(SearchRequest $request) {
        $params = $request->only(
            ['email',
                'name',
                'user_flg',
                'date_of_birth',
                'phone',
            ],
        );

        if ($request->has('btnExport')) {
            $params['btnExport'] = 'exportCSV';
        }

        session()->forget('user.search');
        session()->put('user.search', $params);

        return redirect()->route('admin.user.search');
    }

    /**
     * Returns the view for adding a new user.
     *
     * @return \Illuminate\Contracts\View\View The view for adding a new user.
     */
    public function add() {
        $breadcrumbLinks = [
            ['url' => route('admin.top.index'), 'name' => 'Top'],
            ['url' => route('admin.user.search'), 'name' => 'Users'],
            ['url' => '', 'name' => 'User add'],
        ];

        return view('screens.user.add', compact('breadcrumbLinks'));
    }

    /**
     * Handles the addition of a new user.
     *
     * @param AddUserRequest $request The request containing the user data.
     * @return \Illuminate\Http\RedirectResponse The redirect response after adding the user.
     */
    public function postAdd(AddUserRequest $request) {
        $result = $this->userService->create($request);
        if ($result) {
            Session::flash('success', ConfigUtil::getMessage('I013'));

            return redirect()->route('admin.user.search');
        }

        Session::flash('error', ConfigUtil::getMessage('E014'));

        return redirect()->back();
    }

    /**
     * Returns the ADMIN_USER_EDIT view to edit user information.
     *
     * @param int $id The ID of the user to be edited.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The ADMIN_USER_EDIT view or a 404 error if the user is not found.
     */
    public function edit($id) {
        $user = $this->userService->find($id);
        if (! $user) {
            abort(404);
        }
        $breadcrumbLinks = [
            ['url' => route('admin.top.index'), 'name' => 'Top'],
            ['url' => route('admin.user.search'), 'name' => 'Users'],
            ['url' => '', 'name' => 'User edit'],
        ];

        return view('screens.user.edit', compact('user', 'breadcrumbLinks'));
    }

    /**
     * Handles the editing of a user.
     *
     * @param EditUserRequest $request The request containing the user data.
     * @return \Illuminate\Http\RedirectResponse The redirect response after editing the user.
     */
    public function postEdit(EditUserRequest $request) {
        $result = $this->userService->update($request);
        if ($result != null) {
            Session::flash('success', ConfigUtil::getMessage('I013'));

            return redirect()->route('admin.user.search');
        }
        Session::flash('error', ConfigUtil::getMessage('E014'));

        return redirect()->back();
    }

    /**
     * Delete a user based on ID.
     *
     * @param int $id ID of the user to be deleted.
     * @return \Illuminate\Http\RedirectResponse The redirect response after editing the user.
     */
    public function doDelete($id) {
        if (Auth::id() != $id) {
            $this->userService->delete($id);
        }

        return redirect()->back();
    }

    /**
     * Export users data to CSV file.
     *
     * @param $paramSession - Parameters for exporting data.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse - The response containing the CSV file.
     */
    public function exportCSV($paramSession) {
        $users = $this->userService->exportCSV($paramSession);
        $filePath = storage_path('app/users.csv');
        $file = fopen($filePath, 'w');

        // encoding CSV UTF-8
        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($file, ['"user_id"', '"email"', '"name"', '"user_flg"', '"date_of_birth"', '"phone"', '"address"', '"del_flg"', '"created_by"', '"created_at"']);
        foreach ($users as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        session()->forget('user.search.btnExport');

        $response = response()->download($filePath, 'users.csv')->deleteFileAfterSend(true);
        $cookie = Cookie::make('exported', 'Ok', 1, null, null, false, false);
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * Handles the import of a CSV file.
     *
     * @param ImportCsvRequest $request The request containing the CSV file.
     * @return \Illuminate\Http\RedirectResponse The redirect response after importing the CSV file.
     */
    public function importCsv(ImportCsvRequest $request) {
        if ($request->hasFile('csvFile')) {
            $csvFile = $request->file('csvFile');

            $tempPath = $csvFile->getRealPath();

            $result = $this->userService->importCsv($tempPath);

            switch ($result['message']) {
                case 'WRONG_HEADER':
                    return redirect()->back()->withErrors(ConfigUtil::getMessage('E008'))->withInput();
                case 'ERROR':
                    return redirect()->back()->withErrors($result['data'])->withInput();
                case 'SUCCESS':
                    return redirect('/admin/user')->with('success', ConfigUtil::getMessage('I013'));
                default:
                    return redirect()->back();
            }
        }
    }
}
