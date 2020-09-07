<?php

use App\SmscampaignType;
use Illuminate\Database\Seeder;

class SmscampaignTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createNew("0", "Message Commun");
        $this->createNew("1", "Messages Individuels");
        $this->createNew("2", "Campagne Mixte");
    }

    private function createNew($code, $title) {
        SmscampaignType::create([
            'code' => $code, 'titre' => $title
        ]);
    }
}
