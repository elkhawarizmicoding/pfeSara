<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfileEventPass extends Pivot
{
    use HasFactory;
    protected $table = 'profile_event_passes';
    protected $fillable = [
        'weight',
        'np_docs',
    ];
}
