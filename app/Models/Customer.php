<?php

namespace App\Models;

use App\Filters\CustomerFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    protected $table = "customer";

    protected string $default_filters = CustomerFilters::class;

    protected $fillable = ["code", "name", "last_updated_by"];
}
