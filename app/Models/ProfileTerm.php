<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfileTerm extends Pivot
{
    use HasFactory;
    protected $table = 'profile_terms';
    protected $fillable = [
        'freq_total',
        'np_docs',
        'weight',
    ];
}
