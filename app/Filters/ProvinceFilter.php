<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class ProvinceFilter extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = [];

    protected array $relationSearch = [
        "region" => ["psgc_id"],
    ];
}
