<?php

use Illuminate\Database\Seeder;
use App\Models\Tables;
use App\Models\Institutions;

class TablesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $institution = Institutions::all()->first();
        
        $table = Tables::create([
                    'key' => 'Stolik 1 nieaktywny',
                    'active' => false
        ]);
        
        if ($institution) {
            //seeding table
            $table = Tables::create([
                        'institution_id' => $institution->id,
                        'key' => 'Stolik 2'
            ]);
        }

    }

}
