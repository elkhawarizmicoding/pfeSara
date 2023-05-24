<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Term;
use App\Services\PfeService;

class TestController
{
    public function test($query){
        $term = Term::query()->where('term_name', $query)->first();
        $tProfile = Profile::with('terms.documents')->find(1);
        $termT = $term;
        //return response()->json($tProfile->terms->where('term_name', $query));
        $term = $tProfile->terms->where('term_name', $term->term_name)->first();

        dd($termT->toArray(), $term->toArray());
    }
}
