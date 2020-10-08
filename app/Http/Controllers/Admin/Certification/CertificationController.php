<?php

namespace App\Http\Controllers\Admin\Certification;

use App\Models\Certification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CertificationController extends Controller
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
        return view('admin.certification.certification.list', ['rows' => Certification::paginate(15)]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int $certificationId
     * @return \Illuminate\Http\Response
     */
    public function toggleActive($certificationId)
    {
        try {
            $certification = Certification::where('uuid','=',$certificationId)->first();
            
            if($certification->active == 1){
                $certification->where('uuid','=',$certificationId)->update(['active' => 0]);
            }else{
                $certification->where('uuid','=',$certificationId)->update(['active' => 1]);
            }
            \Session::flash('success', __('save_success').' '.__('certification'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował wpis certyfikacji');
            return response()->redirectTo(route('certification.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
    
    /**
     * Soft delete the specified certification record.
     *
     * @param  int $certificationId
     * @return \Illuminate\Http\Response
     */
    public function destroy($certificationId)
    {
        try {
            $certification = Certification::where('uuid','=',$certificationId);
            $certification->delete();
            \Session::flash('success', __('remove_success').' '.__('certification'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął wpis certyfikacji');
            return response()->redirectTo(route('certification.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
}
