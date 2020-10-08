<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    const CACHE_KEY = 'settings';

    protected $fillable = ['value'];

    static public function apiFetch()
    {
        $settings = Cache::remember(self::CACHE_KEY, 15, function () {
            $data = [];
            foreach (parent::all(['key', 'value', 'type']) as $row) {
                switch ($row->type) {
                    case 'int':
                        $data[$row->key] = (int)$row->value;
                        break;
                    case 'string':
                        $data[$row->key] = (string)$row->value;
                        break;
                    case 'boolean':
                        $data[$row->key] = (bool)$row->value;
                        break;
                    case 'long':
                        $data[$row->key] = (float)$row->value;
                        break;
                    case 'double':
                        $data[$row->key] = (double)$row->value;
                        break;
                }
            }
            return $data;
        });

        return $settings;

    }

    public function save(array $options = [])
    {
        Cache::forget(self::CACHE_KEY);
        return parent::save($options);
    }
}
