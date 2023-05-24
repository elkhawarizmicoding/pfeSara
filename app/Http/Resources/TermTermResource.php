<?php

namespace App\Http\Resources;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermTermResource extends JsonResource
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
            "name_term_1" => $this->term_name,
            "name_term_2" => Term::query()->find($this->pivot->term2_id)->term_name,
            "nb_docs_com" => $this->pivot->nb_docs_com,
            "sim_tt" => $this->pivot->sim_tt,
        ];
        //return parent::toArray($request);
    }
}
