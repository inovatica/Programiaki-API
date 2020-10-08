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
use App\Models\Certification;
use App\Models\Games;
use App\Models\GamesLevels;
use App\Models\Institutions;

class CertificationController extends RestController {

    /**
     * @SWG\Get(
     *     tags={"Certifications"},
     *     path="/certifications",
     *     summary="Get certification records from groups either owned by a user or all if admin requests",
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
    public function getCertifications() {
        $user = Auth::user();
        return [
            'status' => 'success',
            'data' => [
                'certifications' => [Certification::all()]
            ]
        ];
        //FIXME: need to solve this supercalifragilisticexpialidocious relation to provide data for this particular request od any babysitter
        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return [
                'status' => 'success',
                'data' => [
                    'certifications' => [Gamification::all()]
                ]
            ];
        }
    }

    /**
     * @SWG\Get(
     *     tags={"Certifications"},
     *     path="/certifications/{institutionId}/checkAndGrant",
     *     summary="Request a check on gamification rows, and if any kid reaches the requested level a certification is granted",
     *  @SWG\Parameter(
     *       name="institutionId",
     *       description="institutionId",
     *       in="path",
     *       required=true,
     *       type="string",
     *       default="69959a60-37ff-11e8-adc2-576b1f99477e"
     *     ),
     *  @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     }
     * )
     *
     * @param  string $institutionId
     * @return array
     */
    public function checkAndGrantCertification($institutionId) {

        $user = Auth::user();

        $groupsToCheck = Groups::where('institution_id', '=', $institutionId)->get();

        if (!in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CHECK_AND_GRANT_CERTIFICATION', 'USER_NOT_AUTHORIZED_TO_CHECK_AND_GRANT_CERTIFICATIONS']], 401);
        }
        if (is_null($user->institutions)) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CHECK_AND_GRANT_CERTIFICATION', 'USER_WITHOUT_INSTITUTION']], 401);
        }
        if (!in_array($user->uuid, $groupsToCheck->pluck('babysitter_id')->unique()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CHECK_AND_GRANT_CERTIFICATION', 'USER_IS_NOT_AN_OWNER_OF_ANY_GROUP_IN_GIVEN_INSTITUTION']], 401);
        }
        $certificationsToInsert = array();
        $certificationsToUpdate = array();
        $certificationsToUpdateUuids = array();
        foreach ($groupsToCheck as $groupToCheck) {
            if ($groupToCheck->getKids()->count() < 1) {
                break;
            }
            foreach ($groupToCheck->getKids()->pluck('uuid') as $kidToCheck) {
                $kidGamificationCount = Gamification::findByChild($kidToCheck)->count();
                if ($kidGamificationCount >= Gamification::countAllActiveLevels()) {
                    $certificationToCheck = Certification::where('child_id', '=', $kidToCheck)->where('active', '=', 1)->first();
                    if ($certificationToCheck === null) {
                        $certification = new Certification();
                        $certification->uuid = Uuid::generate()->string;
                        $certification->child_id = $kidToCheck;
                        $certification->issued_at = Carbon::now();
                        $certification->created_at = Carbon::now();
                        $certification->updated_at = Carbon::now();
                        array_push($certificationsToInsert, $certification->toArray());
                    } else {
                        array_push($certificationsToUpdateUuids, $certificationToCheck->uuid);
                        array_push($certificationsToUpdate, $certificationToCheck->toArray());
                    }
                }
            }
        }
        try {
            \DB::beginTransaction();
            if (count($certificationsToInsert) > 0) {
                Certification::insert($certificationsToInsert);
            } else {
                $certificationsToInsert = 'NO_NEW_CERTIFICATIONS_TO_GRANT';
                Certification::whereIn('uuid', $certificationsToUpdateUuids)->update(['updated_at' => Carbon::now(), 'issued_at' => Carbon::now()]);
            }

            \DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył wpisy certifikacji', ['certifications' => $certificationsToInsert]);
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował wpisy certifikacji', ['certifications' => $certificationsToUpdate]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['certificationsToInsert'] = $certificationsToInsert;
            $success['data']['certificationsToUpdate'] = $certificationsToUpdate;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_CERTIFICATION_ROW']]);
        }
    }

}
