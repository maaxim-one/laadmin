<?php

namespace MaaximOne\LaAdmin\Http\Controllers\Auth;

use MaaximOne\LaAdmin\Http\Controllers\AdminController;
use MaaximOne\LaAdmin\Filters\ReplaceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class LaAdminLogin extends AdminController
{
    use ReplaceRequest;

    public function page()
    {
        return view('laadmin::auth.login');
    }

    public function authenticate(Request $request)
    {
        $request = $this->replaceRequest($request);

        $userData = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attemptWhen($userData, function (User $user) {
            return $user->role_id > 1;
        }, $request->input('remember', false))) {
            $request->session()->regenerate();
            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'errors' => [
                'login' => [''],
                'password' => [
                    'Имя пользователя или пароль не совпадают.'
                ]
            ]
        ], 422);
    }

    public function getUser()
    {
        return response()->json(
            Auth::user()
        );
    }
}
