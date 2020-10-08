<?php

use Illuminate\Database\Seeder;

class ObjectsGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ObjectsGroups::create([
            'name' => 'Dzikie',
            'key' => 'wild'
        ]);

        \App\Models\ObjectsGroups::create([
            'name' => 'Udomowione',
            'key' => 'domesticated'
        ]);
    }
}
