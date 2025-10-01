<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\function\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Requests\Customer\StoreRequest;

class CustomerController extends Controller
{
    use ApiResponse;
    public function index(StatusRequest $request)
    {
        $status = $request->status;

        $customer = Customer::when($status === "inactive", function (
            $query
        ) use ($status) {
            return $query->onlyTrashed();
        })
            ->useFilters()
            ->dynamicPaginate();

        if ($customer->isEmpty()) {
            return $this->responseNotFound("Nothing to display.");
        }

        return $this->responseSuccess(ResponseMessage::DISPLAY, $customer);
    }
    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return $this->responseNotFound("Nothing to display.");
        }

        return $this->responseSuccess(ResponseMessage::DISPLAY, $customer);
    }

    public function store(StoreRequest $request)
    {
        $customer = Customer::create([
            "code" => $request->code,
            "name" => $request->name,
            "business_name" => $request->business_name,
            "registration_status" => $request->registration_status,
            "contact_no" => $request->contact_no,
            "email_address" => $request->email_address,
            "house_no" => $request->house_no,
            "street_name" => $request->street_name,
            "barangay_name" => $request->barangay_name,
            "city" => $request->city,
            "province" => $request->province,
            "customer_type" => $request->customer_type,
            "cluster_id" => $request->cluster_id,
            "cluster_name" => $request->cluster_nameame,
            "terms" => $request->terms,
        ]);

        // $user_login = Auth()->user()->id;
        // $audit_trail = AuditTrail::create([
        //     "user_id" => $user_login,
        //     "action" => "Create",
        //     "module" => "Customer Module",
        //     "details" => "created account " . $request->full_name,
        // ]);
        return $this->responseCreated(ResponseMessage::CREATE, $customer);
    }

    public function update(StoreRequest $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return $this->responseNotFound("Nothing to update.");
        }

        $customer->update([
            "code" => $request->code,
            "name" => $request->name,
            "business_name" => $request->business_name,
            "registration_status" => $request->registration_status,
            "contact_no" => $request->contact_no,
            "email_address" => $request->email_address,
            "house_no" => $request->house_no,
            "street_name" => $request->street_name,
            "barangay_name" => $request->barangay_name,
            "city" => $request->city,
            "province" => $request->province,
            "customer_type" => $request->customer_type,
            "cluster_id" => $request->cluster_id,
            "cluster_name" => $request->cluster_nameame,
            "terms" => $request->terms,
            // "last_update_by" => Auth::user()->full_name,
        ]);

        return $this->responseSuccess(ResponseMessage::UPDATE, $customer);
    }
    public function destroy($id)
    {
        $customer = Customer::where("id", $id)
            ->withTrashed()
            ->get();

        if ($customer->isEmpty()) {
            return $this->responseNotFound("Nothing to display.");
        }

        $customer = Customer::withTrashed()->find($id);
        $is_active = Customer::withTrashed()
            ->where("id", $id)
            ->first();
        if (!$is_active) {
            return $is_active;
        } elseif (!$is_active->deleted_at) {
            $customer->delete();
            return $this->responseDeleted();
        } else {
            $customer->restore();
            $message = ResponseMessage::RESTORE;
        }

        return $this->responseSuccess($message, $customer);
    }

    public function import(ImportRequest $request)
    {
        $import = $request->all();
        $department = Customer::upsert($import, ["code"], ["name"]);

        return $this->responseSuccess("Imported Sucessfully.", $import);
    }
}
