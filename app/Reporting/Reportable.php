<?php
/**
 * Reportable.php
 * User: bstorz
 * Date: 2019-03-02
 */

namespace App\Http\Controllers\AutoQueue\Reports;


use App\Http\Controllers\Filters\Filterable;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BaseReport
 * @package App\Http\Controllers\AutoQueue\Reports
 */
interface Reportable {
    /**
     * @return Collection
     */
    public function getData(): Collection;

    /**
     * @param Filterable[] $filters
     * @return \App\Http\Controllers\AutoQueue\Reports\Reportable
     */
    public function setFilters(array $filters): Reportable;
}
