<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Groups extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'groups';
    protected $fillable = ['institution_id', 'babysitter_id', 'name'];
    public $incrementing = false;
    
    public function institution() {
        return $this->belongsTo(Institutions::class, 'institution_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'babysitter_id', 'uuid');
    }
    
    public function getKids()
    {
        $children = DB::table('users')->select('users.uuid', 'users.name', 'groups_users.id as group_user')->join('groups_users', 'users.uuid', '=', 'groups_users.child_id')->where('groups_users.group_id', $this->id)->whereNull('groups_users.deleted_at')->get();
        return $children;
    }
    
    public function childs()
    {
        return $this->hasManyThrough(
            User::class,
            GroupsUsers::class,
            'group_id',
            'uuid',
            'id',
            'child_id'
        );
    }
    
}
