<?php

namespace App\Casts;

use App\IdeaStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class IdeaStatusCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?IdeaStatus
    {
        if ($value === null) {
            return null;
        }

        return IdeaStatus::fromStored((string) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof IdeaStatus) {
            return $value->value;
        }

        return IdeaStatus::fromStored((string) $value)->value;
    }
}
