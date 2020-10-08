<?php

namespace App\Http\Controllers\Rest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Groups;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;

class GroupsController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * @SWG\Get(
     *     tags={"Groups"},
     *     path="/groups",
     *     summary="Get groups either owned by a user or all if admin requests",
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
    public function getGroups(Request $request) {

        $user = Auth::user();

        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return [
                'status' => 'success',
                'data' => [
//                    'groups' => [\App\Models\Groups::all()->where('babysitter_id', $user->uuid)]
                    'groups' => [$user->groups]
                ]
            ];
        }

        return [
            'status' => 'success',
            'data' => [
                'groups' => [\App\Models\Groups::all()]
            ]
        ];
    }
    
    /**
     * @param Request $request
     * @return array
     *
     * @SWG\Post(
     *   path="/groups/create",
     *   tags={"Groups"},
     *   summary="Create a group if a user is a babysitter and has related institution",
     *   operationId="create-group",
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
     *                  @SWG\Property(property="name", type="string", example="Przykładowa grupa"),
     *                  @SWG\Property(property="institution_id", type="string", example="d2fee450-31c0-11e8-8b55-b7f85733ef28"),
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
    public function createGroup(Request $request) {

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

        $this->validate($request, [
            'name' => 'required|max:190',
        ]);
        $data = $request->all();
        $data['id'] = Uuid::generate()->string;
        $data['babysitter_id'] = $user->uuid;
        $group = new Groups();
        $group->fill($data);

        try {
            \DB::beginTransaction();

            $group->save();
            
            \DB::commit();

            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył grupę', ['group' => $group]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['group'] = $group;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_GROUP']], 401);
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * @SWG\Post(
     *   path="/groups/update",
     *   tags={"Groups"},
     *   summary="Update a group by its id but only if a user is a babysitter and own given group",
     *   operationId="update-group",
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
     *                  @SWG\Property(property="id", type="string", example="0fae6d20-30df-11e8-bbcb-6d0dfcfad3c4"),
     *                  @SWG\Property(property="name", type="string", example="Przykładowa grupa po edycji"),
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
    public function updateGroup(Request $request) {

        $user = Auth::user();
        if (!in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_GROUP', 'USER_NOT_AUTHORIZED_TO_UPDATE_GROUP']], 401);
        }
        $group = Groups::find($request->id);
        if(is_null($group)){
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DESTROY_GROUP', 'REQESTED_GROUP_DOES_NOT_EXIST']], 401);
        }
        if ($user->uuid !== $group->babysitter_id) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_GROUP', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
        }

        $this->validate($request, [
            'name' => 'required|max:190',
        ]);
        
        $group->fill($request->all());

        try {
            \DB::beginTransaction();
            $group->save();
            
            \DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował grupę', ['group' => $group]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['group'] = $group;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_GROUP']], 401);
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * @SWG\Delete(
     *   path="/groups/destroy",
     *   tags={"Groups"},
     *   summary="Destroys a group by its id but only if a user is a babysitter and own given group",
     *   operationId="destroy-group",
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
     *                  @SWG\Property(property="id", type="string", example="699ea160-37ff-11e8-9638-e17fded8bfba"),
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
    public function destroyGroup(Request $request) {
        
        $user = Auth::user();
        if (!in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DESTROY_GROUP', 'USER_NOT_AUTHORIZED_TO_DESTROY_GROUP']], 401);
        }
        $group = Groups::find($request->id);
        if(is_null($group)){
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DESTROY_GROUP', 'REQESTED_GROUP_DOES_NOT_EXIST']], 401);
        }
        if ($user->uuid !== $group->babysitter_id) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DESTROY_GROUP', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
        }
        try {
            \DB::beginTransaction();
            \App\Models\GroupsUsers::where('group_id', '=', $group->id)->delete();
            $group->delete();
            
            \DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. edytował grupę', ['group' => $group]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['group'] = $group;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DELETE_GROUP']], 401);
        }
    }
    
    /**
     * @SWG\Post(
     *     tags={"Groups"},
     *     path="/groups/childs",
     *     summary="Get childs from group",
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
     *              @SWG\Property(property="group_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
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
    public function getGroupChilds(Request $request) {

        $user = Auth::user();
        
        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            $group = $user->groups->where('id', $request->input('group_id'))->first();
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_GET_CHILDS', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
            }
        } else {
            $group = Groups::find($request->input('group_id'));
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_GET_CHILDS', 'GROUP_NOT_EXIST']], 401);
            }
        }
        
        return [
            'status' => 'success',
            'data' => [
                'childs' => $group->childs
            ]
        ];
    }
    
    /**
     * @SWG\Post(
     *     tags={"Groups"},
     *     path="/groups/childs/add",
     *     summary="add childs to user(babysitter) group",
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
     *              @SWG\Property(property="group_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
     *              @SWG\Property(property="childs", type="string", example="5ba9d680-37f7-11e8-975f-73ecca376845,5d037830-37f7-11e8-9b5a-3517d58076b7"),
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
    public function addGroupChilds(Request $request) {

        $user = Auth::user();
        
        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            $group = $user->groups->where('id', $request->input('group_id'))->first();
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_ADD_CHILDS', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
            }
        } else {
            $group = Groups::find($request->input('group_id'));
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_ADD_CHILDS', 'GROUP_NOT_EXIST']], 401);
            }
        }
        
        $this->validate($request, [
            'childs' => 'required',
        ]);
        
        $institution_childs = $group->institution->childs()->pluck('uuid')->all();
        $groups_childs = $group->childs->pluck('uuid')->all();
        $data = array(
            'group_id' => $group->id
        );
        
        try {
            \DB::beginTransaction();
            foreach (explode(',', $request->input('childs')) as $child) {
                if (in_array($child, $institution_childs) && !in_array($child, $groups_childs)) {
                    $data['child_id'] = $child;
                    $groups_users = new \App\Models\GroupsUsers();
                    $groups_users->fill($data);
                    $groups_users->save();
                }
            }
            \DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał dzieci do grupy', ['group' => $group]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['group'] = $group;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_ADD_CHILDS']], 401);
        }
    }
    
    /**
     * @SWG\Delete(
     *     tags={"Groups"},
     *     path="/groups/childs/remove",
     *     summary="remove childs from user(babysitter) group",
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
     *              @SWG\Property(property="group_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
     *              @SWG\Property(property="childs", type="string", example="5ba9d680-37f7-11e8-975f-73ecca376845,5d037830-37f7-11e8-9b5a-3517d58076b7"),
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
    public function removeGroupChilds(Request $request) {

        $user = Auth::user();
        
        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            $group = $user->groups->where('id', $request->input('group_id'))->first();
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_REMOVE_CHILDS', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
            }
        } else {
            $group = Groups::find($request->input('group_id'));
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_REMOVE_CHILDS', 'GROUP_NOT_EXIST']], 401);
            }
        }
        
        $this->validate($request, [
            'childs' => 'required',
        ]);
        
        $groups_childs = $group->childs->pluck('uuid')->all();
        
        try {
            \DB::beginTransaction();
            foreach (explode(',', $request->input('childs')) as $child) {
                if (in_array($child, $groups_childs)) {
                    $groups_users = \App\Models\GroupsUsers::where('group_id', '=', $group->id)->where('child_id', '=', $child)->first();
                    $groups_users->delete();
                }
            }
            \DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął dzieci z grupy', ['group' => $group]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['group'] = $group;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_REMOVE_CHILDS']], 401);
        }
    }
    
    /**
     * @SWG\Post(
     *     tags={"Groups"},
     *     path="/groups/childs/update",
     *     summary="update childs in user(babysitter) group",
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
     *              @SWG\Property(property="group_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
     *              @SWG\Property(property="childs", type="string", example="5ba9d680-37f7-11e8-975f-73ecca376845,5d037830-37f7-11e8-9b5a-3517d58076b7"),
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
    public function updateGroupChilds(Request $request) {

        $user = Auth::user();
        
        if (in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            $group = $user->groups->where('id', $request->input('group_id'))->first();
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_ADD_CHILDS', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
            }
        } else {
            $group = Groups::find($request->input('group_id'));
            if (!$group) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_ADD_CHILDS', 'GROUP_NOT_EXIST']], 401);
            }
        }
        
        $this->validate($request, [
            'childs' => 'required',
        ]);
        
        $institution_childs = $group->institution->childs()->pluck('uuid')->all();
        $groups_childs = $group->childs->pluck('uuid')->all();
        $data = array(
            'group_id' => $group->id
        );
        
        try {
            \DB::beginTransaction();
            foreach (explode(',', $request->input('childs')) as $child) {
                if (in_array($child, $institution_childs) && !in_array($child, $groups_childs)) {
                    $data['child_id'] = $child;
                    $groups_users = new \App\Models\GroupsUsers();
                    $groups_users->fill($data);
                    $groups_users->save();
                }
                if (in_array($child, $institution_childs) && in_array($child, $groups_childs)) {
                    unset($groups_childs[array_search($child, $groups_childs)]);
                }
            }
            foreach ($groups_childs as $child) {
                $groups_users = \App\Models\GroupsUsers::where('group_id', '=', $group->id)->where('child_id', '=', $child)->first();
                $groups_users->delete();
            }
            \DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał dzieci do grupy', ['group' => $group]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['group'] = $group;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_ADD_CHILDS']], 401);
        }
    }

}
