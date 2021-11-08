<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $superAdminUser = User::firstOrCreate([
            'id' => 1,
        ],
        [
            'name' => 'Super Admin',
            'email' => 'superadmin@invoke.com',
            'role' => 1,
            'password' => bcrypt('pass123')
        ]);
    }
}
