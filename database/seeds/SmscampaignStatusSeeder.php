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
        $this->createNew("1", "attente traitement");
        $this->createNew("2", "traitement en cours");
        $this->createNew("3", "fin traitement");
    }

    private function createNew($code, $title) {
        SmscampaignStatus::create([
            'code' => $code, 'titre' => $title
        ]);
    }
}
