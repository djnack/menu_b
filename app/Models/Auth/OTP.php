<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Function_;

class OTP extends Model
{
    use HasFactory;

    static function getRandomCode()
    {
        $num = 6;
        $randomInteger = '';

        for ($i = 0; $i < $num; $i++) {
            $randomInteger .= rand(0, 9);
        }
        return $randomInteger;
    }
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}