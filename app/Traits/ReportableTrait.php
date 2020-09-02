<?php


namespace App\Traits;


trait ReportableTrait
{
    private function addToReport($row_current, $msg) {
        if (empty($this->report)) {
            $this->report = json_encode([ ["".$row_current."",$msg,1],]);
        } else {
            $report_tab = json_decode($this->report);
            $msg_found = false;
            for ($i = 0; $i < count($report_tab); $i++) {
                if (strpos($report_tab[$i][1], $msg) !== false) {
                    $report_tab[$i][0] = $report_tab[$i][0] . "," .$row_current;
                    $report_tab[$i][2] = $report_tab[$i][2] + 1;
                    $msg_found = true;
                    break;
                }
            }

            if (!$msg_found) {
                $report_tab[] = ["".$row_current."",$msg,1];
            }
            $this->report = json_encode($report_tab);
        }
    }
}
