<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectsFoodChain extends Model
{
    use UuidTriat;
    use SoftDeletes;

    protected $table = 'objects_food_chain';

    protected $fillable = ['meal_id', 'consumer_id'];
    public $incrementing = false;
}
