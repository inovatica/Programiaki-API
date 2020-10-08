<?php

use Illuminate\Database\Seeder;
use \App\Models\User;

class UsersSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        //seeding admin user
        $admin = User::create([
                    'name' => 'admin',
                    'uuid' => Uuid::generate()->string,
                    'email' => 'admin@inovatica.com',
                    'password' => bcrypt('inovatica'),
                    'active' => true
        ]);

        $admin->assignRole('admin');
        
        // seeding 10 child accounts
        for ($i = 1; $i <= 10; $i++) {
            $randString = time().'_'.rand(1,100000);
            $kid = User::create([
                        'name' => 'Kid_'.$randString,
                        'uuid' => Uuid::generate()->string,
                        'email' => 'kid_'.$randString.'@inovatica.com',
                        'password' => bcrypt('1234'),
                        'active' => true
            ]);
            $kid->assignRole('child');
            sleep(1);
        }
        
        // seeding 5 babysitter accounts
        for ($i = 1; $i <= 5; $i++) {
            $kid = User::create([
                        'name' => 'Babysitter_'.$i,
                        'uuid' => Uuid::generate()->string,
                        'email' => 'babysitter_'.$i.'@inovatica.com',
                        'password' => bcrypt('inovatica'),
                        'active' => true
            ]);
            $kid->assignRole('babysitter');
        }
    }

}
