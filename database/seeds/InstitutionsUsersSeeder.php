<?php

use Illuminate\Database\Seeder;
use App\Models\InstitutionsUsers;
use App\Models\Institutions;
use App\Models\User;

class InstitutionsUsersSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $institution = Institutions::all()->first();
        $babysitter = null;
        foreach (User::all() as $user) {
            if ($user->hasRole('babysitter')) {
                $babysitter = $user;
                break;
            }
        }
        
        if ($institution && $babysitter) {
            //seeding institution babysitter
            $institution_user = InstitutionsUsers::create([
                        'institution_id' => $institution->id,
                        'user_id' => $babysitter->uuid
            ]);
        }

    }

}
