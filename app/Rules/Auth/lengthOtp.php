<?php

namespace App\Rules\Auth;

use App\Models\Other\ConvertNumberToEnglish;
use Illuminate\Contracts\Validation\Rule;

class lengthOtp implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $data = ConvertNumberToEnglish::Convert($value);
        return strlen($data) === 6;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.otp');
    }
}