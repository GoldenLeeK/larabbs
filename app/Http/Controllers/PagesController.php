<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller {
    public function root()
    {

        return view('pages.root');
    }

    public function permissionDenied()
    {
        if (config('administrator.permission')()) {
            return redirect(url(config('administrator.uri')), 302);
        }

        return view('pages.permission_denied');
    }
}
