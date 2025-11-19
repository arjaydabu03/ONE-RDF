<?php

namespace App\Models;

use App\Filters\SupplierFilter;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use Filterable, HasFactory, SoftDeletes;

    protected $table = "suppliers";

    protected string $default_filters = SupplierFilter::class;

    protected $fillable = [
        "code",
        "name",
        "terms",
        "type",
        "address",
        "email",
        "contact_no",
        "last_update_by",
    ];
}
