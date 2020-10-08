<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AclRolesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(ObjectTypesSeeder::class);
        $this->call(ObjectsGroupsSeeder::class);
        $this->call(GamesAndLevelsSeeder::class);
        $this->call(InstitutionsSeeder::class);
        $this->call(InstitutionsUsersSeeder::class);
        $this->call(GroupsSeeder::class);
        $this->call(TablesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(GamificationsSeeder::class);
        $this->call(GroupsUsersSeeder::class);
    }
}
