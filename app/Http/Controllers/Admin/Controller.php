<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    protected function validateRequest(Request $request, array $rules)
    {
        return Validator::make($request->all(), $rules)->validate();
    }

    protected function withSuccessMessage($message)
    {
        return redirect()->back()->with('success', $message);
    }

    protected function withErrorMessage($message)
    {
        return redirect()->back()->with('error', $message);
    }
}
