<?php

namespace App\Console\Commands;

use App\SmscampaignFile;
use Illuminate\Console\Command;
use App\Traits\SmscampaignTrait;

class SmscampaignImportfiles extends Command
{
    use SmscampaignTrait;
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
        $file_to_import = SmscampaignFile::whereNull('imported_at')->first();

        \Log::info("Cron en cours de traitement...");

        if ($file_to_import) {
            $this->importFile($file_to_import);
            $this->info('Commande smscampaign:importfiles execute avec succes! 1 fichier traité.');
        } else {
            $this->info('Aucun fichier a traiter.');
        }
        \Log::info("Traitement termine.");
        return 0;
    }
}
