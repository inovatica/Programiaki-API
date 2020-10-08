<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesLevels extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'games_levels';
    protected $fillable = ['name', 'key', 'active', 'pos', 'game_id'];
    public $incrementing = false;

    public function game()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }

    public function assignedTags()
    {
        return $this->hasMany(GamesLevelsTagsObjects::class, 'games_levels_id', 'id');
    }
}
