<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfileTermTerm extends Pivot
{
    use HasFactory;
    protected $table = 'profile_term_terms';
    protected $fillable = [
        'profile_id',
        'term_id',
        'term2_id',
        'nb_docs_com',
        'sim_tt',
    ];
}
