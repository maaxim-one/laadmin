<?php

namespace MaaximOne\LaAdmin\Filters;

use Illuminate\Http\Request;

trait ReplaceRequest
{
    public function replaceRequest(Request $request): Request
    {
        foreach ($request->all() as $key => $item) {
            if ($item === 'null' || $item === '') {
                $request->merge([
                    $key => null
                ]);
            } elseif ($item === 'true') {
                $request->merge([
                    $key => true
                ]);
            } elseif ($item === 'false') {
                $request->merge([
                    $key => false
                ]);
            }
        }

        return $request;
    }
}
