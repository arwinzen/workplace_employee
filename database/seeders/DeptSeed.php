<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DeptSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        $dept = ['Finance', 'System', 'Digital Marketing', 'HR', 'Creative', 'Data', 'Cleaning', 'Engineering', 'Security', 'Audit'];

        foreach(range(0, 9) as $index){
            DB::table('departments')->insert([
                'department_name' => $dept[$index],
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);
        }
    }
}
