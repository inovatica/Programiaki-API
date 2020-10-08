<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certification extends Model
{
    use SoftDeletes;
    
    protected $softDeletes = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'child_id', 'issued_at', 'created_at', 'updated_at', 'deleted_at', 'active'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'child_id', 'uuid');
    }
}
