<?php

namespace App\Http\Controllers\Admin\Games;

use App\Models\Games;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GamesController extends Controller
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
        return view('admin.games.games.list', ['rows' => Games::paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.games.games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:190',
            'key' => 'required|max:190'
        ]);

        $data = $request->all();
        $lastGame = Games::orderBy('pos', 'desc')->first();
        $data['pos'] = ($lastGame) ? $lastGame->pos + 1 : 1;

        try {
            $game = Games::create($data);
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył grę', ['game' => $game]);
            return response()->redirectTo(route('games.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Games $game
     * @return \Illuminate\Http\Response
     */
    public function show(Games $game)
    {
        return view('admin.games.games.show', ['game' => $game]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  Games $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Games $game)
    {
        $game->load('levels');
        return view('admin.games.games.edit', ['game' => $game]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Games $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Games $game)
    {

        $this->validate($request, [
            'name' => 'required|max:190',
            'key' => 'required|max:190'
        ]);

        $data = $request->except('pos');
        $data['active'] = $request->get('active', 0);

        $game->fill($data);

        try {
            $game->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował grę', ['game' => $game]);
            return \Redirect::back();
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Games $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Games $game)
    {
        try {
            $game->delete();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął grę', ['game' => $game]);
            return response()->redirectTo(route('games.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
    
    public function levelOrder(Request $request)
    {
        $levels = \App\Models\GamesLevels::all();

        foreach ($levels as $level) {
            $level->timestamps = false; // To disable update_at field updation
            $id = $level->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $level->update(['pos' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }
}
