<?php

namespace App\Reporting\User;


use App\Http\Controllers\AutoQueue\Reports\BaseReport;
use App\TimeEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserReport
 * @package App\Reporting\User
 */
class UserReport extends BaseReport {
    /**
     * @return Model
     */
    public function getBaseClass(): Model
    {
        return new TimeEntry();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function updateQuery(Builder $query): Builder
    {
        // For a week, show all time for a user.
        return parent::updateQuery($query)->with('project');
    }

    /**
     * @param Collection $data
     * @return Collection
     */
    protected function parseData(Collection $data): Collection
    {
        $data = parent::parseData($data);
        $output = collect();

        // Group by project
        $data->groupBy('project_id')
                     ->each(function ($item, $key) use($output) {
                         // Grab the time spent
                         /** @var Collection|TimeEntry[] $item */
                         $time_spent = $item->sum('time_spent');

                         // Grab the first for its data
                         /** @var TimeEntry $first */
                         $first = $item->first();

                         // Add the user to the output
                         $new_item = collect();
                         $new_item['name'] = $first->user->name;
                         $new_item['time_spent'] = $time_spent;
                         $output->add($new_item);
                     });
        return $data;
    }
}
