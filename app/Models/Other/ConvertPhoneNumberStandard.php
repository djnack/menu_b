<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvertPhoneNumberStandard extends Model
{
    use HasFactory;

    static function Convert($phone)
    {
        $pattern = '%^(0|\+98|98|0098)?(9\d{9})$%i';
        return '0' . preg_replace($pattern, "$2", $phone);
    }
}
