<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Games;
use App\Models\GamesLevels;

class GamificationsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $game = Games::where('active', 1)->first();

        //seeding gamification row
        foreach (GamesLevels::where('active', 1)->get() as $k => $v) {
            $gamification = \App\Models\Gamification::create([
                        'uuid' => Uuid::generate()->string,
                        'child_id' => User::where('active', 1)->where('name', 'like', '%Kid%')->first()->uuid,
                        'game_id' => $v['game_id'],
                        'game_level_id' => $v['id'],
                        'data' => '{["additionalData":"Some additional data","evenMoreAdditionalData":"Some even more additional data"]} ',
            ]);
        }
    }

}
