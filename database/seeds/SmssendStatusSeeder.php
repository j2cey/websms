<?php

use App\SmssendStatus;
use Illuminate\Database\Seeder;

class SmssendStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createNew("0", "aucun élément à traiter","élément nouvellement créée");
        $this->createNew("1", "attente traitement", "élément en attente de traitement");
        $this->createNew("2", "traitement en cours", "élément en cours de traitement");
        $this->createNew("3", "succès traitement", "élément traité avec succès");
        $this->createNew("4", "traitement effectué avec erreur(s)", "élément traité avec erreur(s)");
        $this->createNew("5", "échec traitement", "échec traitement élément");
    }

    private function createNew($code, $title, $description) {
        SmssendStatus::create([
            'code' => $code, 'titre' => $title, 'description' => $description
        ]);
    }
}
