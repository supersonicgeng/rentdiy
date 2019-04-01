<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(DefaultRouteSeeder::class);
        $this->call(RegionsTableSeeder::class);
    }
}
