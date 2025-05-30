<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class AccountTitleFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = ["code", "name"];

    protected array $relationSearch = [
        "account_group" => ["name"],
        "account_sub_group" => ["name"],
        "account_unit" => ["name"],
        "account_type" => ["name"],
        "normal_balance" => ["name"],
        "financial_statement" => ["name"],
    ];
}
