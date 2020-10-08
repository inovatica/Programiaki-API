<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstitutionsUsers extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'institutions_users';
    protected $fillable = ['user_id', 'institution_id'];
    public $incrementing = false;
}
