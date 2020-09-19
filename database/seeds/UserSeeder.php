<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(['name' => "rootdev",'email' => "rootdev@gestockdsi.com",'password' => bcrypt('Gestock@Pw0rd')]);
        $user = User::create(['name' => "admin",'email' => "admin@gt.com",'password' => bcrypt('admin123')]);
        $user = User::create(['name' => "DV 01",'email' => "dv1@gt.com",'password' => bcrypt('dvlibertis123')]);
        $user = User::create(['name' => "DV 02",'email' => "dv2@gt.com",'password' => bcrypt('dvlibertis123')]);
        $user = User::create(['name' => "DV 03",'email' => "dv3@gt.com",'password' => bcrypt('dvlibertis123')]);
    }
}
