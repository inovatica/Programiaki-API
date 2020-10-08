<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objects extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'objects';

    protected $fillable = ['name', 'key', 'image_id', 'group_id', 'audio_id', 'type_id'];
    public $incrementing = false;

    public function type()
    {
        return $this->belongsTo(ObjectsTypes::class, 'type_id');
    }

    public function group()
    {
        return $this->belongsTo(ObjectsGroups::class, 'group_id');
    }

    public function image()
    {
        return $this->belongsTo(Files::class, 'image_id');
    }

    public function audio()
    {
        return $this->belongsTo(Files::class, 'audio_id');
    }

    public function tags()
    {
        return $this->hasManyThrough(
            Tags::class,
            TagsObjects::class,
            'object_id',
            'id',
            'id',
            'tag_id'
        );

    }

    public function meal()
    {
        return $this->hasManyThrough(
            Objects::class,
            ObjectsFoodChain::class,
            'consumer_id',
            'id',
            'id',
            'meal_id'
        );

    }
}
