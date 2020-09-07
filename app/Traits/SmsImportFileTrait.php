<?php


namespace App\Traits;

use App\SmscampaignPlanningResult;
use App\SmscampaignReceiver;

trait SmsImportFileTrait
{
    use ReportableTrait;
    // SmscampaignFile $campaignfile
    public function importToPlannig() {
        $pendingfiles_dir = config('app.smscampaigns_filesfolder');
        $raw_dir = config('app.RAW_FOLDER');
        $file_fullpath = $pendingfiles_dir.'/'.$this->name;
        $planning = $this->planning;
        $campaign = $planning->campaign;

        $csvData = file_get_contents($raw_dir.'/'.$file_fullpath); // separateur_colonnes
        //$rows = array_map("str_getcsv", explode($campaign->separateur_colonnes, $csvData));
        $rows = array_map("str_getcsv", explode("\n", $csvData));

        //dd($this->report);

        $row_current = 1;
        //foreach ($rows as $row) {
        for ($i = 0; $i < $this->nb_rows; $i++) {
            $row_current = $i + 1;
            $row = $rows[$i];

            $can_process_line = ($row_current > $this->row_last_processed);
            if ($can_process_line) {
                $report_line = "";
                $report_line_result = 0;
                if ( $this->getReportLine($row_current, $report_line,true) ) {
                    // Cette ligne peut être traité si son dernier traitement a été un échec
                    $report_line_result = $report_line[1];
                    if ($report_line[1] < 0) {
                        $can_process_line = true;
                        $this->nb_rows_failed -= 1;
                    } else {
                        // line déjà traité avec succès, alors ...
                        // on la remet dans le rapport
                        $this->addToReport($row_current,$report_line[2],$report_line[1]);
                        // on assigne la dernière ligne traitée
                        $this->row_last_processed = $row_current;
                        $can_process_line = false;
                    }
                }

                echo("report line result: ");
                var_dump($report_line_result);
                echo("can process line: ");
                var_dump($can_process_line);
            }

            if ($can_process_line) {

                $receiver = new SmscampaignReceiver();
                $msg = "";

                // récuration des paramètres de la ligne
                $row_parse_ok = $this->getParameters($row_current, $row[0], $campaign, $receiver, $msg);

                if ($row_parse_ok) {
                    // incrément des lignes a traiter dans la planning
                    $planning->nb_to_import += 1;
                    // New planning result
                    $planning_result = SmscampaignPlanningResult::create([
                        'message' => $msg,
                        'smscampaign_planning_id' => $planning->id,
                        'smscampaign_receiver_id' => $receiver->id,
                        'report' => json_encode([]),
                    ]);
                    $this->nb_rows_imported += 1;
                } else {
                    $this->nb_rows_failed += 1;
                }

                // MAJ du SmscampaingFile
                $this->row_last_processed = $row_current;
                $this->setStatus();
            }
        }
        $this->nb_try += 1;
        $this->save();
    }

    private function parseMsg($msg_in, &$msg_out, &$report_msg) {
        $parse_result = false;
        if (empty($msg_in)) {
            $msg_out = $msg_in;
            $report_msg = "le message de SMS est vide";
        } else {
            $msg_out = $msg_in;
            $parse_result = true;
            $report_msg = "message recupere avec succes";
        }
        return $parse_result;
    }

    private function parseMobile($mobile, &$receiver, &$report_msg) {
        $mobile_local = substr($mobile, -8);
        $parse_result = false;
        if (is_numeric($mobile_local)) {
            $receiver = SmscampaignReceiver::updateOrCreate([
                'mobile' => $mobile,
            ]);
            $parse_result = true;
            $report_msg = "numeros recupere avec succes";
        } else {
            $receiver = null;
            $report_msg = "le numero " . $mobile . " n'est pas valide";
        }
        return $parse_result;
    }

    private function getParameters($row_current, $row, $campaign, &$receiver, &$msg) {
        $receiver = new SmscampaignReceiver();
        $parameters_ok = false;
        $parameters_result = -1;

        $report_msg = "";
        if ($campaign->type->code == "1") {
            // Messages individuels
            if (strpos($row, $campaign->separateur_colonnes) === false) {
                $report_msg = "Separateur de colonnes non trouve!";
            } else {
                $row_tab = explode($campaign->separateur_colonnes, $row);
                $parameters_ok = $this->parseMobile($row_tab[0], $receiver, $report_msg);
                if ($parameters_ok) {
                    $parameters_ok = $this->parseMsg($row_tab[1], $msg, $report_msg);
                }
            }
        } else {
            // Message commun OU Campagne Mixte
            if (strpos($row, $campaign->separateur_colonnes) === false) {
                $parameters_ok = $this->parseMobile($row, $receiver,$report_msg);
                if ($parameters_ok) {
                    $parameters_ok = $this->parseMsg($campaign->message, $msg,$report_msg);
                }
            } else {
                $row_tab = explode($campaign->separateur_colonnes, $row);
                $parameters_ok = $this->parseMobile($row_tab[0], $receiver,$report_msg);

                if ($parameters_ok) {
                    if ($campaign->type->code == "0") {
                        // Message commun
                        $parameters_ok = $this->parseMsg($campaign->message, $msg,$report_msg);
                    } else {
                        // Campagne Mixte
                        $parameters_ok = $this->parseMsg($row_tab[1], $msg,$report_msg);
                        if (!$parameters_ok) {
                            // si le message dans le fichier est empty, on essaie de passer le message de la campagne (commun)
                            $parameters_ok = $this->parseMsg($campaign->message, $msg,$report_msg);
                        }
                    }
                }
            }
        }

        if ($parameters_ok) {
            $report_msg = "succès importation";
            $parameters_result = 1;
        }
        $this->addToReport($row_current,$report_msg, $parameters_result);
        return $parameters_ok;
    }
}
