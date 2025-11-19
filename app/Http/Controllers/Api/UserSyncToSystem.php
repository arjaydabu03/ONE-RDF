<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\function\ResponseMessage;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Support\Facades\Http;

class UserSyncToSystem extends Controller
{
    use ApiResponse;
    public function store(Request $request)
    {
        $api = [
            "username" => $request->username,
            "password" => $request->password,
            "id_prefix" => $request->id_prefix,
            "id_no" => $request->id_no,
        ];

        $errors = [];

        foreach ($request->endpoint as $endpoint) {
            $response = Http::withOptions(["verify" => false])
                ->withHeaders(["api-key" => $endpoint["token"]])
                ->post($endpoint["url"], $api);

            if ($response->failed()) {
                $errors[] = [
                    "url" => $endpoint["url"],
                    "status" => $response->status(),
                    "body" => $response->body(),
                ];
                // $user_login = Auth()->user()->id;
                // $audit_trail = AuditTrail::create([
                //     "user_id" => $user_login,
                //     "action" => "Create",
                //     "module" => "AccountTitle Module",
                //     "details" => "created account " . $request->full_name,
                // ]);
            }
        }

        if (!empty($errors)) {
            return $this->responseConflictError(ResponseMessage::SERVER);
        }

        return $this->responseCreated(ResponseMessage::SYNC);
    }
}
