<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomepagePostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'author' => $this->author,
            'user' => $this->user,
            'image' => $this->image,
            'content' => $this->content,
            'tags' => $this->tags,
            'comments_count' => $this->comments_count,
            'views_count' => $this->views_count,
            'likes_count' => $this->likes_count,
            'created_at' => $this->created_at,
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
                'slug' => $this->category->slug,
            ]
        ];
    }
}
