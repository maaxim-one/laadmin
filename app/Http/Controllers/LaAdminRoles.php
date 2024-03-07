<?php

namespace MaaximOne\LaAdmin\Http\Controllers;

use MaaximOne\LaAdmin\Facades\LaAdminRole;
use MaaximOne\LaAdmin\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;

class LaAdminRoles extends AdminController
{
    protected Request $_request;

    public function __construct(Request $request)
    {
        $this->_request = $this->replaceRequest($request);

        $this->setRulePage([
            'laadmin.add-role' => 'roles.add',
            'laadmin.save-role' => 'roles.edit',
            'laadmin.delete-role' => 'roles.delete'
        ], 'roles');

        $this->checkRules($this->_request, ['getRoles', 'getCleanRules']);
    }

    public function add()
    {
        $item = $this->setData(new Role());
        $item->save();
        return response()->json($item);
    }

    public function save()
    {
        $item = $this->setData(Role::findOrFail($this->_request->input('role_id')));
        $item->save();
        return response()->json($item);
    }

    public function delete()
    {
        $users = User::where('role_id', $this->_request->input('role_id'))->get();

        if (count($users->toArray()) <= 0) {
            Role::findOrFail($this->_request->input('role_id'))->delete();
        } else {
            return response()->json([
                'message' => 'Данная роль привяза к одному или нескольким пользователям. Удаление не возможно.'
            ]);
        }

        return response()->json(true);
    }

    protected function setData(Role $role): Role
    {
        $this->_request->validate([
            'role_name' => 'required|max:255|string',
            'rules' => 'required|array'
        ]);

        $role->role_name = $this->_request->input('role_name');
        $role->rules = $this->rulesReplace($this->_request->input('rules'));
        return $role;
    }

    protected function rulesReplace($rules)
    {
        foreach ($rules as $key => $rule) {
            if (Arr::has($rule, 'accept')) $rules[$key]['accept'] = $rule['accept'] == 'true';
            elseif (Arr::has($rule, 'value')) $rules[$key]['value'] = $rule['value'] == 'true';

            if (Arr::has($rule, 'params')) {
                $rules[$key]['params'] = $this->rulesReplace($rule['params']);
            }
        }

        return $rules;
    }

    public function getRoles()
    {
        if ($this->_request->has('role_id')) {
            $role = Role::findOrFail($this->_request->input('role_id'));
            $role->rules = collect(LaAdminRole::__toResponse())->merge($role->rules);

            return response()->json($role);
        } else {
            $roles = $this->_request->input('except_1', false)
                ? Role::all()->except(1)
                : Role::all();

            if ($this->_request->input('show_users_c', false)) {
                $users = User::all();

                foreach ($roles as $role) {
                    $a = $users->filter(function ($value, $key) use ($role) {
                        return $value->role_id == $role->role_id;
                    })->count();

                    $role->users = $a;
                }
            }

            return response()->json($roles);
        }
    }

    public function getCleanRules()
    {
        return response()->json(LaAdminRole::__toResponse());
    }
}
