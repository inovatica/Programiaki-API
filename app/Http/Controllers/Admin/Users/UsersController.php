<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\User;
use App\Models\Avatars;
use App\Models\Institutions;
use App\Models\InstitutionsUsers;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;
use App\Services\File\Client\BaseFile;
use App\Services\File\Client\ImageFile;
use App\Services\File\Client\LocalDriver;
use App\Services\File\FileService;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("acl:admin|moderator");
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.users.list', ['rows' => User::paginate(15)]);
    }
    
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.users.create',[
            'roles' => Role::all(),
            'avatars' => Avatars::all(),
            'institutions' => Institutions::orderBy('name','asc')->get()
        ]);
    }
    
    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:190',
            'email' => 'required|email|unique:users'
        ]);
        $data = $request->all();
        $data['uuid'] = Uuid::generate()->string;
        $data['password'] = bcrypt('inovatica');
        
        $user = new User();

        $imageId = $this->uploadImage($request, $user);
        
        if ($imageId === false) {
            $msg = [];
            if (!$imageId) {
                $msg[] = __('upload_image_error');
            }
            return \Redirect::back()->withErrors($msg)->withInput($request->all());
        }
        
        $data['image_id'] = $imageId;
        
        $user->fill($data);
        
        try {
            $user->save();
            $user->assignRole($request['role']);
            $this->handleInstitutions($request, $user);

            \Session::flash('success', __('create_success').' '.__('user').'a '.$user->name);
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył uzytkownika', ['user' => $user]);
            return response()->redirectTo(route('users.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    /**
     * Show the form for editing the specified user.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user->load('institutions');

        $selectedInstitutions = [];

        foreach ($user->institutions as $institution) {
            $selectedInstitutions[$institution->id] = $institution->id;
        }
        
        return view('admin.users.users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'avatars' => Avatars::all(),
            'institutions' => Institutions::orderBy('name','asc')->get(),
            'selectedInstitutions' => $selectedInstitutions,
        ]);
    }
    
    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $this->validate($request, [
            'name' => 'required|max:190',
            'email' => 'required|email'
        ]);
        $data = $request->all();
        $data['active'] = $request->get('active', 0);
        $user->fill($data);
        
        $imageId = $this->uploadImage($request, $user);

        if ($imageId === false) {
            $msg = [];
            if (!$imageId) {
                $msg[] = __('upload_image_error');
            }

            return \Redirect::back()->withErrors($msg)->withInput($request->all());
        }
        
        if ($imageId) {
            $data['image_id'] = $imageId;
        }
        
        $user->fill($data);
        
        try {
            foreach($user->getRoleNames() as $role_key=>$role_value){
                $user->removeRole($role_value);
            }
            $user->assignRole($request['role']);
            $user->save();
            $this->handleInstitutions($request, $user);
            \Session::flash('success', __('save_success').' '.__('data').' '.__('user').'a '.$user->name);
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował użytkownika', ['user' => $user]);
            return \Redirect::back();
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    /**
     * Soft delete the specified user from storage.
     *
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $user = User::findOrFail($userId);
            if ($user->hasRole(User::BABYSITTER_ROLE)) {
                foreach ($user->groups as $group) {
                    \App\Models\GroupsUsers::where('group_id', '=', $group->id)->delete();
                    $group->delete();
                }
                InstitutionsUsers::where('user_id', '=', $user->uuid)->delete();
            } else if ($user->hasRole(User::KID_ROLE)) {
                \App\Models\GroupsUsers::where('child_id', '=', $user->uuid)->delete();
                InstitutionsUsers::where('user_id', '=', $user->uuid)->delete();
            }
            $user->delete();
            \Illuminate\Support\Facades\DB::commit();
            \Session::flash('success', __('remove_success').' '.__('user').'a '.$user->name);
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął użytkownika', ['user' => $user]);
            return response()->redirectTo(route('users.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
    
    private function uploadImage(Request $request, $object)
    {
        if (!$request->hasFile('image')) {
            return null;
        }
        $fileService = new FileService(new ImageFile(), new LocalDriver());
        if ($fileService->upload($object, $request->file('image'))) {
            return $fileService->getId();
        }

        return false;
    }
    
    /**
     * @param Request $request
     * @param Tags $user
     */
    private function handleInstitutions(Request $request, User $user)
    {
        $toSave = $request->get('institutions', []);
        $current = [];
        $handled = [];

        foreach ($user->institutions as $institution) {
            $current[$institution->id] = $institution;
        }

        foreach ($toSave as $item) {
            if (key_exists($item, $current)) {
                $handled[$item] = $item;
                continue;
            }
            $el = InstitutionsUsers::create([
                'user_id' => $user->uuid,
                'institution_id' => $item
            ]);
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał powiązanie instytucji z userem', ['el' => $el]);
            $handled[$item] = $item;
        }

        foreach ($current as $value) {
            if (!key_exists($value->id, $handled)) {
                $el = InstitutionsUsers::where('institution_id', '=', $value->id)->where('user_id', '=', $user->uuid)->first();
                $el->delete();
                \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął powiązanie instytucji z userem', ['el' => $el]);
            }
        }

    }
}
