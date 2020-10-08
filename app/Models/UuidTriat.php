<?php
/**
 * Created by PhpStorm.
 * User: apakula
 * Date: 14.03.18
 * Time: 14:43
 */

namespace App\Models;

use Webpatser\Uuid\Uuid;

trait UuidTriat
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}