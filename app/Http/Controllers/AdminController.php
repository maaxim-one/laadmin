<?php

namespace MaaximOne\LaAdmin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use MaaximOne\LaAdmin\Filters\ReplaceRequest;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ReplaceRequest;

    protected string $_rulePage = '';

    protected function getRulePage(): string
    {
        return $this->_rulePage;
    }

    protected function setRulePage(array $rules, string $default)
    {
        foreach ($rules as $key => $rule) {
            if (app('router')->currentRouteNamed($key)) {
                $this->_rulePage = $rule;
            }
        }

        if ($this->_rulePage == '') {
            $this->_rulePage = $default;
        }
    }

    protected function checkRules(Request $request, $except = null)
    {
        $request->merge(['page' => $this->getRulePage()]);
        $this->middleware('CheckAccept')->except($except);
    }
}
