<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\Company;

class CompanyScope implements Scope
{
    protected string $column;

    public function __construct(string $column = 'company_id')
    {
        $this->column = $column;
    }

    public function apply(Builder $builder, Model $model): void
    {
        $companyIds = session('active_company_ids');
        $user = auth()->user();

        if (!$companyIds || empty($companyIds)) {
            if ($user?->isCompanyAdmin()) {
                $companyIds = $user->managedCompanyIds();
            } else {
                return; // Admin sees all
            }
        }

        $builder->whereIn($this->column, $companyIds);
    }
}
