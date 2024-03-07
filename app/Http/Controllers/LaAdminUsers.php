<?php

namespace MaaximOne\LaAdmin\Http\Controllers;

use MaaximOne\LaAdmin\Mail\LaAdminNewUserMail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use MaaximOne\LaAdmin\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class LaAdminUsers extends AdminController
{
    protected Request $_request;
    protected LaAdminProfile $_profile;

    public function __construct(Request $request)
    {
        $this->_request = $this->replaceRequest($request);
        $this->_profile = new LaAdminProfile($this->_request);

        $this->setRulePage([
            'laadmin.save-user' => 'users.edit',
            'laadmin.delete-user' => 'users.delete',
            'laadmin.new-user' => 'users.add',
            'laadmin.forget-password' => 'users.reset',
        ], 'users');

        $this->checkRules($this->_request, 'getUsers');
    }

    public function getUsers()
    {
        $users = User::orderBy('id', 'desc')->get();
        $roles = Role::all()->keyBy('role_id');

        foreach ($users as $user) {
            $user->other_data = $this->_profile->getUserOtherData($user);
            $user->role = $roles[$user->role_id];
        }

        return response()->json($users);
    }

    public function saveUser()
    {
        return $this->_profile->saveProfile(
            User::findOrFail($this->_request->input('id'))
        );
    }

    public function newUser()
    {
        $this->_request->validate([
            'email' => 'required|email|max:255|unique:users',
            'role_id' => 'required|int|exists:roles'
        ]);

        $token = Str::random(60);

        $new = new User();
        $new->name = 'New user';
        $new->email = $this->_request->input('email');
        $new->role_id = $this->_request->input('role_id');
        $new->password = Hash::make($token);
        $new->saveOrFail();

        Mail::send(new LaAdminNewUserMail(
            $this->_request->input('email'), $token
        ));

        return response()->json(true);
    }

    public function deleteUser()
    {
        return response()->json(
            User::findOrFail($this->_request->input('user_id'))->delete()
        );
    }

    public function sendPasswordResetNotification()
    {
        $this->_request->validate([
            'email' => 'required|email'
        ]);

        return response()->json(Password::sendResetLink($this->_request->only('email')));
    }
}
