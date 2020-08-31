<?php


namespace App\Traits;

use App\SmscampaignFile;
use App\SmscampaignPlanning;
use App\SmscampaignPlanningResult;
use App\SmscampaignReceiver;
use Illuminate\Support\Carbon;

trait SmscampaignTrait
{
    public function importFile(SmscampaignFile $file) {
        $pendingfiles_dir = config('app.smscampaigns_filesfolder');
        $file_fullpath = $pendingfiles_dir.'/'.$file->name;
        $campaign = $file->campaign;

        $csvData = file_get_contents('/var/www/websms/public/'.$file_fullpath); // separateur_colonnes
        //$rows = array_map("str_getcsv", explode($campaign->separateur_colonnes, $csvData));
        $rows = array_map("str_getcsv", explode("\n", $csvData));

        // planning - attente traitement (1)
        $planning = SmscampaignPlanning::Create([
            'plan_at' => Carbon::now(),
            'smscampaign_id' => $campaign->id,
            'smscampaign_status_id' => 2,
        ]);

        foreach ($rows as $row) {
            $row_tab = explode($campaign->separateur_colonnes, $row[0]);
            // Receiver
            $receiver = SmscampaignReceiver::updateOrCreate([
                'mobile' => $row_tab[0],
            ]);

            // planning result
            $planning_result = new SmscampaignPlanningResult();
            $planning_result_valdone = false;
            if ($campaign->messages_individuels) {
                if (isset($row_tab[1])) {
                    $planning_result->message = $row_tab[1];
                    $planning_result_valdone = true;
                }
            } else {
                if (isset($row_tab[1])) {
                    $planning_result->message = $campaign->message;
                    $planning_result_valdone = true;
                }
            }
            if ($planning_result_valdone) {
                $planning_result->smscampaign_planning_id = $planning->id;
                $planning_result->smscampaign_receiver_id = $receiver->id;
                $planning_result->save();
            }
        }

        $campaign->smscampaign_status_id = 2;
        $campaign->save();

        // file
        $file->imported = 1;
        $file->imported_at = Carbon::now();
        $file->save();
    }
}
