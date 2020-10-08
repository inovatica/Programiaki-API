<?php

use Illuminate\Database\Seeder;
use App\Models\InstitutionsUsers;
use App\Models\Groups;
use App\Models\GroupsUsers;
use App\Models\User;

class GroupsUsersSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $volunteerKids = User::where('name', 'like', '%Kid%')->take(2)->get();
        $group = Groups::all()->first();
        
        foreach($volunteerKids as $braveKido){
            App\Models\InstitutionsUsers::create([
                        'institution_id' => $group->institution_id,
                        'user_id' => $braveKido->uuid,
            ]);
            GroupsUsers::create([
                        'group_id' => $group->id,
                        'child_id' => $braveKido->uuid,
            ]);
        }
    }

}
