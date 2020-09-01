<?php


namespace App\Traits;

use App\SmscampaignFile;
use App\SmscampaignPlanning;
use App\SmscampaignPlanningResult;
use App\SmscampaignReceiver;
use App\SmscampaignStatus;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Boolean;

trait SmscampaignTrait
{
    public function importFile(SmscampaignFile $campaignfile) {
        $pendingfiles_dir = config('app.smscampaigns_filesfolder');
        $raw_dir = config('app.RAW_FOLDER');
        $file_fullpath = $pendingfiles_dir.'/'.$campaignfile->name;
        $campaign = $campaignfile->campaign;

        $csvData = file_get_contents($raw_dir.'/'.$file_fullpath); // separateur_colonnes
        //$rows = array_map("str_getcsv", explode($campaign->separateur_colonnes, $csvData));
        $rows = array_map("str_getcsv", explode("\n", $csvData));

        $campaignfile->nb_rows = count($rows);
        $campaignfile->nb_rows_imported = 0;
        $campaignfile->nb_rows_failed = 0;

        // planning - attente traitement (1)
        $planning = new SmscampaignPlanning();
        $planning->plan_at = Carbon::now();
        $planning->smscampaign_id = $campaign->id;
        $planning->smscampaign_status_id = 2;
        $planning->stat_all = 0;

        $report = "";

        foreach ($rows as $row) {
            $receiver = new SmscampaignReceiver();
            $msg = "";

            // planning result
            $planning_result_valdone = $this->getParameters($row[0],$campaign,$receiver,$msg,$report);

            if ($planning_result_valdone) {

                if ($planning->stat_all == 0) {
                    $planning->save();
                }
                $planning->stat_all += 1;

                // New planning result
                $planning_result = SmscampaignPlanningResult::create([
                    'message' => $msg,
                    'smscampaign_planning_id' => $planning->id,
                    'smscampaign_receiver_id' => $receiver->id,
                ]);
                $campaignfile->nb_rows_imported += 1;
            } else {
                $campaignfile->nb_rows_failed += 1;
            }
        }

        if ($campaignfile->nb_rows_imported == $campaignfile->nb_rows) {
            // fichier importé avec succès
            $campaign->smscampaign_status_id = SmscampaignStatus::coded("4")->first()->id;
        } elseif ($campaignfile->nb_rows_imported > 0) {
            // fichier importé avec erreurs
            $campaign->smscampaign_status_id = SmscampaignStatus::coded("5")->first()->id;
        } else {
            // échec importation fichier
            $campaign->smscampaign_status_id = SmscampaignStatus::coded("6")->first()->id;
        }
        $campaign->save();

        // file
        $campaignfile->import_report = $report;
        $campaignfile->imported = 1;
        $campaignfile->imported_at = Carbon::now();
        $campaignfile->save();
    }

    private function parseMsg($msg_in, &$msg_out, &$report): Boolean {
        $parse_result = false;
        if (empty($msg_in)) {
            $msg_out = $msg_in;
            $report = $this->addToReport($report, "le message de SMS est vide");
        } else {
            $msg_out = $msg_in;
            $report = $this->addToReport($report, "message recupere avec succes");
        }
        return $parse_result;
    }

    private function parseMobile($mobile, &$receiver, &$report): Boolean {
        $mobile_local = substr($mobile, -8);
        $parse_result = false;
        if (is_numeric($mobile_local)) {
            $receiver = SmscampaignReceiver::updateOrCreate([
                'mobile' => $mobile,
            ]);
            $parse_result = true;
            $report = $this->addToReport($report, "numeros recupere avec succes");
        } else {
            $receiver = null;
            $report = $this->addToReport($report, "le numero " . $mobile . "n est pas valide");
        }
        return $parse_result;
    }

    private function getParameters($row, $campaign, &$receiver, &$msg, &$report): Boolean {
        $receiver = new SmscampaignReceiver();
        $report = "";
        $parameters_ok = false;

        if ($campaign->type->code == "1") {
            // Messages individuels
            if (strpos($row, $campaign->separateur_colonnes) === false) {
                $report = $this->addToReport($report, "Separateur de colonnes non trouve!");
            } else {
                $row_tab = explode($campaign->separateur_colonnes, $row);
                $parameters_ok = $this->parseMobile($row_tab[0], $receiver, $report);
                if ($parameters_ok) {
                    $parameters_ok = $this->parseMsg($row_tab[1], $msg, $report);
                }
            }
        } else {
            // Message commun OU Campagne Mixte
            if (strpos($row, $campaign->separateur_colonnes) === false) {
                $parameters_ok = $this->parseMobile($row, $receiver, $report);
                if ($parameters_ok) {
                    $parameters_ok = $this->parseMsg($campaign->message, $msg, $report);
                }
            } else {
                $row_tab = explode($campaign->separateur_colonnes, $row);
                $parameters_ok = $this->parseMobile($row_tab[0], $receiver, $report);

                if ($parameters_ok) {
                    if ($campaign->type->code == "0") {
                        // Message commun
                        $parameters_ok = $this->parseMsg($campaign->message, $msg, $report);
                    } else {
                        // Campagne Mixte
                        $parameters_ok = $this->parseMsg($row_tab[1], $msg, $report);
                        if (!$parameters_ok) {
                            // si le message dans le fichier est empty, on essaie de passer le message de la campagne (commun)
                            $parameters_ok = $this->parseMsg($campaign->message, $msg, $report);
                        }
                    }
                }
            }
        }
        return $parameters_ok;
    }

    private function addToReport($report, $msg) {
        $report_tab = explode(';', $report);
        $msg_found = false;
        for ($i = 0; $i < count($report_tab); $i++) {
            if (strpos($report_tab[$i], $msg) !== false) {
                $report_line_tab = explode('(', $report_tab[$i]);
                if (count($report_line_tab) == 1) {
                    $report_line_count = 1;
                } else {
                    $report_line_count = (int) str_replace([')',' '], ['',''], $report_line_tab[1]);
                }
                $report_line_count = $report_line_count + 1;
                $report_tab[$i] = $msg . ' (' . $report_line_count . ')';

                $msg_found = true;
                break;
            }
        }
        $report = implode(';', $report_tab);
        if (!$msg_found) {
            $report = $report . ';' . $msg;
        }
        return $report;
    }
}
