<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectsGroups extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'objects_groups';

    protected $fillable = ['name', 'key'];
    public $incrementing = false;
}
