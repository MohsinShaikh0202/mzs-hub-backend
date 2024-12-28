<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserList(Request $request){
        $getUserList = User::all();
        return json_encode($getUserList);
    }
}
