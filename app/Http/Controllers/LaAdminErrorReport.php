<?php

namespace MaaximOne\LaAdmin\Http\Controllers;

use MaaximOne\LaAdmin\Models\ErrorReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LaAdminErrorReport extends AdminController
{
    protected Request $_request;

    public function __construct(Request $request)
    {
        $this->_request = $request;

        $this->setRulePage([
            'laadmin.get-report' => 'errors.read',
            'laadmin.error-read' => 'errors.read',
            'laadmin.error-fixed' => 'errors.fixed',
            'laadmin.error-event' => 'errors.comment'
        ], 'errors');

        $this->checkRules($request, 'getErrors');
    }

    public function getErrors()
    {
        $errors = ErrorReport::orderBy('report_id', 'desc');
        if ($this->_request->has('limit')) $errors = $errors->limit($this->_request->input('limit'));
        $errors = $errors->get();
        $count = 0;

        foreach ($errors as $error) {
            if ($error->report_fixed_at == null && $error->report_read_at == null) {
                $count++;
            }
        }

        return response()->json([
            'reports' => $errors,
            'count' => $count
        ]);
    }

    public function getReport()
    {
        return response()->json(
            ErrorReport::findOrFail($this->_request->input('report_id'))
        );
    }

    public function read()
    {
        return response()->json(
            ErrorReport::findOrFail($this->_request->input('report_id'))->setReadStatus()
        );
    }

    public function fixed()
    {
        return response()->json(
            ErrorReport::findOrFail($this->_request->input('report_id'))->setFixedStatus()
        );
    }

    public function event()
    {
        return response()->json(
            ErrorReport::findOrFail($this->_request->input('report_id'))->newEvent(
                $this->_request->input('event_text'),
                Auth::user()
            )
        );
    }
}
