<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountTitleResource extends JsonResource
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
            "account_group_name" => $this->account_group->name,
            "account_sub_group_name" => $this->account_sub_group->name,
            "account_unit_name" => $this->account_unit->name,
            "account_type_name" => $this->account_type->name,
            "financial_statment_name" => $this->financial_statement->name,
            "normal_balance_name" => $this->normal_balance->name,
            "credit_code" => $this->credit->code,
            "credit_name" => $this->credit->name,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
        ];
    }
}
