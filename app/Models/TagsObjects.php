<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagsObjects extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'tags_objects';

    protected $fillable = ['tag_id', 'object_id'];
    public $incrementing = false;

    public function tag()
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }

    public function object()
    {
        return $this->belongsTo(Objects::class, 'object_id');
    }

}
