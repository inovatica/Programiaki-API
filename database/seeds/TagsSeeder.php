<?php

use Illuminate\Database\Seeder;
use App\Models\Tables;
use App\Models\Tags;

class TagsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $table = null;
        foreach (Tables::all() as $tab) {
            $table = $tab;
            break;
        }
        
        $tag = Tags::create([
                    'id' => Uuid::generate()->string,
                    'key' => 'tag 1',
                    'active' => false
        ]);
        
        if ($table) {
            //seeding tag
            $tag = Tags::create([
                        'id' => Uuid::generate()->string,
                        'table_id' => $table->id,
                        'key' => 'tag 2'
            ]);
        }

    }

}
