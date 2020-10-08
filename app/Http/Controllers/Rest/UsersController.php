<?php

namespace App\Http\Controllers\Rest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @SWG\Get(
     *     tags={"users"},
     *     path="/users",
     *     summary="Get users",
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
    public function getUsers()
    {
        return [
            'status' => 'success',
            'data' => [
                'users' => [\App\Models\User::all()]
            ]
        ];
    }
}
