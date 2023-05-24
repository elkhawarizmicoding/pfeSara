<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DocumentEventPass extends Pivot
{
    use HasFactory;
    protected $table = 'document_event_passes';
}
