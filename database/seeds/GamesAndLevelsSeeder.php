<?php

use Illuminate\Database\Seeder;

class GamesAndLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Puzzle',
                'key' => 'puzzle',
                'levels' => [
                    [
                        'name' => 'Weź i upuść',
                        'key' => 'dragAndDrop',
                    ],
                    [
                        'name' => 'Weź i upuść 2',
                        'key' => 'dragAndDrop2',
                    ],
                    [
                        'name' => 'Weź i upuść 3',
                        'key' => 'dragAndDrop3',
                    ]
                ]
            ]
        ];

        $lastPos = 1;
        foreach (json_decode(json_encode($data)) as $row) {
            $game = \App\Models\Games::create([
                'key' => $row->key,
                'name' => $row->name,
                'active' => 1,
                'pos' => $lastPos
            ]);

            $lastPos++;

            $lvlPos = 1;
            foreach ($row->levels as $el) {
                \App\Models\GamesLevels::create([
                    'game_id' => $game->id,
                    'name' => $el->name,
                    'key' => $el->key,
                    'active' => 1,
                    'pos' => $lvlPos
                ]);
                $lvlPos++;
            }
        }
    }
}
