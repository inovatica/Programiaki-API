<?php

namespace App\Http\Controllers\Admin\Institutions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Institutions;
use App\Models\InstitutionsUsers;
use App\Models\User;

class InstitutionsController extends Controller
{
    function __construct()
    {
        $this->middleware("acl:admin");
    }

    public function index()
    {
        return view('admin.institutions.list', ['rows' => Institutions::paginate(15)]);
    }

    public function add(Request $request)
    {
        return view('admin.institutions.add');
    }
    
    public function create(Request $request)
    {
        $validation = [
            'name' => 'required|max:190'
        ];

        $this->validate($request, $validation);
        
        try {
            $institution = Institutions::create($request->all());
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył instytycję', ['institution' => $institution]);
            return response()->redirectTo(route('institutions.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    public function edit(Request $request, Institutions $institution)
    {
        return view('admin.institutions.edit', ['institution' => $institution]);
    }

    public function update(Request $request, Institutions $institution)
    {
        $validation = [
            'name' => 'required|max:190'
        ];

        $this->validate($request, $validation);
        
        $institution->fill($request->all());
        try {
            $institution->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował instytucję', ['institution' => $institution]);
            return response()->redirectTo(route('institutions.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Institutions $institution
     * @return \Illuminate\Http\Response
     */
    public function show(Institutions $institution)
    {
        return view('admin.institutions.show', ['institution' => $institution]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  Institutions $institution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Institutions $institution)
    {
        try {
            DB::beginTransaction();
            foreach ($institution->groups as $group) {
                \App\Models\GroupsUsers::where('group_id', '=', $group->id)->delete();
                $group->delete();
            }
            foreach ($institution->childs() as $child) {
                if ($child->institutions->count() == 1)
                    $child->delete();
            }
            foreach ($institution->babysitters() as $babysitter) {
                if ($babysitter->institutions->count() == 1)
                    $babysitter->delete();
            }
            InstitutionsUsers::where('institution_id', '=', $institution->id)->delete();
            $institution->delete();
            DB::commit();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął instytycję ', ['institution' => $institution]);
            return response()->redirectTo(route('institutions.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
    
    public function babysitters(Request $request, Institutions $institution)
    {
        return view('admin.institutions.babysitters', ['institution' => $institution]);
    }
    
    public function babysittersAdd(Request $request, Institutions $institution)
    {
        $babysitters_already = array();
        foreach ($institution->babysitters() as $babysitter) {
            $babysitters_already[] = $babysitter->uuid;
        }
        $babysitters = array();
        foreach (User::all() as $user) {
            if ($user->hasRole(User::BABYSITTER_ROLE) && !in_array($user->uuid, $babysitters_already))
                $babysitters[] = $user;
        }
        return view('admin.institutions.babysittersadd', ['institution' => $institution, 'babysitters' => $babysitters]);
    }
    
    public function babysittersCreate(Request $request, Institutions $institution)
    {
        try {
            $institution_user = InstitutionsUsers::create($request->all());
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał opiekuna do instytycji', ['institution' => $institution]);
            return response()->redirectTo(route('institutions.babysitters', $institution->id));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    public function babysittersShow(Institutions $institution, User $babysitter)
    {
        return view('admin.institutions.babysittersshow', ['institution' => $institution, 'babysitter' => $babysitter]);
    }
    
    public function babysittersDestroy(Institutions $institution, User $babysitter)
    {
        try {
            DB::beginTransaction();
            foreach ($babysitter->groups as $group) {
                if ($group->institution_id == $institution->id) {
                    \App\Models\GroupsUsers::where('group_id', '=', $group->id)->delete();
                    $group->delete();
                }
            }
            $institution_user = InstitutionsUsers::where('institution_id', '=', $institution->id)->where('user_id', '=', $babysitter->uuid)->first();
            $institution_user->delete();
            if ($babysitter->institutions->count() === 0)
                $babysitter->delete();
            DB::commit();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął opiekuna z instytucji ' . $institution->id);
            return response()->redirectTo(route('institutions.babysitters', $institution->id));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
    
    public function getBabysitters(Request $request) {
        $babysitters = Institutions::find($request->input('institution'))->babysitters();
        return response()->json($babysitters);
    }
    
    public function childs(Request $request, Institutions $institution)
    {
        return view('admin.institutions.childs', ['institution' => $institution]);
    }
    
    public function childsAdd(Request $request, Institutions $institution)
    {
        $childs_already = array();
        foreach ($institution->childs() as $child) {
            $childs_already[] = $child->uuid;
        }
        $childs = array();
        foreach (User::all() as $user) {
            if ($user->hasRole(User::KID_ROLE) && !in_array($user->uuid, $childs_already))
                $childs[] = $user;
        }
        return view('admin.institutions.childsadd', ['institution' => $institution, 'childs' => $childs]);
    }
    
    public function childsCreate(Request $request, Institutions $institution)
    {
        try {
            $institution_user = InstitutionsUsers::create($request->all());
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał opiekuna do instytucji', ['institution' => $institution]);
            return response()->redirectTo(route('institutions.childs', $institution->id));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    public function childsShow(Institutions $institution, User $child)
    {
        return view('admin.institutions.childsshow', ['institution' => $institution, 'child' => $child]);
    }
    
    public function childsDestroy(Institutions $institution, User $child)
    {
        try {
            DB::beginTransaction();
            $child_groups = array();
            foreach ($child->child_groups as $group) {
                if ($group->institution_id == $institution->id) {
                    $child_groups[] = $group->id;
                }
            }
            if (!empty($child_groups))
                \App\Models\GroupsUsers::where('child_id', '=', $child->uuid)->whereIn('group_id', $child_groups)->delete();
            $institution_user = InstitutionsUsers::where('institution_id', '=', $institution->id)->where('user_id', '=', $child->uuid)->first();
            $institution_user->delete();
            if ($child->institutions->count() === 0)
                $child->delete();
            DB::commit();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął opiekuna z instytucji ' . $institution->id);
            return response()->redirectTo(route('institutions.childs', $institution->id));
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
}
