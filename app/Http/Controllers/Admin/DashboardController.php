<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Games;
use App\Models\GamesLevels;
use App\Models\Objects;
use App\Models\Tags;
use App\Models\User;
use App\Models\Avatars;
use App\Models\Institutions;
use App\Models\Groups;
use App\Models\Tables;
use App\Models\Gamification;
use App\Models\Certification;
use Barryvdh\Reflection\DocBlock\Tag;

class DashboardController extends Controller
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
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = [
            'games' => ['count'=>Games::count(),'icon'=>'fa-gamepad','color'=>'aqua'],
            'levels' => ['count'=>GamesLevels::count(),'icon'=>'fa-code-fork','color'=>'green'],
            'objects' => ['count'=>Objects::count(),'icon'=>'fa-cube','color'=>'yellow'],
            'tags' => ['count'=>Tags::count(),'icon'=>'fa-cubes','color'=>'red'],
            'users' => ['count'=>User::count(),'icon'=>'fa-user-circle-o','color'=>'green'],
            'avatars' => ['count'=>Avatars::count(),'icon'=>'fa-image','color'=>'aqua'],
            'institutions' => ['count'=>Institutions::count(),'icon'=>'fa-university','color'=>'red'],
            'groups' => ['count'=>Groups::count(),'icon'=>'fa-users','color'=>'yellow'],
            'tables' => ['count'=>Tables::count(),'icon'=>'fa-tv','color'=>'aqua'],
            'gamification' => ['count'=>Gamification::count(),'icon'=>'fa-line-chart','color'=>'green'],
            'certification' => ['count'=>Certification::count(),'icon'=>'fa-graduation-cap','color'=>'red'],
        ];
        return view('admin.dashboard.index', ['result' => $result]);
    }
}
