<?php

namespace App;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int          id
 * @property int          time_spent
 * @property int          project_id
 * @property int          user_id
 * @property string       created_at
 * @property string       updated_at
 * @property string       deleted_at
 * @property \App\User    user
 * @property \App\Project project
 */
class TimeEntry extends Model {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param string $time_spent
     * @throws \Exception
     */
    public function setTimeSpentAttribute(string $time_spent): void
    {
        $interval = $this->timeToInterval($time_spent);
        $this->attributes['time_spent'] = $interval->totalSeconds;
    }

    /**
     * @param $time
     * @return CarbonInterval
     * @throws \Exception
     */
    protected function timeToInterval(string $time): CarbonInterval
    {
        return new CarbonInterval($time);
    }

    /**
     * @return CarbonInterval
     * @throws \Exception
     */
    public function getTimeSpentAttribute(): CarbonInterval
    {
        return $this->timeToInterval($this->time_spent);
    }
}
