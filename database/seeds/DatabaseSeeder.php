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
        $this->call(RolesTableSeeder::class);
        $this->call(BroadcastsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SermonsTableSeeder::class);
    }
}
