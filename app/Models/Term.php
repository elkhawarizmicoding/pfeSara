<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $table = 'terms';
    protected $fillable = ['term_name'];

    public function profiles(){
        return $this->belongsToMany(Profile::class, 'profile_terms')
            ->using(ProfileTerm::class);
    }
    public function documents(){
        return $this->belongsToMany(Document::class, 'document_terms')
            ->using(DucumentTerm::class);
    }

    public function terms(){
        return $this->belongsToMany(Term::class, 'profile_term_terms')
            ->using(ProfileTermTerm::class);
    }
    public function eventPasses(){
        return $this->belongsToMany(EventPass::class, 'profile_term_event_passes')
            ->using(ProfileTermEventPass::class);
    }

}
