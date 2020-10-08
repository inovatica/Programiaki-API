<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamesLevelsTagsObjects extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'games_levels_tags_objects';
    protected $fillable = ['tags_objects_id', 'games_levels_id'];
    public $incrementing = false;

    public function tagsObjects()
    {
        return $this->belongsTo(TagsObjects::class, 'tags_objects_id');
    }
}
