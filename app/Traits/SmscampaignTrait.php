<?php


namespace App\Traits;


use App\SmscampaignFile;
use App\SmscampaignPlanning;
use App\SmsimportStatus;
use App\SmssendStatus;
use Illuminate\Support\Carbon;

trait SmscampaignTrait
{
    public function addFile($fullpathfile, $entete_premiere_ligne)
    {
        //turn into array
        $file = file($fullpathfile);

        if ($entete_premiere_ligne) {
            //remove first line
            $data = array_slice($file, 1);
        } else {
            $data = $file;
        }

        //loop through file and split every 1000 lines
        $file_max_line = 500;
        $parts = (array_chunk($data, $file_max_line));
        $parts_count = count($parts);

        if ($parts_count > 0) {

            // Nouveau planning (en attente importation fichiers)
            $new_planning = SmscampaignPlanning::create([
                'plan_at' => Carbon::now(), // TODO: récupérer la date de planification
                'smscampaign_id' => $this->id,
                'smsimport_status_id' => SmsimportStatus::coded("0")->first()->id,
                'smssend_status_id' => SmssendStatus::coded("0")->first()->id,
                'current' => true,
            ]);

            $i = 1;
            $nb_rows_all = 0;
            $pendingfiles_dir = config('app.smscampaigns_filesfolder');
            foreach ($parts as $line) {
                $filename = $this->id . '_' . str_replace(['-',' ',':'],"",gmdate('Y-m-d h:i:s')) . '_' . $i . '.csv';
                $filename_full = $pendingfiles_dir . '/' . $filename;

                file_put_contents($filename_full, $line);
                $i++;

                $nb_rows_curr = intval(exec("wc -l '" . $filename_full . "'"));
                $new_file = SmscampaignFile::create([
                    'name' => $filename,
                    'smscampaign_planning_id' => $new_planning->id,
                    'nb_rows' => $nb_rows_curr,
                    'smsimport_status_id' => SmsimportStatus::coded("1")->first()->id,
                    'report' => json_encode([]),
                ]);

                $nb_rows_all += $nb_rows_curr;
                $new_planning->addImportResult($nb_rows_curr, 0, 0, 0, 0);
            }
            //$new_planning->setStatus();
        }
    }
}
