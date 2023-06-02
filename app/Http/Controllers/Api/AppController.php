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
    public function inscription(Request $request){
        $request->validate([
            'full_name' => 'required',
            'phone' => 'required|unique:profiles',
            'email' => 'required|email|unique:profiles',
            'age' => 'required',
            'sex' => 'required|in:women,man',
            'area' => 'required|in:computer_science,policy,medicine,sport,economy',
        ]);
        return response()->json((new PfeService())->inscription($request->toArray()));
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
    public function updateProfile(Request $request){
        $request->validate([
            'email' => 'required|email',
            'full_name' => 'required',
            'phone' => 'required',
            'age' => 'required',
            'sex' => 'required|in:women,man',
        ]);
        return response()->json((new PfeService())->updateProfile($request->toArray()));
    }

}
