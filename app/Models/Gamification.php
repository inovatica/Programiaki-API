<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GamesLevels;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gamification extends Model
{
    use SoftDeletes;
    
    protected $softDeletes = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'child_id', 'game_id', 'game_level_id', 'data', 'created_at', 'updated_at', 'deleted_at'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'child_id', 'uuid');
    }
    
    public static function findByChildGameLevel($kidUuid, $gameUuid, $levelUuid)
    {
        return Gamification::where('child_id', '=', $kidUuid)->where('game_id', '=', $gameUuid)->where('game_level_id', '=', $levelUuid)->get()->first();
    }

    public static function findByChild($kidUuid)
    {
        return Gamification::where('child_id', '=', $kidUuid)->where('active', '=', 1)->get();
    }
    
    public static function countAllActiveLevels()
    {
        return GamesLevels::where('active', '=', 1)->get()->count();
    }
    
    public function game()
    {
        return $this->belongsTo(Games::class, 'game_id', 'id');
    }

    public function gameLevel()
    {
        return $this->belongsTo(GamesLevels::class, 'game_level_id', 'id');
    }
}
