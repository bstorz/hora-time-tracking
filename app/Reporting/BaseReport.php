<?php
/**
 * MissedRunsReport
 * @author    bstorz@designsensory.com
 */

namespace App\Http\Controllers\AutoQueue\Reports;

use App\Http\Controllers\Filters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseReport
 * @package App\Http\Controllers\AutoQueue\Reports
 */
abstract class BaseReport implements Reportable {
    /**
     * @var Filterable[]
     */
    protected $filters = [];

    /**
     * @return Filterable[]
     */
    protected function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return \App\Http\Controllers\AutoQueue\Reports\Reportable
     */
    public function setFilters(array $filters): Reportable
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        $query = $this->buildDataQuery($this->getFilters());
        $data = $query->get();
        return $this->parseData($data);
    }

    /**
     * @param Filterable[] $filters
     * @return Builder
     */
    protected function buildDataQuery(array $filters): Builder
    {
        $query = $this->getBaseClass()->newModelQuery();
        $query = $this->applyFilters($query, $filters);
        $query = $this->updateQuery($query);
        return $query;
    }

    /**
     * @param Collection $data
     * @return Collection
     */
    protected function parseData(Collection $data): Collection
    {
        return $data;
    }

    /**
     * @return Model
     */
    abstract protected function getBaseClass(): Model;

    /**
     * @param Builder      $query
     * @param Filterable[] $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter) {
            $query = $this->applyFilter($query, $filter);
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function updateQuery(Builder $query): Builder
    {
        return $query;
    }

    /**
     * @param Builder    $query
     * @param Filterable $filter
     * @return Builder
     */
    protected function applyFilter(Builder $query, Filterable $filter): Builder
    {
        return $filter->applyToQuery($query);
    }
}
