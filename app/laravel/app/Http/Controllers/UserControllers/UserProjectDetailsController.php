<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProjectDetailsController extends Controller
{
    public function index($project = null)
    {
        return view('user.user-project');
    }
}
