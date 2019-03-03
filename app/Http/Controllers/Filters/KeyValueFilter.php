<?php
/**
 * KeyValueFilter
 * @author    bstorz@designsensory.com
 */

namespace App\Http\Controllers\Filters;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class KeyValueFilter
 * @package App\Http\Controllers\Filters
 */
class KeyValueFilter implements Filterable {

    protected $key;
    protected $value;

    /**
     * KeyValueFilter constructor.
     * @param $key
     * @param $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function applyToQuery(Builder $query): Builder
    {
        return $query->where($this->key, $this->value);
    }

    /**
     * @param callable $validator
     * @return bool
     */
    public function validate(callable $validator): bool
    {
        if($validator($this->key, $this->value)){
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        // TODO: Implement getLabel() method.
        return '';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        // TODO: Implement getName() method.
        return '';
    }
}
