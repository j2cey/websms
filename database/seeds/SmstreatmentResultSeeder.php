<?php

use App\SmstreatmentResult;
use Illuminate\Database\Seeder;

class SmstreatmentResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createNew("0", "Non Traité","élément non traité");
        $this->createNew("-1", "Echec", "échec de traitement");
        $this->createNew("1", "Succès", "succès de traitement");
    }

    private function createNew($code, $title, $description) {
        SmstreatmentResult::create([
            'code' => $code, 'titre' => $title, 'description' => $description
        ]);
    }
}
