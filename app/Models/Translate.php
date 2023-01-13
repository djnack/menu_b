<?php

namespace App\Models;

use App\Models\Market\Marketplace;
use App\Models\Market\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translate extends Model
{
    use HasFactory, SoftDeletes;

    public function marketplace()
    {
        return $this->morphedByMany(Marketplace::class, 'translatables');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'translatables');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'translatables');
    }

    public static function sortTranslate($data, $list)
    {
        $temp = [];
        foreach ($data->attributes as $key => $value) {
            if (gettype($data[$key]) === 'object' && in_array($key, $list)) {
                foreach ($data[$key] as $langKey => $langValue) {
                    if (array_key_exists($langKey, $temp)) {
                        $temp[$langKey] += array($key => $langValue);
                    } else {
                        $temp[$langKey] = [$key => $langValue];
                    }
                }
            }
        }
        return $temp;
    }

    public static function checkInTranslate($name, $data)
    {
        if (count($data) === 0) {
            return null;
        }
        return array_key_exists($name, array_values($data)[0]) ? $name : null;
    }
}
