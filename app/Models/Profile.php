<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $table = 'profiles';
    protected $fillable = [
        'full_name',
        'area',
        'specialty',
        'age',
        'email',
        'phone',
        'sex',
        'password',
        'nb_docs',
        'freq_max',
    ];
    protected $appends = [
        'area_fr',
        'count_documents',
        'count_terms',
    ];
    public function documents(){
        return $this->hasMany(Document::class);
    }
    public function terms(){
        return $this->belongsToMany(Term::class, 'profile_terms')
            ->withPivot('weight')
            ->using(ProfileTerm::class);
    }
    public function eventPasses(){
        return $this->belongsToMany(EventPass::class, 'profile_event_passes')
            ->using(ProfileEventPass::class);
    }
    public function termTerms(){
        return $this->belongsToMany(Term::class, 'profile_term_terms')
            ->withPivot(['term2_id', 'nb_docs_com', 'sim_tt'])
            ->using(ProfileTermTerm::class);
    }
    public function termEventPasses(){
        return $this->belongsToMany(EventPass::class, 'profile_term_event_passes')
            ->withPivot(['term_id','event_pass_id', 'nb_docs_com', 'sim_te'])
            ->using(ProfileTermEventPass::class);
    }

    public function getCountTermsAttribute()
    {
        return $this->terms()->count();
    }

    public function getCountDocumentsAttribute()
    {
        return $this->documents()->count();
    }

    public function getAreaFrAttribute()
    {
        switch ($this->attributes['area']){
            case "computer_science": return "L'informatique";
            case "policy": return "Politique";
            case "medicine": return "Médecine";
            case "sport": return "Sport";
        }
    }

}
