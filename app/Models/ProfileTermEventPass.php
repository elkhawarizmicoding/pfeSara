<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfileTermEventPass extends Pivot
{
    use HasFactory;
    protected $table = 'profile_term_event_passes';
    protected $fillable = [
        'profile_id',
        'term_id',
        'event_pass_id',
        'nb_docs_com',
        'sim_te',
    ];
}
