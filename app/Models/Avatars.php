<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avatars extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'avatars';

    protected $fillable = ['name', 'image_id'];
    public $incrementing = false;

    public function image()
    {
        return $this->belongsTo(Files::class, 'image_id');
    }
}
