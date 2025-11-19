<?php

namespace App\Http\Controllers\Api;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\function\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Requests\Supplier\StoreRequest;

class SupplierController extends Controller
{
    use ApiResponse;
    public function index(StatusRequest $request)
    {
        $status = $request->status;

        $supplier = Supplier::when($status === "inactive", function (
            $query
        ) use ($status) {
            return $query->onlyTrashed();
        })
            ->useFilters()
            ->dynamicPaginate();

        if ($supplier->isEmpty()) {
            return $this->responseNotFound("Nothing to display.");
        }

        return $this->responseSuccess(ResponseMessage::DISPLAY, $supplier);
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return $this->responseNotFound("Nothing to display.");
        }

        return $this->responseSuccess(ResponseMessage::DISPLAY, $supplier);
    }

    public function store(StoreRequest $request)
    {
        $supplier = Supplier::create([
            "code" => $request->code,
            "name" => $request->name,
            "terms" => $request->terms,
            "type" => $request->type,
            "address" => $request->address,
            "email" => $request->email,
            "contact_no" => $request->contact_no,
        ]);

        // $user_login = Auth()->user()->id;
        // $audit_trail = AuditTrail::create([
        //     "user_id" => $user_login,
        //     "action" => "Create",
        //     "module" => "Supplier Module",
        //     "details" => "created account " . $request->full_name,
        // ]);
        return $this->responseCreated(ResponseMessage::CREATE, $supplier);
    }

    public function update(StoreRequest $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return $this->responseNotFound("Nothing to update.");
        }

        $supplier->update([
            "code" => $request->code,
            "name" => $request->name,
            "terms" => $request->terms,
            "type" => $request->type,
            "address" => $request->address,
            "email" => $request->email,
            "contact_no" => $request->contact_no,
            // "last_update_by" => Auth::user()->full_name,
        ]);

        return $this->responseSuccess(ResponseMessage::UPDATE, $supplier);
    }
    public function destroy($id)
    {
        $supplier = Supplier::where("id", $id)
            ->withTrashed()
            ->get();

        if ($supplier->isEmpty()) {
            return $this->responseNotFound("Nothing to display.");
        }

        $supplier = Supplier::withTrashed()->find($id);
        $is_active = Supplier::withTrashed()
            ->where("id", $id)
            ->first();
        if (!$is_active) {
            return $is_active;
        } elseif (!$is_active->deleted_at) {
            $supplier->delete();
            return $this->responseDeleted();
        } else {
            $supplier->restore();
            $message = ResponseMessage::RESTORE;
        }

        return $this->responseSuccess($message, $supplier);
    }
    // public function export()
    // {
    //     $export = Excel::download(new CompaniesExport(), "supplier.csv");

    //     if (!$export) {
    //         return $this->responseUnprocessable("Export failed.");
    //     }

    //     return $this->responseSuccess(ResponseMessage::EXPORT);
    // }

    // public function import(Request $request)
    // {
    //     $company_file = $request->file("file");

    //     if (!$company_file) {
    //         return $this->responseUnprocessable("File not found.");
    //     }
    //     Excel::import(new CompanyImport(), $company_file);

    //     return $this->responseSuccess(ResponseMessage::IMPORT);
    // }
}
