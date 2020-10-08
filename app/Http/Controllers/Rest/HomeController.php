<?php

namespace App\Http\Controllers\Rest;

use App\Models\Games;
use App\Models\Objects;
use Illuminate\Support\Facades\Auth;

class HomeController extends RestController
{
    /**
     * @SWG\Get(
     *     path="/pong",
     *     tags={"Defaults"},
     *     summary="Pong message",
     *     operationId="PongMsg",
     *     @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * ):wq
     */
    public function index()
    {
        return [
            'status' => 'success',
            'messages' => ['pong']
        ];
    }

    /**
     * @SWG\Get(
     *     tags={"Games"},
     *     path="/objects",
     *     summary="Get objects",
     *     @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function getObjects()
    {
        $objects = [];
        $objectsFetch = Objects::with(['type', 'group', 'image', 'audio', 'meal'])->get();

        foreach ($objectsFetch as $row) {
            $tmp = [];
            $tmp['id'] = $row->id;
            $tmp['name'] = $row->name;
            $tmp['key'] = $row->key;
            $tmp['type'] = null;
            $tmp['type_key'] = null;

            if (!is_null($row->type)) {
                $tmp['type'] = $row->type->name;
                $tmp['type_key'] = $row->type->key;
            }

            $tmp['group'] = null;
            $tmp['group_key'] = null;

            if (!is_null($row->group)) {
                $tmp['group'] = $row->group->name;
                $tmp['group_key'] = $row->group->key;
            }

            $tmp['image'] = null;
            $tmp['audio'] = null;

            if (!is_null($row->image_id)) {
                $tmp['image'] = $row->image->getFile();
            }

            if (!is_null($row->audio_id)) {
                $tmp['audio'] = $row->audio->getFile();
            }

            $tmp['meal'] = [];
            foreach ($row->meal as $meal) {
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

            $objects[] = $tmp;
        }

        return [
            'status' => 'success',
            'data' => [
                'objects' => $objects
            ]
        ];
    }

    /**
     * @SWG\Get(
     *     path="/who-am-i",
     *     tags={"Defaults"},
     *     summary="Who ma I endpoint",
     *     operationId="WhoAmI",
     *     @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     }
     * )
     */
    public function whoAmI()
    {
        $user = Auth::user();
	$image = $user->image->getFile();
        $response = [
            'status' => 'success',
            'data' => [
                'user' => $user->toArray()
            ]
        ];
	
	$response['data']['user']['image'] = $image;
	return $response;
    }

}
