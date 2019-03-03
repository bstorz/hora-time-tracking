<?php
/**
 * UserReportController
 * @author    bstorz@designsensory.com
 */

namespace App\Http\Controllers\Reporting;


use App\Http\Controllers\AutoQueue\Reports\Reportable;
use App\Reporting\User\UserReport;

/**
 * Class UserReportController
 * @package App\Http\Controllers\Reporting
 */
class UserReportController extends BaseReportController {

    /**
     * @return string
     */
    protected static function getView(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public static function getValidFilterableColumns(): array
    {
        return [
            'time_spent',
            'project_id',
            'user_id'
        ];
    }

    /**
     * @return \App\Http\Controllers\AutoQueue\Reports\Reportable
     */
    protected static function getReport(): Reportable
    {
        return new UserReport();
    }

    /**
     * @return string
     */
    protected static function getFilename(): string
    {
        return 'user-time';
    }
}
