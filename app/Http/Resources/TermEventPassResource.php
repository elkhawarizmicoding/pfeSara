<?php

namespace App\Http\Resources;

use App\Models\EventPass;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermEventPassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name_term" => Term::query()->find($this->pivot->term_id)->term_name,
            "name_event" => EventPass::query()->find($this->pivot->event_pass_id)->name,
            "nb_docs_com" => $this->pivot->nb_docs_com,
            "sim_te" => $this->pivot->sim_te,
        ];
    }
}
