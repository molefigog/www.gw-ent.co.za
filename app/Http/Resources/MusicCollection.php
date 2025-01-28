<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MusicCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($track) {
                return [
                    'id' => $track->id,
                    'title' => $track->title,
                    'artist' => $track->artist,
                    'duration' => $track->duration,
                    'size' => $track->size,
                    'img' => $track->img,

                    'buttonType' => $track->getButtonType(),
                    'genre_title' => $track->genre_title,
                    'amount' => $track->amount,
                    'demo' => $track->preview,
                    'file' => $track->product,
                    'description' => $track->description,
                    'downloads' => $track->downloads,
                    'slug' => $track->slug,
                    'free' => $track->free,
                    'beat' => $track->beat,
                    'publish' => $track->publish,
                ];
            }),
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ],
        ];
    }
}
