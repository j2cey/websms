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
        $this->createNew("0", "message commun");
        $this->createNew("1", "messages individuels");
        $this->createNew("2", "mixte");
    }

    private function createNew($code, $title) {
        SmscampaignType::create([
            'code' => $code, 'titre' => $title
        ]);
    }
}
