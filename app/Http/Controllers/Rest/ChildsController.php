<?php

namespace App\Http\Controllers\Rest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\GroupsUsers;
use App\Models\InstitutionsUsers;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;

class ChildsController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return array
     *
     * @SWG\Post(
     *   path="/childs/create",
     *   tags={"childs"},
     *   summary="Create a child if a user is a babysitter and has related institution",
     *   operationId="create-child",
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
     *                  @SWG\Property(property="name", type="string", example="Przykładowy przedszkolak"),
     *                  @SWG\Property(property="groups", type="string", example="0fae6d20-30df-11e8-bbcb-6d0dfcfad3c4,0fae6d20-30df-11e8-bbcb-6d0dfcfad3c4"),
     *                  @SWG\Property(property="institution_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
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
    public function createChild(Request $request) {

        $user = Auth::user();
        if (!in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_CHILD', 'USER_NOT_AUTHORIZED_TO_CREATE_CHILD']], 401);
        }
        if (empty($user->institutions->where('id', $request->input('institution_id'))->all())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_CHILD', 'USER_WITHOUT_INSTITUTION']], 401);
        }
        $child_groups = explode(',', $request->input('groups'));
        if (empty($child_groups)) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_CHILD', 'MISSING_ARGUMENT_GROUPS']], 401);
        }
        foreach ($child_groups as $child_group) {
            if (!in_array($child_group, $user->groups->pluck('id')->all())) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_CHILD', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
            }
        }

        $this->validate($request, [
            'name' => 'required|max:190',
            'institution_id' => 'required|max:36',
        ]);
        $data_user = $request->all();
        $data_user['uuid'] = Uuid::generate()->string;
        $data_user['password'] = bcrypt('1234');
        $data_user['active'] = 1;
        $data_user['email'] = 'child_' . time() . '_' . rand(10000, 99999) . '@programiaki.com';
        $child = new User();
        $child->fill($data_user);

        try {
            DB::beginTransaction();
            $child->save();
            $child->assignRole(User::KID_ROLE);
            $this->updateChildGroups($request, $child, $user);
            $data_institution_user = array(
                'institution_id' => $request->input('institution_id'),
                'user_id' => $child->uuid
            );
            $institutions_childs = new InstitutionsUsers();
            $institutions_childs->fill($data_institution_user);
            $institutions_childs->save();
            DB::commit();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył dziecko', ['child' => $child]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['child'] = $child;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_CREATE_CHILD', $e->getMessage()]], 401);
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * @SWG\Post(
     *   path="/childs/update",
     *   tags={"childs"},
     *   summary="Update a child by its id but only if a user is a babysitter and is in the same institution as the child",
     *   operationId="update-child",
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
     *                  @SWG\Property(property="name", type="string", example="Przykładowy przedszkolak po edycji"),
     *                  @SWG\Property(property="groups", type="string", example="0fae6d20-30df-11e8-bbcb-6d0dfcfad3c4,0fae6d20-30df-11e8-bbcb-6d0dfcfad3c4"),
     *                  @SWG\Property(property="institution_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
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
    public function updateChild(Request $request) {

        $user = Auth::user();
        if (!in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_CHILD', 'USER_NOT_AUTHORIZED_TO_UPDATE_CHILD']], 401);
        }
        if (empty($user->institutions->where('id', $request->input('institution_id'))->all())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_CHILD', 'USER_WITHOUT_INSTITUTION']], 401);
        }
        $child_groups = explode(',', $request->input('groups'));
        if (empty($child_groups)) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_CHILD', 'MISSING_ARGUMENT_GROUPS']], 401);
        }
        foreach ($child_groups as $child_group) {
            if (!in_array($child_group, $user->groups->pluck('id')->all())) {
                return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_CHILD', 'USER_DOES_NOT_OWN_REQUESTED_GROUP']], 401);
            }
        }
        $child = User::where('uuid', '=', $request->id)->first();
        if(is_null($child)){
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_CHILD', 'REQESTED_CHILD_DOES_NOT_EXIST']], 401);
        }

        $this->validate($request, [
            'name' => 'required|max:190',
            'institution_id' => 'required|max:36'
        ]);
        
        $data_user = $request->all();
        
        $child->fill($data_user);

        try {
            DB::beginTransaction();
            $child->save();
            $this->updateChildGroups($request, $child, $user);
            DB::commit();

            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował dziecko', ['child' => $child]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['child'] = $child;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_UPDATE_CHILD', $e->getMessage()]], 401);
        }
    }

    /**
     * @param Request $request
     * @return array
     *
     * @SWG\Delete(
     *   path="/childs/destroy",
     *   tags={"childs"},
     *   summary="Destroys a child by its id but only if a user is a babysitter and is in the same institution as the child",
     *   operationId="destroy-child",
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
     *                  @SWG\Property(property="institution_id", type="string", example="0c2e3660-31c7-11e8-9832-615834c725c6"),
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
    public function destroyChild(Request $request) {
        
        $user = Auth::user();
        if (!in_array(User::BABYSITTER_ROLE, $user->getRoleNames()->toArray())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DESTROY_CHILD', 'USER_NOT_AUTHORIZED_TO_DESTROY_CHILD']], 401);
        }
        if (empty($user->institutions->where('id', $request->input('institution_id'))->all())) {
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DESTROY_CHILD', 'USER_WITHOUT_INSTITUTION']], 401);
        }
        $child = User::where('uuid', '=', $request->id)->first();
        if(is_null($child)){
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DESTROY_CHILD', 'REQESTED_CHILD_DOES_NOT_EXIST']], 401);
        }
        
        try {
            DB::beginTransaction();
            if (InstitutionsUsers::where('user_id', '=', $child->uuid)->count() === 1)
                $child->delete();
            $this->dropChildGroups($request, $child);
            $institutions_childs = InstitutionsUsers::where('user_id', '=', $child->uuid)->where('institution_id', '=', $request->input('institution_id'))->first();
            $institutions_childs->delete();
            DB::commit();
            
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął dziecko', ['child' => $child]);
            $success = [];
            $success['status'] = 'success';
            $success['data']['child'] = $child;
            $success['dateTime'] = Carbon::now();
            return response()->json($success);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'errors' => ['CANNOT_DELETE_CHILD', $e->getMessage()]], 401);
        }
    }
    
    private function updateChildGroups(Request $request, User $child, User $user) {
        $user_groups = $user->groups->where('institution_id', '=', $request->input('institution_id'))->pluck('id')->all();
        $child_groups_new = explode(',', $request->input('groups'));
        $child_groups_old = GroupsUsers::where('child_id', '=', $child->uuid)->whereIn('group_id', $user_groups)->get();

        foreach ($child_groups_old as $child_group_old) {
            if (in_array($child_group_old->group_id, $child_groups_new)) {
                //przypisanie do grupy zostaje
                unset($child_groups_new[array_search($child_group_old->group_id, $child_groups_new)]);
            } else {
                //usuwamy przypisanie do grupy
                $child_group_old->delete();
            }
        }
        
        $data_group_user = array(
            'group_id' => NULL,
            'child_id' => $child->uuid
        );
        foreach ($child_groups_new as $child_group_new) {
            //dodajemy nowe przypisanie do grupy
            $data_group_user['group_id'] = $child_group_new;
            $groups_childs = new GroupsUsers();
            $groups_childs->fill($data_group_user);
            $groups_childs->save();
        }
        
    }
    
    private function dropChildGroups(Request $request, User $child) {
        $child_groups = $child->child_groups->where('institution_id', '=', $request->input('institution_id'))->pluck('id')->all();

        foreach (GroupsUsers::where('child_id', '=', $child->uuid)->whereIn('group_id', $child_groups)->get() as $groups_child) {
            $groups_child->delete();
        }
        
    }

}
