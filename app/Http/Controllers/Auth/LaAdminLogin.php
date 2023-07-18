<?php

namespace MaaximOne\LaAdmin\Http\Controllers\Auth;

use MaaximOne\LaAdmin\Http\Controllers\AdminController;
use MaaximOne\LaAdmin\Filters\ReplaceRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use MaaximOne\LaAdmin\Models\Role;
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
        $user = Auth::user();
        $user->other_data = $this->getUserOtherData($user);
        $user->role = Role::findOrFail($user->role_id);

        return response()->json($user);
    }

    public function verifyUser(Request $request)
    {
        if (Hash::check($request->input('password'), Auth::user()->getAuthPassword())) {
            return response()->json(true);
        } else {
            return response()->json([
                'errors' => [
                    'password' => [
                        'Не корректно веден пароль'
                    ]
                ]
            ], 422);
        }
    }

    public function saveProfile(Request $request)
    {
        $request = $this->replaceRequest($request);
        $user = Auth::user();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'login' => "required|string|max:255|unique:users,login,{$user->id},id",
            'email' => "sometimes|nullable|email|max:255|unique:users,email,{$user->id},id"
        ]);

        if (Schema::hasColumn('users', 'phone')) {
            $this->validate($request, [
                'phone' => "sometimes|nullable|max:255|unique:users,phone,{$user->id},id"
            ]);
        }

        foreach (collect($request->all())->forget(['id', 'role', 'other_data']) as $key => $item) {
            $user->$key = $item;
        }

        foreach ($request->all()['other_data'] as $key => $item) {
            $user->$key = $item['value'];
        }

        $user->save();

        return response()->json(true);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|string|min:6'
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(true);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('laadmin.login');
    }

    protected function getUserOtherData(User $user): array
    {
        $other_data = collect($user)->forget([
            'id', 'name', 'surname', 'login', 'email', 'phone', 'email_verified_at', 'password', 'remember_token',
            'last_visit', 'role_id', 'created_at', 'updated_at'
        ])->toArray();

        foreach ($other_data as $key => $other_datum) {
            $other_data[$key] = [
                'title' => __($key),
                'value' => $other_datum
            ];
        }

        return $other_data;
    }
}
