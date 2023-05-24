<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $fillable = [
        'title',
        'date',
        'url',
        'author',
        'profile_id',
    ];
    public function profile(){
        return $this->belongsTo(Profile::class);
    }
    public function terms(){
        return $this->belongsToMany(Term::class, 'document_terms')
            ->using(DucumentTerm::class);
    }
    public function eventPasses(){
        return $this->belongsToMany(EventPass::class, 'document_event_passes')
            ->using(DocumentEventPass::class);
    }
}
