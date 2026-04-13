<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserTicketsController extends Controller
{
    public function index()
    {
        return view('user.user-tickets');
    }
}
