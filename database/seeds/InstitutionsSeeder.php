<?php

use Illuminate\Database\Seeder;
use App\Models\Institutions;

class InstitutionsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        //seeding institution
        $institution = Institutions::create([
                    'name' => 'Przedszkole nr 1',
                    'city' => 'Łódź',
                    'zip_code' => '99-999',
                    'street' => 'Łódzka',
                    'street_number' => '12',
                    'phone' => '699699699'
        ]);

    }

}
