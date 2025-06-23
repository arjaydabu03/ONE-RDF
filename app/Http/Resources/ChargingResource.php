<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargingResource extends JsonResource
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
            "code" => $this->code,
            "name" => $this->name,

            "company_id" => $this->company->id,
            "company_code" => $this->company->code,
            "company_name" => $this->company->name,

            "business_unit_id" => $this->business_unit->id,
            "business_unit_code" => $this->business_unit->code,
            "business_unit_name" => $this->business_unit->name,

            "department_id" => $this->department->id,
            "department_code" => $this->department->code,
            "department_name" => $this->department->name,

            "unit_id" => $this->department_unit->id,
            "unit_code" => $this->department_unit->code,
            "unit_name" => $this->department_unit->name,

            "sub_unit_id" => $this->sub_unit->id,
            "sub_unit_code" => $this->sub_unit->code,
            "sub_unit_name" => $this->sub_unit->name,

            "location_id" => $this->location->id,
            "location_code" => $this->location->code,
            "location_name" => $this->location->name,

            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
        ];
    }
}
