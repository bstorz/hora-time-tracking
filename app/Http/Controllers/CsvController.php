<?php
/**
 * CsvController
 * @author    bstorz@designsensory.com
 */

namespace App\Http\Controllers;

/**
 * Class CsvController
 * @package App\Http\Controllers
 */
class CsvController {
    /**
     * @param string $filename
     * @param array  $data
     */
    public function outputCsv(string $filename, array $data): void
    {
        $this->outputCsvHeaders($filename);
        $this->outputCsvBody($data);
    }

    /**
     * @param string $filename
     */
    private function outputCsvHeaders(string $filename): void
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    /**
     * @param array $data
     */
    private function outputCsvBody(array $data): void
    {
        $outstream = fopen('php://output', 'wb');
        foreach ($data as $row) {
            fputcsv($outstream, $row);
        }
        fclose($outstream);
    }
}
