<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PfeService;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        return response()->json((new PfeService())->login($request->toArray()));
    }
    public function search($query){
        return response()->json((new PfeService())->search($query));
    }


}
