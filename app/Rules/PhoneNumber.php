<?php

namespace App\Rules;

use App\Models\Other\ConvertNumberToEnglish;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
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
        $value = ConvertNumberToEnglish::Convert($value);
        return preg_match('%^(0|\+98|98|0098)?9\d{9}$%i', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.valid_phone_number');
    }
}
