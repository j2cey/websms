<?php

use Illuminate\Database\Seeder;
use App\SmscampaignStatus;

class SmscampaignStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createNew("0", "nouveau");
        $this->createNew("1", "attente importation fichier(s)");
        $this->createNew("2", "importation fichier(s) en cours");
        $this->createNew("3", "succès importation fichier(s)");
        $this->createNew("4", "fichier(s) importé(s) avec erreur(s)");
        $this->createNew("5", "échec importation fichier(s)");
        $this->createNew("6", "attente traitement");
        $this->createNew("7", "traitement en cours");
        $this->createNew("8", "succès traitement");
        $this->createNew("9", "traitement effectué avec erreur(s)");
        $this->createNew("10", "échec traitement");
    }

    private function createNew($code, $title) {
        SmscampaignStatus::create([
            'code' => $code, 'titre' => $title
        ]);
    }
}
