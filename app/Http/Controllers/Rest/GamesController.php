<?php

namespace App\Http\Controllers\Rest;

use App\Models\Games;
use App\Models\GamesLevels;
use App\Models\GamesLevelsTagsObjects;
use Illuminate\Http\Request;

class GamesController extends RestController
{
    /**
     * @SWG\Get(
     *     tags={"Games"},
     *     path="/games",
     *     summary="Get available games",
     *     @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function getGames()
    {
        return [
            'status' => 'success',
            'data' => [
                'games' => Games::select(['id', 'name', 'key', 'pos'])
                    ->where('active', '=', 1)
                    ->orderBy('pos', 'asc')
                    ->get()
            ]
        ];
    }

    /**
     * @SWG\Get(
     *     tags={"Games"},
     *     path="/games/{gameId}/levels",
     *     summary="Get available levels in game",
     *     @SWG\Parameter(
     *       name="gameId",
     *       description="gameId",
     *       in="path",
     *       required=true,
     *       type="string",
     *       default="1cfdd350-2b74-11e8-84ab-a5a12d13da2a"
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     *
     * @param  Games $game
     * @return array
     */
    public function getLevels(Games $game)
    {
        return [
            'status' => 'success',
            'data' => [
                'levels' => GamesLevels::select(['id', 'name', 'key', 'pos'])
                    ->where('active', '=', 1)
                    ->where('game_id', '=', $game->id)
                    ->orderBy('pos', 'asc')
                    ->get()
            ]
        ];
    }

    /**
     * @SWG\Get(
     *     tags={"Games"},
     *     path="/games/{gameId}/levels/{levelId}/elements",
     *     summary="Get elements in game level",
     *     @SWG\Parameter(
     *       name="gameId",
     *       description="gameId",
     *       in="path",
     *       required=true,
     *       type="string",
     *       default="1cfdd350-2b74-11e8-84ab-a5a12d13da2a"
     *     ),
     *     @SWG\Parameter(
     *       name="levelId",
     *       description="levelId",
     *       in="path",
     *       required=true,
     *       type="string",
     *       default="1cfed220-2b74-11e8-8653-cff0bf191a51"
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     *
     * @param  Games $game
     * @param  GamesLevels $level
     * @return array
     */
    public function getTags(Games $game, GamesLevels $level)
    {
        $referencesToTagsObjects = GamesLevelsTagsObjects::select('tags_objects_id')
            ->where('games_levels_id', '=', $level->id)
            ->with('tagsObjects')
            ->get();

        $response = [];

        foreach ($referencesToTagsObjects as $ref) {
            if (is_null($ref->tagsObjects)) {
                continue;
            }
            if (!$ref->tagsObjects->tag->active) {
                continue;
            }

            $ref->tagsObjects->load(['tag', 'object']);
            $ref->tagsObjects->object->load(['type', 'group', 'image', 'audio', 'meal']);
            $tmp = [
                'tag_id' => $ref->tagsObjects->tag_id,
                'tag_key' => $ref->tagsObjects->tag->key,
                'id' => $ref->tagsObjects->object_id,
                'name' => $ref->tagsObjects->object->name,
                'key' => $ref->tagsObjects->object->key,
                'image' => null,
                'audio' => null,
                'type_name' => $ref->tagsObjects->object->type->name,
                'type_key' => $ref->tagsObjects->object->type->key,
                'group_name' => null,
                'group_key' => null,
            ];

            if (!is_null($ref->tagsObjects->object->image)) {
                $tmp['image'] = $ref->tagsObjects->object->image->getFile();
            }
            if (!is_null($ref->tagsObjects->object->audio)) {
                $tmp['audio'] = $ref->tagsObjects->object->audio->getFile();
            }
            if (!is_null($ref->tagsObjects->object->group)) {
                $tmp['group_name'] = $ref->tagsObjects->object->group->name;
            }
            if (!is_null($ref->tagsObjects->object->group)) {
                $tmp['group_key'] = $ref->tagsObjects->object->group->key;
            }

            $tmp['meal'] = [];
            foreach ($ref->tagsObjects->object->meal as $meal) {
                $arr = [];
                $arr['name'] = $meal->name;
                $arr['key'] = $meal->key;

                $arr['type'] = null;
                $arr['type_key'] = null;

                if (!is_null($meal->type)) {
                    $arr['type'] = $meal->type->name;
                    $arr['type_key'] = $meal->type->key;
                }

                $arr['group'] = null;
                $arr['group_key'] = null;

                if (!is_null($meal->group)) {
                    $arr['group'] = $meal->group->name;
                    $arr['group_key'] = $meal->group->key;
                }

                $arr['image'] = null;
                $arr['audio'] = null;

                if (!is_null($meal->image_id)) {
                    $arr['image'] = $meal->image->getFile();
                }

                if (!is_null($meal->audio_id)) {
                    $arr['audio'] = $meal->audio->getFile();
                }

                $tmp['meal'][] = $arr;
            }

            $response[] = $tmp;

        }

        return [
            'status' => 'success',
            'data' => [
                'elements' => $response
            ]
        ];
    }
}
