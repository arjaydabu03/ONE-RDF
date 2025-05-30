<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required",
            "code" => [
                "required",
                $this->route()->department
                    ? "unique:department,department," .
                        $this->route()->department
                    : "unique:department,code",
                // Rule::unique("department", "code")
                //     ->ignore($this->route("department"))
                //     ->where("business_unit", $business_unit),opspedo09
            ],
        ];
    }
}
