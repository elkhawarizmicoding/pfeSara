<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventPassViewResource extends JsonResource
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
            'name' => ucfirst(strtolower($this->name)),
            'lieu' => ucfirst(strtolower($this->lieu)),
            'date' => Carbon::parse($this->date)->format('d/m/Y h:i'),
            'docs' => $docs < 10 ? "0{$docs}" : $docs,
        ];
    }
}
