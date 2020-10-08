<?php

namespace App\Http\Controllers\Admin\Games;

use App\Models\Objects;
use App\Models\ObjectsTypes;
use App\Services\File\Client\BaseFile;
use App\Services\File\Client\ImageFile;
use App\Services\File\Client\LocalDriver;
use App\Services\File\FileService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ObjectsController extends Controller
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
        return view('admin.games.objects.list', ['rows' => Objects::orderBy('name','asc')->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.games.objects.create', [
            'types' => ObjectsTypes::all()
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
            'type_id' => 'required|exists:objects_types,id'
        ]);

        $data = $request->all();
        $object = new Objects();
        $object->fill($data);

        $audioId = $this->uploadSound($request, $object);
        $imageId = $this->uploadImage($request, $object);

        if ($audioId === false || $imageId === false) {
            $msg = [];
            if (!$audioId) {
                $msg[] = __('upload_audio_error');
            }
            if (!$imageId) {
                $msg[] = __('upload_image_error');
            }

            return \Redirect::back()->withErrors($msg)->withInput($request->all());
        }

        $data['image_id'] = $imageId;
        $data['audio_id'] = $audioId;

        $object->fill($data);

        try {
            $object->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył obiekt', ['object' => $object]);
            return response()->redirectTo(route('objects.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Objects $object
     * @return \Illuminate\Http\Response
     */
    public function show(Objects $object)
    {
        return view('admin.games.objects.show', ['object' => $object]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Objects $object
     * @return \Illuminate\Http\Response
     */
    public function edit(Objects $object)
    {
        $object->load('tags');
        return view('admin.games.objects.edit', [
            'types' => ObjectsTypes::all(),
            'object' => $object
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Objects $object
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Objects $object)
    {
        $this->validate($request, [
            'name' => 'required|max:190',
            'key' => 'required|max:190',
            'type_id' => 'required|exists:objects_types,id'
        ]);

        $data = $request->all();
        $object->fill($data);

        $audioId = $this->uploadSound($request, $object);
        $imageId = $this->uploadImage($request, $object);

        if ($audioId === false || $imageId === false) {
            $msg = [];
            if (!$audioId) {
                $msg[] = __('upload_audio_error');
            }
            if (!$imageId) {
                $msg[] = __('upload_image_error');
            }

            return \Redirect::back()->withErrors($msg)->withInput($request->all());
        }

        if ($imageId) {
            $data['image_id'] = $imageId;
        }

        if ($audioId) {
            $data['audio_id'] = $audioId;
        }

        $object->fill($data);

        try {
            $object->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył obiekt', ['object' => $object]);
            return \Redirect::back();
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Objects $object
     * @return \Illuminate\Http\Response
     */
    public function destroy(Objects $object)
    {
        try {
            $object->delete();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął obiekt', ['game' => $object]);
            return response()->redirectTo(route('objects.index'));
        } catch (\Illuminate\Database\QueryException $e) {
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

    private function uploadSound(Request $request, $object)
    {
        if (!$request->hasFile('audio')) {
            return null;
        }
        $fileService = new FileService(new BaseFile(), new LocalDriver());
        if ($fileService->upload($object, $request->file('audio'))) {
            return $fileService->getId();
        }

        return false;
    }
}
