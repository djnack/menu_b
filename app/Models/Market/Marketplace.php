<?php

namespace App\Models\Market;

use App\Models\History;
use App\Models\Image;
use App\Models\Translate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marketplace extends Model
{
    use HasFactory, SoftDeletes;
    private $userTokenId;

    public function setNameAttribute($data)
    {
        // dd($this->attributes);
        $json = json_decode($data, true);
        dd($json);
    }

    public function translate()
    {
        return $this->morphToMany(Translate::class, 'translatables');
    }

    public function histories()
    {
        return $this->morphToMany(History::class, 'historyables');
    }

    public function image()
    {
        return $this->morphToMany(Image::class, 'imageables')->withPivot('detail');
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function getNameAttribute()
    {
        return $this->translate()->whereName('marketplace_name')->get()->pluck('text', 'lang');
    }

    public function getSloganAttribute()
    {
        return $this->translate()->whereName('marketplace_slogan')->get()->pluck('text', 'lang');
    }

    public function getImgBrandAttribute()
    {
        $data = $this->image()->wherePivot('detail', 'brand')->first();
        if ($data !== null) {
            return ['path' => $data->path, 'alt' => $data->alt];
        }
    }

    public function getImgAblAttribute()
    {
        $data = $this->image()->wherePivot('detail', 'abl')->first();
        if ($data !== null) {
            return ['path' => $data->path, 'alt' => $data->alt];
        }
    }

    public function getImgBgAttribute()
    {
        $data = $this->image()->wherePivot('detail', 'bg')->first();
        if ($data !== null) {
            return ['path' => $data->path, 'alt' => $data->alt];
        }
    }

}
