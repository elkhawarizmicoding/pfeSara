<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $docs = count($this->documents);
        return [
            'name' => ucfirst(strtolower($this->term_name)),
            'docs' => $docs < 10 ? "0{$docs}" : $docs,
            'weight' => $this->pivot->weight,
        ];
    }
}
