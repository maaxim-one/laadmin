<?php

namespace MaaximOne\LaAdmin\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class LaAdminRegister extends Controller
{
    protected Request $_request;

    public function __construct(Request $request)
    {
        $this->_request = $request;
    }

    public function page()
    {
        return view('laadmin::auth.register', [
            'data' => [
                'verify' => $this->verifyUser(
                    $this->_request->get('token'),
                    $this->_request->get('email')
                ),
                'email' => $this->_request->get('email'),
                'token' => $this->_request->get('token'),
            ]
        ]);
    }

    protected function verifyUser($token, $email)
    {
        $user = User::where('email', '=', $email)->get();

        if (!empty($user) && $user->count() == 1) {
            $user = User::findOrFail($user->toArray()[0]['id']);

            if (Hash::check($token, $user->password)) {
                return ['user' => $user, 'status' => true];
            } else {
                return [
                    'status' => false,
                    'message' => 'Верификация не пройдена. Обратитесь к администратору.'
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => 'Пользователь не найден. Обратитесь к администратору.'
            ];
        }
    }

    public function register()
    {
        $user = $this->_request->input('verify')['user'];

        $this->_request->validate([
            'name' => 'required|max:255|string',
            'login' => 'required|max:255|string|unique:users',
            'email' => "required|max:255|email|unique:users,email,{$user['id']},id",
            'password' => 'required|min:6|string|confirmed'
        ]);

        $user = User::findOrFail($user['id']);
        $user->name = $this->_request->input('name');
        $user->login = $this->_request->input('login');
        $user->email = $this->_request->input('email');
        $user->email_verified_at = now();
        $user->password = Hash::make($this->_request->input('password'));
        $user->save();

        return response()->json(true);
    }
}
