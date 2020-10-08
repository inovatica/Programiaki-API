<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    static $availableTypes = ['int', 'string', 'boolean', 'long', 'double'];

    function __construct()
    {
        $this->middleware("acl:admin");
    }

    public function index()
    {
        return view('admin.settings.list', ['rows' => Settings::paginate(15)]);
    }

    /**
     * Display a listing of searched resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return $this->searchDataTable(Settings::class, 'settings', ['type', 'name', 'key', 'value']);
    }

    public function edit(Request $request, Settings $setting)
    {
        return view('admin.settings.edit', ['setting' => $setting, 'availableTypes' => self::$availableTypes]);
    }

    public function update(Request $request, Settings $setting)
    {
        $validation = [
//            'name' => 'required|max:190',
//            'type' => 'required|in:' . implode(',', self::$availableTypes),
            'value' => 'required'
        ];

        switch ($setting->type) {
            case 'int':
            case 'integer':
                $validation['value'] .= '|int';
                break;
            case 'string':
                $validation['value'] .= '|string';
                break;
            case 'boolean':
            case 'bool':
                $validation['value'] .= '|boolean';
                break;
            case 'long':
                $validation['value'] .= '|digits_between:1,19';
                break;
            case 'double':
                $validation['value'] .= '|between:-9223372036854775807,9223372036854775807';
                break;
        }

        $this->validate($request, $validation);
        \Session::flash('flash_message', ($setting->update($request->all())) ? __('save_success') : null);
        \Log::info('Użytkownik o id ' . \Auth::id() . '. zmodyfikował ustawienie ' . $setting->key);
        return response()->redirectTo(route('settings.edit', $setting->id));
    }
}
