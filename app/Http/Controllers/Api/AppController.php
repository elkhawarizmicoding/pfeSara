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
    public function search($mode, $query){
        $service = new PfeService();
        if($mode == 'classic_search'){
            //searchClassic
            return response()->json($service->searchClassic($query));
        }elseif($mode == 'personalized_search'){
            //searchPersonalized
            return response()->json($service->searchPersonalized($query));
        }
        return response()->json([
            'status' => false,
        ]);

    }


}
