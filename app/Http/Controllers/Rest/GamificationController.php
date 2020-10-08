<?php

namespace App\Http\Controllers\Rest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Groups;
use App\Models\Gamification;
use App\Models\Games;
use App\Models\GamesLevels;
use App\Models\Certification;

class GamificationController extends RestController {

    /**
     * @SWG\Get(
     *     tags={"Gamifications"},
     *     path="/gamifications",
     *     summary="Get gamification records from groups either owned by a user or all if admin requests",
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
    public function getGamifications() {
        $user = Auth::user();
        return [
            'status' => 'success',
            'data' => [
                'gamifications' => [Gamification::all()]
            ]
        ];
        //FIXME: need to solve this supercalifragilisticexpialidocious relation to provide data for this particular request from any babysitter
        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return [
                'status' => 'success',
                'data' => [
                    'gamifications' => [Gamification::all()]
                ]
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * @SWG\Post(
     *   path="/gamifications/create",
     *   tags={"Gamifications"},
     *   summary="Create a gamification row when babysitter accepts kid's level completion in a given game",
     *   operationId="create-gamification",
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   security={
     *       {
     *           "Bearer": {}
     *       }
     *   },
     *   @SWG\Parameter(
     *      name="request",
     *      in="body",
     *      required=true,
     *          @SWG\Schema(
     *                  @SWG\Property(property="children_uuids", type="string", example="fa3572b0-3352-11e8-b3b5-b5ec61fbfe12,fadea5f0-3352-11e8-b1ad-ef7f0911cadd"),
     *                  @SWG\Property(property="game_id", type="string", example="011fcec0-3353-11e8-ab26-539c25ac904f"),
     *                  @SWG\Property(property="game_level_id", type="string", example="01213eb0-3353-11e8-80d8-6f04a0b5a6af"),
     *                  @SWG\Property(property="data", type="string", example="{additionalData:Some additional data, evenMoreAdditionalData:Some even more additional data}"),
     *     ),
     *   ),
     *     @SWG\Parameter(
     *     name="Authorization",
     *     description="TokenUser ",
     *     in="header",
     *     required=false,
     *     type="string",
     *     default="Bearer "
     *   ),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function createGamification(Request $request) {

        $user = Auth::user();
        if (!in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_GROUP', 'USER_NOT_AUTHORIZED_TO_CREATE_GROUP']], 401);
        }
        if (is_null($user->institutions)) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_GROUP', 'USER_WITHOUT_INSTITUTION']], 401);
        }
//        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $user->institutionsUsers['institution_id']) == 0) {
//            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_GROUP', 'WRONG_USER_INSTITUTION_UUID_FORMAT']], 401);
//        }

        $data = $request->all();
        $game = Games::find($data['game_id']);
        $kids = array_unique(explode(',', $data['children_uuids']));

        if (empty($kids)) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_GAMIFICATION_ROW', 'NO_CHILDREN_UUID_GIVEN']], 401);
        }

        unset($data['children_uuids']);

        $gamificationsToInsert = array();
        $gamificationsToUpdate = array();
        $gamificationsToUpdateUuids = array();

        foreach ($kids as $kid) {
            $gamificationCheck = Gamification::findByChildGameLevel($kid, $game->id, $data['game_level_id']);
            if ($gamificationCheck === null) {
                $kidUser = User::where('uuid', $kid)->first();
                $gamification = new Gamification();
                $data['uuid'] = Uuid::generate()->string;
                $data['child_id'] = $kid;
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
                $gamification->fill($data);
                array_push($gamificationsToInsert, $gamification->toArray());
            } else {
                array_push($gamificationsToUpdateUuids, $gamificationCheck->uuid);
                array_push($gamificationsToUpdate, $gamificationCheck->toArray());
            }
        }
        try {
            \DB::beginTransaction();
            Gamification::insert($gamificationsToInsert);
            Gamification::whereIn('uuid', $gamificationsToUpdateUuids)->update(['updated_at' => Carbon::now()]);

            \DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył wpisy grywalizacji', ['gamifications' => $gamificationsToInsert]);
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował wpisy grywalizacji', ['gamifications' => $gamificationsToUpdate]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['gamificationsToInsert'] = $gamificationsToInsert;
            $success['data']['gamificationsToUpdate'] = $gamificationsToUpdate;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_GAMIFICATION_ROW']]);
        }
    }

}
