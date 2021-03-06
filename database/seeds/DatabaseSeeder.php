<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SmsimportStatusSeeder::class);
        $this->call(SmssendStatusSeeder::class);
        $this->call(SmscampaignTypeSeeder::class);
        $this->call(SmstreatmentResultSeeder::class);
    }
}
