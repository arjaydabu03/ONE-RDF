<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class DepartmentFilter extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = ["code","name"];
}
