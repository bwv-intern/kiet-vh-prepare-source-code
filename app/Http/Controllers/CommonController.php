<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * Reset search
     *
     * @param Request $request
     */
    public function resetSearch(Request $request) {
        try {
            $screenSession = $request->screen;
            if ($request->session()->has($screenSession)) {
                $request->session()->forget($screenSession);
            }

            return response()->json([
                'hasError' => false,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'hasError' => true,
            ]);
        }
    }
}
