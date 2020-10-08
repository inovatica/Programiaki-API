<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterConfirmation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'token'
    ];

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'id','user_id');
    }
}
