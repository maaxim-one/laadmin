<?php

namespace MaaximOne\LaAdmin\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MaaximOne\LaAdmin\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;

class LaAdminProfile extends AdminController
{
    protected Request $_request;

    public function __construct(Request $request)
    {
        $this->_request = $this->replaceRequest($request);
    }

    public function getUser()
    {
        $user = Auth::user();
        $user->other_data = $this->getUserOtherData($user);
        $user->role = Role::findOrFail($user->role_id);

        return $this->_request->expectsJson()
            ? response()->json($user)
            : $user;
    }

    public function getUserOtherData(User $user): array
    {
        $other_data = collect($user->toArray())->forget([
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

    public function saveProfile(User $user = null)
    {
        if ($user == null) $user = Auth::user();

        $this->_request->validate([
            'name' => 'required|string|max:255',
            'login' => "required|string|max:255|unique:users,login,$user->id,id",
            'email' => "sometimes|nullable|email|max:255|unique:users,email,$user->id,id"
        ]);

        if (Schema::hasColumn('users', 'phone')) {
            $this->_request->validate([
                'phone' => "sometimes|nullable|max:255|unique:users,phone,$user->id,id"
            ]);
        }

        foreach (collect($this->_request->all())->forget(['id', 'role', 'other_data']) as $key => $item) {
            $user->$key = $item;
        }

        if (Arr::has($this->_request->all(), 'other_data')) {
            foreach ($this->_request->all()['other_data'] as $key => $item) {
                $user->$key = Arr::has($item, 'value') ? $item['value'] : null;
            }
        }

        $user->save();

        return response()->json(true);
    }

    public function changePassword()
    {
        $this->_request->validate([
            'password' => 'required|confirmed|string|min:6'
        ]);

        $user = Auth::user();
        $user->password = Hash::make($this->_request->input('password'));
        $user->save();

        return response()->json(true);
    }

    public function verifyUser()
    {
        if (Hash::check($this->_request->input('password'), Auth::user()->getAuthPassword())) {
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
}
