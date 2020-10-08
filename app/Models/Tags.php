<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tags extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'tags';

    protected $fillable = ['key', 'active', 'table_id'];
    public $incrementing = false;

    public function objects()
    {
        return $this->hasManyThrough(
            Objects::class,
            TagsObjects::class,
            'tag_id',
            'id',
            'id',
            'object_id'
        );
    }
    
    public function table() {
        return $this->belongsTo(Tables::class, 'table_id', 'id');
    }
}
