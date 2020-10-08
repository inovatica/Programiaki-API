<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Models\Gamification;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes, HasApiTokens;

    const ADMIN_ROLE = 'admin';
    const BABYSITTER_ROLE = 'babysitter';
    const KID_ROLE = 'child';
    
    protected $softDeletes = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'uuid', 'active', 'deleted_at', 'image_id', 'avatar_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function activationTokens()
    {
        return $this->hasMany(\App\Models\RegisterConfirmation::class, 'user_id');
    }
    
    public function image()
    {
        return $this->belongsTo(Files::class, 'image_id');
    }

    public function avatar()
    {
        return $this->belongsTo(Avatars::class, 'avatar_id');
    }
    
    public function institutions()
    {
        return $this->hasManyThrough(
            Institutions::class,
            InstitutionsUsers::class,
            'user_id',
            'id',
            'uuid',
            'institution_id'
        );
    }

//    public function institutionsUsers()
//    {
//        return $this->belongsTo(InstitutionsUsers::class, 'uuid', 'user_id');
//    }
//
//    public function institution()
//    {
//        return Institutions::find($this->institutionsUsers['institution_id']);
//    }
    
    public function groups()
    {
        return $this->hasMany(Groups::class, 'babysitter_id', 'uuid');
    }    

    public function gamifications()
    {
        return $this->hasMany(Gamification::class, 'child_id', 'uuid');
    }    

    public function gameGamifications($gameUuid, $kidUuid)
    {
        return Gamification::where('child_id', '=', $kidUuid)->where('game_id', '=', $gameUuid)->get();
    }
    
    public function child_groups()
    {
        return $this->hasManyThrough(
            Groups::class,
            GroupsUsers::class,
            'child_id',
            'id',
            'uuid',
            'group_id'
        );
    }
    
}
