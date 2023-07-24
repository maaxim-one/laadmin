<?php

namespace MaaximOne\LaAdmin\Http\Controllers;

use Illuminate\Http\Request;
use MaaximOne\LaAdmin\Models\Role;

class LaAdminRoles extends AdminController
{
    protected Request $_request;

    public function __construct(Request $request)
    {
        $this->_request = $this->replaceRequest($request);
    }

    public function getRoles()
    {
        return response()->json(Role::all());
    }
}
