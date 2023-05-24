<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            "full_name" => $this->full_name,
            "email" => $this->email,
            "phone" => $this->phone,
            "area" => $this->area_fr,
            "age" => $this->age,
            "sex" => $this->sex,
            "nb_docs" => $this->nb_docs,
            "freq_max" => $this->sex,
            "count_terms" => $this->count_terms,
            "terms" => TermResource::collection($this->terms),
            "term_terms" => TermTermResource::collection($this->termTerms),
            "event_passes" => EventPassResource::collection($this->eventPasses),
            "term_event_passes" => TermEventPassResource::collection($this->termEventPasses),
        ];
    }
}
