<?php

use Illuminate\Database\Seeder;
use App\Models\InstitutionsUsers;
use App\Models\Groups;

class GroupsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $institution_user = InstitutionsUsers::all()->first();
        
        if ($institution_user) {
            //seeding group
            $group = Groups::create([
                        'name' => 'Pszczółki',
                        'institution_id' => $institution_user->institution_id,
                        'babysitter_id' => $institution_user->user_id
            ]);
            $group = Groups::create([
                        'name' => 'Tygryski',
                        'institution_id' => $institution_user->institution_id,
                        'babysitter_id' => $institution_user->user_id
            ]);
        }

    }

}
