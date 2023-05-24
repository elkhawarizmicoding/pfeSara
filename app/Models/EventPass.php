<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPass extends Model
{
    use HasFactory;
    protected $table = 'event_passes';
    protected $fillable = [
        'name',
        'date',
        'lieu',
    ];
    public function profiles(){
        return $this->belongsToMany(Profile::class, 'profile_event_passes')
            ->using(ProfileEventPass::class);
    }
    public function documents(){
        return $this->belongsToMany(Document::class, 'document_event_passes')
            ->using(DocumentEventPass::class);
    }
    public function terms(){
        return $this->belongsToMany(Term::class, 'profile_term_event_passes')
            ->using(ProfileTermEventPass::class);
    }
}
