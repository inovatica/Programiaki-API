<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institutions extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'institutions';
    protected $fillable = ['name', 'city', 'zip_code', 'street', 'street_number', 'phone'];
    public $incrementing = false;
    
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            InstitutionsUsers::class,
            'institution_id',
            'uuid',
            'id',
            'user_id'
        );
    }
    
    public function babysitters() {
        return $this->users()->whereHas(
                'roles', function($q){
                    $q->where('name', User::BABYSITTER_ROLE);
                }
            )->orderBy('name','asc')->get();
    }
    
    public function childs() {
        return $this->users()->whereHas(
                'roles', function($q){
                    $q->where('name', User::KID_ROLE);
                }
            )->orderBy('name','asc')->get();
    }
    
    public function groups()
    {
        return $this->hasMany(Groups::class, 'institution_id', 'id');
    }
}
