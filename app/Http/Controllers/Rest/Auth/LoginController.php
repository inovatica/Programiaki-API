<?php

/**
 * Date: 20.03.18
 * Time: 15:45
 */

namespace App\Http\Controllers\Rest\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class LoginController extends Controller {

    /**
     * login api
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *   path="/auth/login",
     *   tags={"auth"},
     *   summary="Login Action",
     *   operationId="Login",
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @SWG\Parameter(
     *      name="request",
     *      in="body",
     *      required=true,
     *          @SWG\Schema(
     *                  @SWG\Property(property="email", type="string", example="babysitter_1@inovatica.com"),
     *                  @SWG\Property(property="password", type="string", example="inovatica"),
     *     ),
     *   ),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function login(Request $request) {
        $authorizedRoles = ['admin', 'babysitter'];

        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $user = Auth::user();

            if ($user->hasAnyRole($authorizedRoles) === FALSE) {
                return response()->json(['status' => 'error', 'errors' => ['ACCOUNT_WITH_INSUFICIENT_PERMISSIONS']], 401);
            }

            if (!$user->active) {
                return response()->json(['status' => 'error', 'errors' => ['ACCOUNT_NOT_ACTIVE']], 401);
            }

            $success = [];
            $success['status'] = 'success';
            $success['data']['user'] = $user->toArray();
	    $success['data']['user']['image'] = $user->image->getFile();
            try {
                $user->createToken('MyApp')->accessToken;
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_GENERATE_TOKEN', $e->getMessage()]], 401);
            }
            $success['data']['token'] = $user->createToken('MyApp')->accessToken;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } else {
            return response()->json(['status' => 'error', 'errors' => ['WRONG_EMAIL_OR_PASSWORD']], 401);
        }
    }

    /**
     * logout api
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *   path="/auth/logout",
     *   tags={"auth"},
     *   summary="logout Action",
     *   operationId="logout",
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @SWG\Response(response=500, description="internal server error"),
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     }
     * )
     */
    public function logout(Request $request) {
        $request->user()->token()->revoke();

        // handling tokens "custom" way 
        \Laravel\Passport\Token::where('user_id', $request->user()->id)->delete();

        return [
            'status' => 'success',
            'message' => 'LOGOUT_SUCCESS',
            "dateTime" => Carbon::now()
        ];
    }

}
