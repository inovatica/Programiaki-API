<?php

namespace App\Http\Controllers\Admin\Games;

use App\Models\Games;
use App\Models\GamesLevels;
use App\Models\GamesLevelsTagsObjects;
use App\Models\Tags;
use App\Models\TagsObjects;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LevelsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("acl:admin");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.games.levels.list', ['rows' => GamesLevels::with('game')->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.games.levels.create', [
            'games' => Games::all(),
            'tags' => Tags::with('objects')->get(),
        ]);
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
            'key' => 'required|max:190',
            'game_id' => 'exists:games,id'
        ]);

        $lastGame = GamesLevels::where('game_id', '=', $request->get('game_id'))
            ->orderBy('pos', 'desc')
            ->first();

        $data = $request->except('objects');
        $data['pos'] = ($lastGame) ? $lastGame->pos + 1 : 1;

        try {
            $level = GamesLevels::create($data);
            $this->handleTagsAssigements($request, $level);

            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył poziom', ['level' => $level]);
            return response()->redirectTo(route('levels.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  GamesLevels $level
     * @return \Illuminate\Http\Response
     */
    public function show(GamesLevels $level)
    {
        $level->load('game');
        return view('admin.games.levels.show', ['level' => $level, 'games' => Games::all()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  GamesLevels $level
     * @return \Illuminate\Http\Response
     */
    public function edit(GamesLevels $level)
    {
        $level->load('assignedTags');
        $tagsObjectsReferences = [];
        foreach ($level->assignedTags as $row) {
            $tagsObjectsReferences[] = $row->tags_objects_id;
        }

        $tagsObjectsResolver = TagsObjects::whereIn('id', $tagsObjectsReferences)->get();
        $selectedTagsObjects = [];
        foreach ($tagsObjectsResolver as $row) {
            if (!key_exists($row->tag_id, $selectedTagsObjects)) {
                $selectedTagsObjects[$row->tag_id] = [];
            }
            $selectedTagsObjects[$row->tag_id][$row->object_id] = $row->object_id;
        }

        return view('admin.games.levels.edit', [
            'level' => $level,
            'games' => Games::all(),
            'tags' => Tags::with('objects')->get(),
            'selectedTagsObjects' => $selectedTagsObjects
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  GamesLevels $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GamesLevels $level)
    {
        $this->validate($request, [
            'name' => 'required|max:190',
            'key' => 'required|max:190',
            'game_id' => 'exists:games,id'
        ]);

        $data = $request->except('pos', 'objects');
        $data['active'] = $request->get('active', 0);

        $level->fill($data);

        try {
            $level->save();
            $this->handleTagsAssigements($request, $level);
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował poziom', ['level' => $level]);
            return \Redirect::back();
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    private function handleTagsAssigements(Request $request, GamesLevels $level)
    {
        $tagObjects = [];
        foreach ($request->get('objects',[]) as $row) {
            list($tagId, $objectId) = explode('_', $row);
            if (!key_exists($tagId, $tagObjects)) {
                $tagObjects[$tagId] = [];
            }
            $tagObjects[$tagId][$objectId] = $row;
        }

        $tagsObjectsReferences = TagsObjects::whereIn('tag_id', array_keys($tagObjects))->get();
        $referencesChecker = [];
        foreach ($tagsObjectsReferences as $row) {
            if (!key_exists($row->tag_id, $referencesChecker)) {
                $referencesChecker[$row->tag_id] = [];
            }
            $referencesChecker[$row->tag_id][$row->object_id] = $row;
        }

        $current = [];
        $handled = [];

        foreach ($level->assignedTags as $row) {
            $current[$row->tags_objects_id] = $row;
        }

        foreach ($tagObjects as $tagId => $objects) {
            if (!key_exists($tagId, $referencesChecker)) {
                continue;
            }

            foreach ($objects as $objectId => $object) {
                if (!key_exists($objectId, $referencesChecker[$tagId])) {
                    continue;
                }
                if (key_exists($referencesChecker[$tagId][$objectId]->id, $current)) {
                    $handled[$referencesChecker[$tagId][$objectId]->id] = $object;
                    continue;
                }

                $el = GamesLevelsTagsObjects::create([
                    'tags_objects_id' => $referencesChecker[$tagId][$objectId]->id,
                    'games_levels_id' => $level->id
                ]);
                \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał powiązanie tagu z poziomem', ['el' => $el]);
                $handled[$referencesChecker[$tagId][$objectId]->id] = $object;
            }

        }

        foreach ($current as $refId => $row) {
            if (!key_exists($refId, $handled)) {
                $row->delete();
                \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął powiązanie tagu z poziomem', ['el' => $row]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  GamesLevels $level
     * @return \Illuminate\Http\Response
     */
    public
    function destroy(GamesLevels $level)
    {
        try {
            $level->delete();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął poziom', ['level' => $level]);
            return response()->redirectTo(route('levels.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }
}
