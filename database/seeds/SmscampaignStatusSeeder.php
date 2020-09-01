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
        $this->createNew("2", "succès importation fichier(s)");
        $this->createNew("3", "fichier(s) importé(s) avec erreur(s)");
        $this->createNew("4", "échec importation fichier(s)");
        $this->createNew("5", "attente traitement");
        $this->createNew("6", "succès traitement");
        $this->createNew("7", "traitement effectué avec erreur(s)");
        $this->createNew("8", "échec traitement");
    }

    private function createNew($code, $title) {
        SmscampaignStatus::create([
            'code' => $code, 'titre' => $title
        ]);
    }
}
