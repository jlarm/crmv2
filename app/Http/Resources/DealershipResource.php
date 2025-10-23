<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Dealership;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Dealership
 */
final class DealershipResource extends JsonResource
{
    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     city: string,
     *     state: string,
     *     phone: string,
     *     status: string,
     *     rating: string
     * }
     */
    public function toArray(Request $request): array
    {
        /** @var Dealership $dealership */
        $dealership = $this->resource;

        return [
            'id' => $dealership->id,
            'name' => $dealership->name,
            'city' => $dealership->city,
            'state' => $dealership->state,
            'phone' => $dealership->phone,
            'status' => $dealership->status,
            'rating' => $dealership->rating,
        ];
    }
}
