<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\Avatars;
use App\Services\File\Client\BaseFile;
use App\Services\File\Client\ImageFile;
use App\Services\File\Client\LocalDriver;
use App\Services\File\FileService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvatarsController extends Controller
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
        return view('admin.users.avatars.list', ['rows' => Avatars::paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.avatars.create');
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
            'name' => 'required|max:190'
        ]);

        $data = $request->all();
        $avatar = new Avatars();
        $avatar->fill($data);

        $imageId = $this->uploadImage($request, $avatar);

        if ($imageId === false) {
            $msg = [];
            if (!$imageId) {
                $msg[] = __('upload_image_error');
            }

            return \Redirect::back()->withErrors($msg)->withInput($request->all());
        }

        $data['image_id'] = $imageId;

        $avatar->fill($data);

        try {
            $avatar->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył avatar', ['avatar' => $avatar]);
            return response()->redirectTo(route('avatars.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Avatars $avatar
     * @return \Illuminate\Http\Response
     */
    public function show(Avatars $avatar)
    {
        return view('admin.users.avatars.show', ['avatar' => $avatar]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Avatars $avatar
     * @return \Illuminate\Http\Response
     */
    public function edit(Avatars $avatar)
    {
        return view('admin.users.avatars.edit', [
            'avatar' => $avatar
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Avatars $avatar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Avatars $avatar)
    {
        $this->validate($request, [
            'name' => 'required|max:190'
        ]);

        $data = $request->all();
        $avatar->fill($data);

        $imageId = $this->uploadImage($request, $avatar);

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

        $avatar->fill($data);

        try {
            $avatar->save();
            \Session::flash('success', __('save_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. utworzył avatar', ['avatar' => $avatar]);
            return \Redirect::back();
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()])->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Avatars $avatar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Avatars $avatar)
    {
        try {
            $avatar->delete();
            \Session::flash('success', __('remove_success'));
            \Log::info('Użytkownik o id ' . \Auth::id() . '. usunął avatar', ['game' => $avatar]);
            return response()->redirectTo(route('avatars.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            return \Redirect::back()->withErrors(['Błąd zapisu do bazy danych', $e->getMessage()]);
        }
    }

    private function uploadImage(Request $request, $avatar)
    {
        if (!$request->hasFile('image')) {
            return null;
        }
        $fileService = new FileService(new ImageFile(), new LocalDriver());
        if ($fileService->upload($avatar, $request->file('image'))) {
            return $fileService->getId();
        }

        return false;
    }
}
