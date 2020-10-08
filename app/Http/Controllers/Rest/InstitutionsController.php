<?php

namespace App\Http\Controllers\Rest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Institutions;
use Carbon\Carbon;

class InstitutionsController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * @SWG\Get(
     *     tags={"Institutions"},
     *     path="/institutions",
     *     summary="Get user institutions or all if admin requests",
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
    public function getInstitutions(Request $request) {

        $user = Auth::user();

        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return [
                'status' => 'success',
                'data' => [
                    'institutions' => $user->institutions
                ]
            ];
        }

        return [
            'status' => 'success',
            'data' => [
                'institutions' => [\App\Models\Institutions::all()]
            ]
        ];
    }
    
    /**
     * @SWG\Post(
     *     tags={"Institutions"},
     *     path="/institutions/childs",
     *     summary="Get childs from institution",
     *     @SWG\Response(
     *         response="default",
     *         description="successful operation"
     *     ),
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *              @SWG\Property(property="institution_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
     *         ),
     *     ),
     *     @SWG\Parameter(
     *      name="Authorization",
     *      description="TokenUser ",
     *      in="header",
     *       required=false,
     *       type="string",
     *        default="Bearer "
     *    ),
     *    @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function getInstitutionChilds(Request $request) {

        $user = Auth::user();
        
        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            $institution = $user->institutions->where('id', $request->input('institution_id'))->first();
            if (!$institution) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_GET_CHILDS', 'USER_WITHOUT_INSTITUTION']], 401);
            }
        } else {
            $institution = Institutions::find($request->input('institution_id'));
            if (!$institution) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_GET_CHILDS', 'INSTITUTION_NOT_EXIST']], 401);
            }
        }

        return [
            'status' => 'success',
            'data' => [
                'childs' => $institution->childs()
            ]
        ];
    }

}
