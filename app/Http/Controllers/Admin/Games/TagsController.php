<?php

namespace App\Http\Controllers\Admin\Games;

use App\Models\Objects;
use App\Models\Tables;
use App\Models\Tags;
use App\Models\TagsObjects;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagsController extends Controller
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
        return view('admin.games.tags.list', ['rows' => Tags::with('objects')->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.games.tags.create', [
            'objects' => Objects::orderBy('name','asc')->get(),
            'tables' => Tables::all()
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
            'key' => 'required|max:190',
        ]);

        $data = $request->except('pos');
        $data['active'] = $request->get('active', 0);

        try {
            $tag = Tags::create($data);
            $this->handleObjects($request, $tag);
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył tag', ['tag' => $tag]);
            return response()->redirectTo(route('tags.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Tags $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tags $tag)
    {
        return view('admin.games.tags.show', ['tag' => $tag]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tags $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tags $tag)
    {
        $tag->load('objects');

        $selectedObjects = [];

        foreach ($tag->objects as $object) {
            $selectedObjects[$object->id] = $object->id;
        }

        return view('admin.games.tags.edit', [
            'objects' => Objects::orderBy('name','asc')->get(),
            'tag' => $tag,
            'selectedObjects' => $selectedObjects,
            'tables' => Tables::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tags $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tags $tag)
    {
        $this->validate($request, [
            'key' => 'required|max:190',
        ]);

        $data = $request->except('pos');
        $data['active'] = $request->get('active', 0);
        $tag->fill($data);

        try {
            $tag->save();
            $this->handleObjects($request, $tag);
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. zaktualizował tag', ['tag' => $tag]);
            return \Redirect::back();
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tags $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tags $tag)
    {
        try {
            $tag->delete();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął tag', ['tag' => $tag]);
            return response()->redirectTo(route('tags.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param Tags $tag
     */
    private function handleObjects(Request $request, Tags $tag)
    {
        $toSave = $request->get('objects', []);
        $current = [];
        $handled = [];

        foreach ($tag->objects as $object) {
            $current[$object->id] = $object;
        }

        foreach ($toSave as $item) {
            if (key_exists($item, $current)) {
                $handled[$item] = $item;
                continue;
            }
            $el = TagsObjects::create([
                'tag_id' => $tag->id,
                'object_id' => $item
            ]);
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał powiązanie tagu z obiektem', ['el' => $el]);
            $handled[$item] = $item;
        }

        foreach ($current as $value) {
            if (!key_exists($value->id, $handled)) {
                $el = TagsObjects::where('object_id', '=', $value->id)->where('tag_id', '=', $tag->id)->first();
                $el->delete();
                \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął powiązanie tagu z obiektem', ['el' => $el]);
            }
        }

    }
}
