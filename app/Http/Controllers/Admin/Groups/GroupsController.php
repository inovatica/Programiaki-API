<?php

namespace App\Http\Controllers\Admin\Groups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Groups;
use App\Models\Institutions;
use App\Models\InstitutionsUsers;
use App\Models\GroupsUsers;
use App\Models\User;

class GroupsController extends Controller
{
    function __construct()
    {
        $this->middleware("acl:admin");
    }

    public function index()
    {
        return view('admin.groups.list', ['rows' => Groups::paginate(15)]);
    }

    public function add(Request $request)
    {
        return view('admin.groups.add', ['institutions' => Institutions::all()]);
    }
    
    public function create(Request $request)
    {
        $validation = [
            'name' => 'required|max:190',
            'institution_id' => 'required'
        ];

        $this->validate($request, $validation);
        
        try {
            $group = Groups::create($request->all());
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył grupę', ['group' => $group]);
            return response()->redirectTo(route('groups.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    public function edit(Request $request, Groups $group)
    {
        $babysitters = Institutions::find($group->institution_id)->babysitters();
        return view('admin.groups.edit', ['group' => $group,'institutions' => Institutions::all(), 'babysitters' => $babysitters]);
    }

    public function update(Request $request, Groups $group)
    {
        $validation = [
            'name' => 'required|max:190',
            'institution_id' => 'required'
        ];

        $this->validate($request, $validation);
        
        $group->fill($request->all());
        try {
            $group->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował grupę', ['group' => $group]);
            return response()->redirectTo(route('groups.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Groups $group
     * @return \Illuminate\Http\Response
     */
    public function show(Groups $group)
    {
        return view('admin.groups.show', ['group' => $group]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  Groups $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Groups $group)
    {
        try {
            DB::beginTransaction();
            GroupsUsers::where('group_id', '=', $group->id)->delete();
            $group->delete();
            DB::commit();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął grupę', ['group' => $group]);
            return response()->redirectTo(route('groups.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
    
    public function childs(Request $request, Groups $group)
    {
        return view('admin.groups.childs', ['group' => $group]);
    }
    
    public function childsAdd(Request $request, Groups $group)
    {
        $childs_already = array();
        foreach ($group->childs as $child) {
            $childs_already[] = $child->uuid;
        }
        $childs = array();
        foreach ($group->institution->childs() as $child) {
            if (!in_array($child->uuid, $childs_already))
                $childs[] = $child;
        }
        return view('admin.groups.childsadd', ['group' => $group, 'childs' => $childs]);
    }
    
    public function childsCreate(Request $request, Groups $group)
    {
        try {
            $group_user = GroupsUsers::create($request->all());
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał dziecko do grupy', ['group' => $group]);
            return response()->redirectTo(route('groups.childs', $group->id));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    public function childsShow(Groups $group, User $child)
    {
        return view('admin.groups.childsshow', ['group' => $group, 'child' => $child]);
    }
    
    public function childsDestroy(Groups $group, User $child)
    {
        try {
            $group_user = GroupsUsers::where('group_id', '=', $group->id)->where('child_id', '=', $child->uuid)->first();
            $group_user->delete();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął dziecko z grupy', ['group' => $group]);
            return response()->redirectTo(route('groups.childs', $group->id));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
}
