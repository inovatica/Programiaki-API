<?php

namespace App\Http\Controllers\Admin\Tables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tables;
use App\Models\Institutions;

class TablesController extends Controller
{
    function __construct()
    {
        $this->middleware("acl:admin");
    }

    public function index()
    {
        return view('admin.tables.list', ['rows' => Tables::paginate(15)]);
    }

    public function add(Request $request)
    {
        return view('admin.tables.add', ['institutions' => Institutions::all()]);
    }
    
    public function create(Request $request)
    {
        $validation = [
            'key' => 'required|max:190',
        ];

        $this->validate($request, $validation);
        
        $data = $request->all();
        $data['active'] = $request->get('active', 0);
        try {
            $table = Tables::create($data);
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył stolik', ['table' => $table]);
            return response()->redirectTo(route('tables.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    public function edit(Request $request, Tables $table)
    {
        return view('admin.tables.edit', ['table' => $table,'institutions' => Institutions::all()]);
    }

    public function update(Request $request, Tables $table)
    {
        $validation = [
            'key' => 'required|max:190',
        ];
        
        $this->validate($request, $validation);

        $data = $request->all();
        $data['active'] = $request->get('active', 0);
        $table->fill($data);
        try {
            $table->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował stolik', ['table' => $table]);
            return response()->redirectTo(route('tables.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Tables $table
     * @return \Illuminate\Http\Response
     */
    public function show(Tables $table)
    {
        return view('admin.tables.show', ['table' => $table]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  Tables $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tables $table)
    {
        try {
            $table->delete();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął stolik', ['table' => $table]);
            return response()->redirectTo(route('tables.list'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
}
