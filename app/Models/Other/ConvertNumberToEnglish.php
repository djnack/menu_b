<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvertNumberToEnglish extends Model
{
    use HasFactory;
    static function Convert($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }

    static function ConvertAll($string)
    {
        $temp = [];

        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

        $num = range(0, 9);

        if (is_array($string)) {
            foreach ($string as $key => $item) {

                $convertedPersianNums = str_replace($persian, $num, $item);
                $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);
                $temp[$key] = $englishNumbersOnly;
            }
        }

        return $temp;
    }

}
