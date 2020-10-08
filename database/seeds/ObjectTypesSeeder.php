<?php

use Illuminate\Database\Seeder;

class ObjectTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ObjectsTypes::create([
            'name' => 'Zwierzęta',
            'key' => 'animal'
        ]);

        \App\Models\ObjectsTypes::create([
            'name' => 'Matematyczne',
            'key' => 'math'
        ]);

        \App\Models\ObjectsTypes::create([
            'name' => 'Kierunkowe',
            'key' => 'way'
        ]);

        \App\Models\ObjectsTypes::create([
            'name' => 'Jedzenie',
            'key' => 'food'
        ]);
    }
}
