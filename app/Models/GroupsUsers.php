<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupsUsers extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'groups_users';
    protected $fillable = ['child_id', 'group_id'];
    public $incrementing = false;
}
