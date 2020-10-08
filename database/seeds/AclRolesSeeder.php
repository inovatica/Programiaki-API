<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Role;

class AclRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        Role::create([
            'name' => 'admin',
            'uuid' => Uuid::generate()->string,
        ]);

        Role::create([
            'name' => 'babysitter',
            'uuid' => Uuid::generate()->string,
        ]);

        Role::create([
            'name' => 'child',
            'uuid' => Uuid::generate()->string,
        ]);
    }
}
