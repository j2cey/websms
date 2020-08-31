<?php

namespace App\Console\Commands;

use App\Smscampaign;
use App\SmscampaignPlanning;
use App\SmscampaignPlanningResult;
use App\SmscampaignReceiver;
use Illuminate\Console\Command;

class SmscampaignSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smscampaign:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie les sms plannifiés';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $max_to_send = 30;
        $planningresult_lines = SmscampaignPlanningResult::where('stat_done', 0)->take($max_to_send)->get();

        \Log::info("Cron en cours de traitement...");

        $nb_done = 0;
        foreach ($planningresult_lines as $planningresult_line) {
            if ($nb_done >= 15) {
                // sleep for 20 seconds
                sleep(20);
                $nb_done = 0;
            }
            /*$receiver = SmscampaignReceiver::where('id',$planningresult_line->smscampaign_receiver_id)->first();
            $planning = SmscampaignPlanning::where('id',$planningresult_line->smscampaign_planning_id)->first();
            $campaign = Smscampaign::where('id',$planning->smscampaign_id)->first();
            $planningresult_line->sendSms($campaign->expediteur,$receiver->mobile,$planningresult_line->message);*/

            $this->sendSms();
            $nb_done = $nb_done + 1;
        }

        \Log::info("Traitement termine.");
        return 0;
    }
}
