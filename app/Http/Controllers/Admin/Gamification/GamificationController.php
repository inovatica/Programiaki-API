<?php

namespace App\Http\Controllers\Admin\Gamification;

use App\Models\Gamification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class GamificationController extends Controller
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
        $result = array();
        $gamifiedKidsUuids = Gamification::distinct()->get(['child_id'])->pluck('child_id');
        
        foreach ($gamifiedKidsUuids as $kid){
            $user = User::where('uuid','=',$kid)->where('active','=',1)->first();
            $kidsGamificationRows = Gamification::findByChild($kid);
            $result[$kid]['gamifications'] = $kidsGamificationRows;
            $result[$kid]['name'] = $user->name;
            $result[$kid]['groups'] = $user->child_groups()->get();
            $result[$kid]['levels'] = $kidsGamificationRows->count();
        }
        
//        $result = Gamification::all()->groupBy('child_id');
        
        return view('admin.gamification.gamification.list', ['result' => $result]);
    }
    
    /**
     * Show the gamification records for given user.
     *
     * @param  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = User::where('uuid','=',$user)->where('active','=',1)->first();
        $result = Gamification::where('child_id','=',$user->uuid)->where('active','=',1);
        return view('admin.gamification.gamification.show', ['result' => $result->paginate(15), 'user' => $user]);

    }
    
    /**
     * Soft delete the specified gamification record.
     *
     * @param  int $gamificationId
     * @return \Illuminate\Http\Response
     */
    public function destroy($gamificationId)
    {
        try {
            $gamification = Gamification::where('uuid','=',$gamificationId);
            $gamification->delete();
            \Session::flash('success', __('remove_success').' '.__('gamification'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął wpis grywalizacji');
            return response()->redirectTo(route('gamification.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
}
