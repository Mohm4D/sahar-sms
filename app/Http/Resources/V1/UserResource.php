<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "first_name"    => $this->first_name,
            "last_name"     => $this->last_name,
            "mobile"        => $this->mobile,
            "email"         => $this->email,
            "status"        => $this->status,
            "role"          => $this->role,
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at,
        ];
    }
}
