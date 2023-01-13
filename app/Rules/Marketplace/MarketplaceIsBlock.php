<?php

namespace App\Rules\Marketplace;

use App\Models\Market\Marketplace;
use App\Models\Other\ConvertNumberToEnglish;
use Illuminate\Contracts\Validation\Rule;

class MarketplaceIsBlock implements Rule
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
        return !Marketplace::whereSlug($value)->where('is_block', 1)->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.page_not_find');
    }
}