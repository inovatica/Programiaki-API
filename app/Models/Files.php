<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Files extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'files';
    protected $fillable = ['owner_id', 'title', 'parent_type', 'parent_id', 'driver', 'original_file', 'meta_data'];
    protected $casts = ['meta_data' => 'array'];

    const DEFAULT_NO_IMG_URL = '/images/no-image.png';

    public $incrementing = false;

    public function getFile()
    {
        return '/storage/' . $this->attributes['file'];
    }
}
