<?php

namespace App\Console\Commands;

use App\SmscampaignFile;
use Illuminate\Console\Command;

class SmscampaignImportfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smscampaign:importfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importe systématiquement tous les fichiers de campagne non importés';

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
        $file_to_import = SmscampaignFile::where('imported', 0)->whereNull('suspended_at')->first();

        \Log::info("smscampaign:importfiles en cours de traitement...");

        if ($file_to_import) {
            $file_to_import->importToPlannig();
            $this->info('smscampaign:importfiles execute avec succes! 1 fichier traité.');
        } else {
            $this->info('Aucun fichier a traiter.');
        }
        \Log::info("smscampaign:importfiles Traitement termine.");
        return 0;
    }
}
