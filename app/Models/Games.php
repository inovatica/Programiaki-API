<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Games extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'games';
    protected $fillable = ['name', 'key', 'active', 'pos'];
    public $incrementing = false;

    public function levels()
    {
        return $this->hasMany(GamesLevels::class, 'game_id')->orderBy('pos', 'asc');
    }
}
