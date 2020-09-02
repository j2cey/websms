<?php

use App\SmsimportStatus;
use Illuminate\Database\Seeder;

class SmsimportStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createNew("0", "aucun fichier à traiter", "fichier(s) nouvellement créé(s)");
        $this->createNew("1", "attente importation fichier(s)", "fichier(s) prêts pour importation");
        $this->createNew("2", "importation fichier(s) en cours", "fichier(s) en cours d'importation");
        $this->createNew("3", "succès importation fichier(s)","importation fichier(s) terminé(s) avec succès");
        $this->createNew("4", "fichier(s) importé(s) avec erreur(s)","importation fichier(s) terminé(s) avec erreur(s)");
        $this->createNew("5", "échec importation fichier(s)","importation fichier(s) totalement échouée");
    }

    private function createNew($code, $title, $description) {
        SmsimportStatus::create([
            'code' => $code, 'titre' => $title, 'description' => $description
        ]);
    }
}
