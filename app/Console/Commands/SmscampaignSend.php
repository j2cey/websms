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
        $max_to_send = 45;
        $planningresult_lines = SmscampaignPlanningResult::where('send_processed', 0)->whereNull('suspended_at')->take($max_to_send)->get();

        \Log::info("Cron en cours de traitement...");

        $nb_done = 0;
        $nb_done_all = 0;
        foreach ($planningresult_lines as $planningresult_line) {
            if ($nb_done >= 15) {
                // sleep for 20 seconds
                sleep(20);
                $nb_done = 0;
            }

            $planningresult_line->sendSms();
            $nb_done += 1;
            $nb_done_all += 1;
        }

        if ($nb_done > 0) {
            $this->info('smscampaign:send execute avec succes! '.$nb_done_all.' élément(s) traité(s).');
        } else {
            $this->info('smscampaign:send execute avec succes! Aucun élément traité.');
        }

        \Log::info("Traitement termine.");
        return 0;
    }
}
