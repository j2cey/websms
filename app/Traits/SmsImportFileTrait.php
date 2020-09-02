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

        //$this->nb_rows = count($rows);
        //$this->nb_rows_imported = 0;
        //$this->nb_rows_failed = 0;

        $row_current = 1;
        //foreach ($rows as $row) {
        for ($i = 0; $i < $this->nb_rows; $i++) {
            $row_current = $i + 1;
            $row = $rows[$i];
            if ($row_current > $this->row_last_processed) {
                $receiver = new SmscampaignReceiver();
                $msg = "";

                // récuration des paramètres de la ligne
                $row_parse_ok = $this->getParameters($row_current, $row[0], $campaign, $receiver, $msg);

                if ($row_parse_ok) {
                    // incrément des lignes a traiter dans la planning
                    $planning->stat_all += 1;
                    // New planning result
                    $planning_result = SmscampaignPlanningResult::create([
                        'message' => $msg,
                        'smscampaign_planning_id' => $planning->id,
                        'smscampaign_receiver_id' => $receiver->id,
                    ]);
                    $this->nb_rows_imported += 1;
                } else {
                    $this->nb_rows_failed += 1;
                }

                // MAJ du SmscampaingFile
                $this->row_last_processed = $row_current;
                $this->setStatus();

                // MAJ du SmscampaignPlanning
                //$planning->setStatus();

                // MAJ du Smscampaign
                //$campaign->setStatus();

                // Next Row
                //$row_current += 1;
            }
        }
    }

    private function parseMsg($msg_in, &$msg_out, &$report_msg) {
        $parse_result = false;
        if (empty($msg_in)) {
            $msg_out = $msg_in;
            //$this->addToReport($row_current,"le message de SMS est vide");
            $report_msg = "le message de SMS est vide";
        } else {
            $msg_out = $msg_in;
            $parse_result = true;
            //$this->addToReport($row_current,"message recupere avec succes");
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
            //$this->addToReport($row_current,"numeros recupere avec succes");
            $report_msg = "numeros recupere avec succes";
        } else {
            $receiver = null;
            //$this->addToReport($row_current,"le numero " . $mobile . "n est pas valide");
            $report_msg = "le numero " . $mobile . " n'est pas valide";
        }
        return $parse_result;
    }

    private function getParameters($row_current, $row, $campaign, &$receiver, &$msg) {
        $receiver = new SmscampaignReceiver();
        $parameters_ok = false;

        $report_msg = "";
        if ($campaign->type->code == "1") {
            // Messages individuels
            if (strpos($row, $campaign->separateur_colonnes) === false) {
                $this->addToReport($row_current, "Separateur de colonnes non trouve!");
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
        }
        $this->addToReport($row_current,$report_msg);
        return $parameters_ok;
    }
}
