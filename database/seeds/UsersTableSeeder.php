<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Mary Letterman',
            'email' => 'mletterman@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        DB::table('users')->insert([
            'name' => 'Brad Hamilton',
            'email' => 'bhamilton@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $role = App\Role::find(2);
        $userIds = [10000,10001];

        foreach ($userIds as $id) {
            $user = App\User::find($id);
            $user->roles()->attach($role);
        }
    }
}
