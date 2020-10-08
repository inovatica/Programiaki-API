<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectsTypes extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'objects_types';

    protected $fillable = ['name', 'key'];
    public $incrementing = false;

}
