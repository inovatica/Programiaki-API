<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tables extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'tables';
    protected $fillable = ['institution_id', 'key', 'active'];
    public $incrementing = false;
    
    public function institution() {
        return $this->belongsTo(Institutions::class, 'institution_id', 'id');
    }
}
