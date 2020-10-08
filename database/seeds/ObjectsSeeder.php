<?php

use Illuminate\Database\Seeder;

class ObjectsSeeder extends Seeder
{
    private function friendly_string($string)
    {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', str_replace(array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ż', 'ź'), array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z'), $string)), '-'));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaults = json_decode('[
  {"id":"1","card_num":"4294965442","object":{"id":"1","type":"animal","title":"Słoń","image":"/files/img/elephant.svg","sound":"/files/sounds/slon.mp3","card_id":"1","group":"wild", "toEat": "Jabłko"}},
  {"id":"2","card_num":"4294945577","object":{"id":"2","type":"animal","title":"Lew","image":"/files/img/lion.svg","sound":"/files/sounds/lew.mp3","card_id":"2","group":"wild", "toEat": "Stek"}},
  {"id":"3","card_num":"4294964418","object":{"id":"3","type":"animal","title":"Małpa","image":"/files/img/monkey.svg","sound":"/files/sounds/malpa.mp3","card_id":"3","group":"wild", "toEat": "Banan"}},
  {"id":"4","card_num":"2953","object":{"id":"4","type":"animal","title":"Papuga","image":"/files/img/parrot.svg","sound":"/files/sounds/papuga.mp3","card_id":"4","group":"wild", "toEat": "Orzeszki"}},
  {"id":"5","card_num":"4294945321","object":{"id":"5","type":"animal","title":"Kura","image":"/files/img/chicken.svg","sound":"/files/sounds/kura.mp3","card_id":"5","group":"domesticated", "toEat": "Ziarno"}},
  {"id":"6","card_num":"4294965969","object":{"id":"6","type":"animal","title":"Świnia","image":"/files/img/pig.svg","sound":"/files/sounds/swinia.mp3","card_id":"6","group":"domesticated", "toEat": "Ziemniak"}},
  {"id":"7","card_num":"2185","object":{"id":"7","type":"animal","title":"Koń","image":"/files/img/horse.svg","sound":"/files/sounds/kon.mp3","card_id":"7","group":"domesticated", "toEat": "Siano"}},
  {"id":"8","card_num":"4294963134","object":{"id":"8","type":"animal","title":"Bocian","image":"/files/img/stork.svg","sound":"/files/sounds/bocian.mp3","card_id":"8","group":"wild", "toEat": "Żaba"}},
  {"id":"9","card_num":"4294946345","object":{"id":"9","type":"animal","title":"Owca","image":"/files/img/sheep.svg","sound":"/files/sounds/owca.mp3","card_id":"9","group":"domesticated", "toEat": "Trawa"}},
  {"id":"11","card_num":"28811","object":{"id":"11","type":"animal","title":"Niedźwiedź","image":"/files/img/bear.svg","sound":"/files/sounds/niedzwiedz.mp3","card_id":"11","group":"wild", "toEat": "Ryba"}},
  {"id":"12","card_num":"4294965186","object":{"id":"12","type":"animal","title":"Orzeł","image":"/files/img/eagle.svg","sound":"/files/sounds/orzel.mp3","card_id":"12","group":"wild", "toEat": "Mysz"}},
  {"id":"13","card_num":"20643","object":{"id":"13","type":"animal","title":"Wróbel","image":"/files/img/sparrow.svg","sound":"/files/sounds/wrobel.wav","card_id":"13","group":"wild", "toEat": "Robak"}},
  {"id":"14","card_num":"23683","object":{"id":"14","type":"animal","title":"Osioł","image":"/files/img/donkey.svg","sound":"/files/sounds/osiol.mp3","card_id":"14","group":"domesticated", "toEat": "Siano"}},
  {"id":"15","card_num":"4294948211","object":{"id":"15","type":"animal","title":"Świerszcz","image":"/files/img/cricket.svg","sound":"/files/sounds/swierszcz.mp3","card_id":"15","group":"wild", "toEat": "Roślina"}},
  {"id":"16","card_num":"4294948467","object":{"id":"16","type":"animal","title":"Żaba","image":"/files/img/frog.svg","sound":"/files/sounds/zaba.WAV","card_id":"16","group":"wild", "toEat": "Mucha"}},
  {"id":"17","card_num":"4294948723","object":{"id":"17","type":"animal","title":"Mysz","image":"/files/img/mouse.svg","sound":"/files/sounds/mysz.mp3","card_id":"17","group":"wild", "toEat": "Ser"}},
  {"id":"18","card_num":"17027","object":{"id":"18","type":"animal","title":"Indyk","image":"/files/img/turkey.svg","sound":"/files/sounds/indyk.mp3","card_id":"18","group":"domesticated", "toEat": "Ziarno"}},
  {"id":"19","card_num":"4294937977","object":{"id":"19","type":"animal","title":"Kot","image":"/files/img/cat.svg","sound":"/files/sounds/kot.mp3","card_id":"19","group":"domesticated", "toEat": "Mleko"}},
  {"id":"20","card_num":"4294948979","object":{"id":"20","type":"animal","title":"Pies","image":"/files/img/dog.svg","sound":"/files/sounds/pies.mp3","card_id":"20","group":"domesticated", "toEat": "Kość"}},
  {"id":"21","card_num":"649","object":{"id":"21","type":"animal","title":"Ryba","image":"/files/img/fish.svg","sound":"/files/sounds/ryba.mp3","card_id":"21","group":"wild", "toEat": ""}},
  {"id":"22","card_num":"21187","object":{"id":"22","type":"animal","title":"Mucha","image":"/files/img/fly.svg","sound":"/files/sounds/mucha.mp3","card_id":"22","group":"wild", "toEat": ""}},
  {"id":"23","card_num":"21667","object":{"id":"23","type":"animal","title":"Gęś","image":"/files/img/goose.svg","sound":"/files/sounds/ges.wav","card_id":"23","group":"domesticated", "toEat": "Ziarno"}},
  {"id":"24","card_num":"4294937785","object":{"id":"24","type":"animal","title":"Sowa","image":"/files/img/owl.svg","sound":"/files/sounds/sowa.mp3","card_id":"24","group":"wild", "toEat": "Mysz"}},
  {"id":"25","card_num":"4294938041","object":{"id":"25","type":"animal","title":"Banan","image":"/files/img/banana.svg","sound":"","card_id":"25","group":"", "toEat": ""}},
  {"id":"26","card_num":"16771","object":{"id":"26","type":"food","title":"Kość","image":"/files/img/bone.svg","sound":"","card_id":"26","group":"", "toEat": ""}},
  {"id":"27","card_num":"18051","object":{"id":"27","type":"food","title":"Marchew","image":"/files/img/carrot.svg","sound":"","card_id":"27","group":"", "toEat": ""}},
  {"id":"28","card_num":"6537","object":{"id":"28","type":"food","title":"Ser","image":"/files/img/cheese.svg","sound":"","card_id":"28","group":"", "toEat": ""}},
  {"id":"29","card_num":"6281","object":{"id":"29","type":"food","title":"Trawa","image":"/files/img/grass.svg","sound":"","card_id":"29","group":"", "toEat": ""}},
  {"id":"30","card_num":"25513","object":{"id":"30","type":"food","title":"Siano","image":"/files/img/hay.svg","sound":"","card_id":"30","group":"", "toEat": ""}},
  {"id":"31","card_num":"17795","object":{"id":"31","type":"food","title":"Jabłko","image":"/files/img/apple.svg","sound":"","card_id":"31","group":"", "toEat": ""}},
  {"id":"32","card_num":"21699","object":{"id":"32","type":"food","title":"Stek","image":"/files/img/meat.svg","sound":"","card_id":"32","group":"", "toEat": ""}},
  {"id":"33","card_num":"21411","object":{"id":"33","type":"food","title":"Orzeszki","image":"/files/img/peanut.svg","sound":"","card_id":"33","group":"", "toEat": ""}},
  {"id":"34","card_num":"21155","object":{"id":"34","type":"math","title":"*2","image":"/files/img/mult2.svg","sound":"","card_id":"34","group":"", "toEat": ""}},
  {"id":"35","card_num":"21443","object":{"id":"35","type":"math","title":"*3","image":"/files/img/mult3.svg","sound":"","card_id":"35","group":"", "toEat": ""}},
  {"id":"36","card_num":"393","object":{"id":"36","type":"math","title":"*4","image":"/files/img/mult4.svg","sound":"","card_id":"36","group":"", "toEat": ""}},
  {"id":"37","card_num":"4294941811","object":{"id":"37","type":"math","title":"*5","image":"/files/img/mult5.svg","sound":"","card_id":"37","group":"", "toEat": ""}},
  {"id":"38","card_num":"6025","object":{"id":"38","type":"food","title":"Chleb","image":"/files/img/bread.svg","sound":"","card_id":"38","group":"", "toEat": ""}},
  {"id":"39","card_num":"3465","object":{"id":"39","type":"food","title":"Roślina","image":"/files/img/leaves.svg","sound":"","card_id":"39","group":"", "toEat": ""}},
  {"id":"40","card_num":"23465","object":{"id":"40","type":"food","title":"Ziarno","image":"/files/img/ziarno.svg","sound":"","card_id":"40","group":"", "toEat": ""}},
  {"id":"41","card_num":"3209","object":{"id":"41","type":"food","title":"Ziemniak","image":"/files/img/potato.svg","sound":"","card_id":"41","group":"", "toEat": ""}},
  {"id":"42","card_num":"137","object":{"id":"42","type":"food","title":"Robak","image":"/files/img/worm.svg","sound":"","card_id":"42","group":"", "toEat": ""}},
  {"id":"43","card_num":"4294947187","object":{"id":"43","type":"food","title":"Mleko","image":"/files/img/milk.svg","sound":"","card_id":"43","group":"", "toEat": ""}},
  {"id":"44","card_num":"18563","object":{"id":"44","type":"math","title":"Prosto","image":"/files/img/?.svg","sound":"","card_id":"44","group":"", "toEat": ""}},
  {"id":"45","card_num":"5769","object":{"id":"45","type":"math","title":"Prosto","image":"/files/img/?.svg","sound":"","card_id":"45","group":"", "toEat": ""}},
  {"id":"46","card_num":"4294947955","object":{"id":"46","type":"math","title":"Prosto","image":"/files/img/?.svg","sound":"","card_id":"46","group":"", "toEat": ""}},
  {"id":"47","card_num":"1417","object":{"id":"47","type":"math","title":"Prosto","image":"/files/img/?.svg","sound":"","card_id":"47","group":"", "toEat": ""}},
  {"id":"48","card_num":"20387","object":{"id":"48","type":"math","title":"Prosto","image":"/files/img/?.svg","sound":"","card_id":"48","group":"", "toEat": ""}},
  {"id":"50","card_num":"30441","object":{"id":"50","type":"math","title":"Lewo","image":"/files/img/?.svg","sound":"","card_id":"50","group":"", "toEat": ""}},
  {"id":"51","card_num":"17283","object":{"id":"51","type":"math","title":"Lewo","image":"/files/img/?.svg","sound":"","card_id":"51","group":"", "toEat": ""}},
  {"id":"52","card_num":"4294947699","object":{"id":"52","type":"math","title":"Lewo","image":"/files/img/?.svg","sound":"","card_id":"52","group":"", "toEat": ""}},
  {"id":"53","card_num":"4294963083","object":{"id":"53","type":"math","title":"Lewo","image":"/files/img/?.svg","sound":"","card_id":"53","group":"", "toEat": ""}},
  {"id":"54","card_num":"17539","object":{"id":"54","type":"math","title":"Prawo","image":"/files/img/?.svg","sound":"","card_id":"54","group":"", "toEat": ""}},
  {"id":"55","card_num":"20899","object":{"id":"55","type":"math","title":"Prawo","image":"/files/img/?.svg","sound":"","card_id":"55","group":"", "toEat": ""}},
  {"id":"57","card_num":"21977","object":{"id":"57","type":"math","title":"Prawo","image":"/files/img/?.svg","sound":"","card_id":"57","group":"", "toEat": ""}},
  {"id":"58","card_num":"25769","object":{"id":"58","type":"math","title":"Prawo","image":"/files/img/?.svg","sound":"","card_id":"58","group":"", "toEat": ""}},
  {"id":"59","card_num":"4294941555","object":{"id":"59","type":"math","title":"Weź","image":"/files/img/?.svg","sound":"","card_id":"59","group":"", "toEat": ""}},
  {"id":"60","card_num":"4294947443","object":{"id":"60","type":"math","title":"Weź","image":"/files/img/?.svg","sound":"","card_id":"60","group":"", "toEat": ""}},
  {"id":"61","card_num":"4294935973","object":{"id":"61","type":"math","title":"Weź","image":"/files/img/?.svg","sound":"","card_id":"61","group":"", "toEat": ""}},
  {"id":"62","card_num":"17625","object":{"id":"62","type":"math","title":"Daj","image":"/files/img/?.svg","sound":"","card_id":"62","group":"", "toEat": ""}},
  {"id":"63","card_num":"22665","object":{"id":"63","type":"math","title":"Daj","image":"/files/img/?.svg","sound":"","card_id":"63","group":"", "toEat": ""}}]');

        $typesFetch = \App\Models\ObjectsTypes::all();
        $types = [];
        foreach ($typesFetch as $type) {
            $types[$type->key] = $type;
        }

        $groupsFetch = \App\Models\ObjectsGroups::all();
        $groups = [];
        foreach ($groupsFetch as $group) {
            $groups[$group->key] = $group;
        }

        $toEat = [];
        $objects = [];

        DB::statement('DELETE FROM objects_food_chain WHERE 1');
        DB::statement('DELETE FROM objects WHERE 1');
        $currentObjects = [];

        foreach ($defaults as $value) {
            $el = $value->object;

            if (!key_exists($el->type, $types)) {
                $type = new \App\Models\ObjectsTypes();
                $type->key = $el->type;
                $type->name = $el->type;
                $type->save();

                $types[$type->key] = $type;
            }

            $key = $this->friendly_string($el->title);

            if (key_exists($key, $currentObjects)) {
                $obj = $currentObjects[$key];
            } else {
                $obj = new \App\Models\Objects();
            }

            $obj->name = $el->title;
            $obj->key = $key;
            $obj->type_id = $types[$el->type]->id;

            if (!empty($el->image)) {
                $image = __DIR__ . $el->image;
                if (file_exists($image)) {
                    $tmpArr = explode('/', $image);
                    $file = new \Illuminate\Http\UploadedFile($image, end($tmpArr));

                    $fileService = new \App\Services\File\FileService(new \App\Services\File\Client\ImageFile(), new \App\Services\File\Client\LocalDriver());
                    if ($fileService->upload($obj, $file)) {
                        $obj->image_id = $fileService->getId();
                    }
                }
            }

            if (!empty($el->sound)) {
                $sound = __DIR__ . $el->sound;
                if (file_exists($sound)) {
                    $tmpArr = explode('/', $sound);
                    $file = new \Illuminate\Http\UploadedFile($sound, end($tmpArr));

                    $fileService = new \App\Services\File\FileService(new \App\Services\File\Client\BaseFile(), new \App\Services\File\Client\LocalDriver());
                    if ($fileService->upload($obj, $file)) {
                        $obj->audio_id = $fileService->getId();
                    }
                }
            }

            if (!empty($el->group)) {
                if (key_exists($el->group, $groups)) {
                    $obj->group_id = $groups[$el->group]->id;
                }
            }

            $obj->save();
            $currentObjects[$key] = $obj;

            if(!empty($el->toEat)){
                $toEat[$obj->id] = $this->friendly_string($el->toEat);
            }

            $objects[$obj->key] = $obj;
        }

        foreach ($toEat as $consumer => $meal) {
            $foodChain = new \App\Models\ObjectsFoodChain();
            $foodChain->consumer_id = $consumer;
            $foodChain->meal_id = $objects[$meal]->id;
            $foodChain->save();
        }
    }
}
