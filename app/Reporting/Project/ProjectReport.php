<?php

namespace App\Reporting\Project;


use App\Http\Controllers\AutoQueue\Reports\BaseReport;
use App\TimeEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Displays how much time each user spent on a given project in a given time period.
 * Class ProjectReport
 * @package App\Reporting\Project
 */
class ProjectReport extends BaseReport {
    /**
     * @return Model
     */
    protected function getBaseClass(): Model
    {
        return new TimeEntry();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function updateQuery(Builder $query): Builder
    {
        return parent::updateQuery($query)->with(['user']);
    }

    /**
     * @param Collection $data
     * @return Collection
     */
    protected function parseData(Collection $data): Collection
    {
        $data = parent::parseData($data);
        $output = collect();

        // Group by user id
        $data->groupBy('user_id')
            // Iterate through each user's records
             ->each(function ($item, $key) use ($output) {
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

        // Return the report
        return $output;
    }
}
