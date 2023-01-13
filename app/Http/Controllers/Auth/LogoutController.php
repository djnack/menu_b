<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function index(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return Response()->json(['status' => 200, 'redirect' => 'home'], 200);
    }
}