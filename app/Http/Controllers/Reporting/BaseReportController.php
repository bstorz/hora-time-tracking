<?php
/**
 * BaseReportController
 * @author    bstorz@designsensory.com
 */

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\AutoQueue\Reports\Reportable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\Filters\Filterable;
use App\Http\Controllers\Filters\KeyValueFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Request;

/**
 * Class BaseReportController
 * @package App\Http\Controllers\Reporting
 */
abstract class BaseReportController extends Controller {
    /**
     * @param \Illuminate\Support\Facades\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request)
    {
        $data = $this->buildDataSet($request);
        return $this->buildView($data);
    }

    /**
     * @param Request $request
     * @return Collection
     */
    protected function buildDataSet(Request $request): Collection
    {
        $filters = $this->getFilters($request);
        $filters = $this->validateFilters($filters);
        return $this->getData($filters);
    }

    /**
     * @param Collection $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function buildView(Collection $data)
    {
        return view(static::getView(), [
            'data' => $data
        ]);
    }

    /**
     * Transforms request parameters into Filterables.  Override if you need more than key-value pairs.
     * @param Request $request
     * @return Filterable[]
     */
    protected function getFilters(Request $request): array
    {
        // Based on the request,
        $input = $this->getFiltersToParse($request);
        $filters = [];

        // Transform into Filterables.
        foreach ($input as $key => $value) {
            $filters[] = new KeyValueFilter($key, $value);
        }

        return $filters;
    }

    /**
     * @param Filterable[] $filters
     * @return Filterable[]
     */
    protected function validateFilters(array $filters): array
    {
        $invalid = [];
        foreach ($filters as $key => $filter) {
            // Run the validation function
            $valid = $this->validateFilter($filter);

            // Store the invalids
            if (!$valid) {
                $invalid[] = $key;
            }
        }

        // Strip em out
        foreach ($invalid as $key) {
            unset($filters[$key]);
        }

        // Return
        return $filters;
    }

    /**
     * @param Filterable[] $filters
     * @return Collection
     */
    protected function getData(array $filters): Collection
    {
        return static::getReport()
                     ->setFilters($filters)
                     ->getData();
    }

    /**
     * @return string
     */
    abstract protected static function getView(): string;

    /**
     * @param \Illuminate\Support\Facades\Request $request
     * @return array
     */
    protected function getFiltersToParse(Request $request): array
    {
        return $request->input('filters') ?: [];
    }

    /**
     * @return array
     */
    abstract public static function getValidFilterableColumns(): array;

    /**
     * @return \App\Http\Controllers\AutoQueue\Reports\Reportable
     */
    abstract protected static function getReport(): Reportable;

    /**
     * @param Request $request
     * @return void
     */
    public function download(Request $request): void
    {
        $data = $this->buildDataSet($request);

        $controller = new CsvController();
        $controller->outputCsv(static::getFilename(), $data->toArray());
    }

    /**
     * @return string
     */
    abstract protected static function getFilename(): string;

    /**
     * @param Filterable $filter
     * @return bool
     */
    protected function validateFilter(Filterable $filter): bool
    {
        return $filter->validate(function ($key, $value) {
            return \in_array($key, static::getValidFilterableColumns(), true);
        });
    }
}
