<?php

namespace App\Http\Resources;

use App\Models\EventPass;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventForMargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "query" => Term::query()->find($this->pivot->term_id)->term_name,
            "term" => EventPass::query()->find($this->pivot->event_pass_id)->name,
            "nb_docs_com" => $this->pivot->nb_docs_com,
            "calc" => (double) number_format((float)$this->pivot->sim_te, 5, '.', ''),
        ];
    }
}
