<?php
/**
 * Filter
 * @author    bstorz@designsensory.com
 */

namespace App\Http\Controllers\Filters;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Filterable
 * @package App\Http\Controllers\Filters
 */
interface Filterable {
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyToQuery(Builder $query) : Builder;

    /**
     * @param callable $validator
     * @return bool
     */
    public function validate(callable $validator) : bool;

    /**
     * @return string
     */
    public function getLabel() : string;

    /**
     * @return string
     */
    public function getName() : string;
}
