<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function index () {
        return view('admin.main_dashboard', [
            'mainHeader' => 'Dashboard',
            'subHeader' => 'Manage your pharmacy operations efficiently',
            'title' => 'Dashboard'
        ]);
    }
}
