<?php

use Illuminate\Database\Seeder;
use App\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedService = new \App\Services\SeedService();
        $seedService->createSuperAdmin();
    }
}
