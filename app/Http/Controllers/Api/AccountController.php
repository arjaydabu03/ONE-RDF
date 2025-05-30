<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use App\function\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\LoginResource;
use App\Http\Resources\AccountResource;
use App\Http\Requests\Accoount\StoreRequest;
use App\Http\Requests\Account\ChangeRequest;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    use ApiResponse;
    public function index(StatusRequest $request)
    {
        $status = $request->status;
        $users = User::when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })
            ->useFilters()
            ->dynamicPaginate();

        if ($users->isEmpty()) {
            return $this->responseNotFound("Nothing to display.");
        }

        AccountResource::collection($users);

        return $this->responseSuccess(ResponseMessage::DISPLAY, $users);
    }
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->responseNotFound("Nothing to display.");
        }
        $user_collect = new AccountResource($user);
        return $this->responseSuccess(ResponseMessage::DISPLAY, $user_collect);
    }
    public function store(StoreRequest $request)
    {
        $access_permission = $request->access_permission;
        $accessConvertedToString = implode(",", $access_permission);
        $user = User::create([
            "full_name" => $request->full_name,
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "access_permission" => $accessConvertedToString,
        ]);
        // $user_login = Auth()->user()->id;
        // $audit_trail = AuditTrail::create([
        //     "user_id" => $user_login,
        //     "action" => "Create",
        //     "module" => "Account Module",
        //     "details" => "created account " . $request->full_name,
        // ]);

        $user_collect = new AccountResource($user);

        return $this->responseCreated(ResponseMessage::CREATE, $user_collect);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $access_permission = $request->access_permission;
        $accessConvertedToString = implode(",", $access_permission);

        if (!$user) {
            return $this->responseNotFound("Nothing to display.");
        }
        $user->update([
            "full_name" => $request->full_name,
            "username" => $request->username,
            "access_permission" => $accessConvertedToString,
            // "last_update_by" => Auth::user()->full_name,
        ]);
        $user_collect = new AccountResource($user);
        return $this->responseSuccess(ResponseMessage::UPDATE, $user_collect);
    }
    public function destroy($id)
    {
        $user = User::where("id", $id)
            ->withTrashed()
            ->get();

        if ($user->isEmpty()) {
            return $this->responseNotFound("Nothing to display.");
        }

        $user = User::withTrashed()->find($id);
        $is_active = User::withTrashed()
            ->where("id", $id)
            ->first();
        if (!$is_active) {
            return $is_active;
        } elseif (!$is_active->deleted_at) {
            $user->delete();
            return $this->responseDeleted();
        } else {
            $user->restore();
            $message = ResponseMessage::RESTORE;
        }
        $user_collect = new AccountResource($user);
        return $this->responseSuccess($message, $user_collect);
    }
    public function login(Request $request)
    {
        $user = User::where("username", $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->responseUnauthorized();
        }
        $token = $user->createToken("PersonalAccessToken")->plainTextToken;

        $user["token"] = $token;
        $cookie = cookie("onerdftoken", $token);

        $user = new LoginResource($user);
        return $this->responseSuccess("Login Success", $user)->withCookie(
            $cookie
        );
    }
    public function logout(Request $request)
    {
        Auth()
            ->user()
            ->currentAccessToken()
            ->delete();
        return $this->responseSuccess("Logout Success");
    }

    public function reset_password(Request $request, $id)
    {
        $user = User::find($id);

        $new_password = Hash::make($user->username);

        $user->update([
            "password" => $new_password,
        ]);

        return $this->responseSuccess("Password has been reset.");
    }

    public function change_password(ChangeRequest $request)
    {
        $id = Auth::id();
        $user = User::find($id);

        if ($user->username == $request->password) {
            throw ValidationException::withMessages([
                "password" => ["Please change your password."],
            ]);
        }
        $user->update([
            "password" => Hash::make($request["password"]),
        ]);
        return $this->responseSuccess("Password has been change.");
    }
}
